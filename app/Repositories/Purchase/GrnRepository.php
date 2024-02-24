<?php

namespace App\Repositories\Purchase;

use App\Account;
use App\Bill;
use App\Grn;
use App\GrnItem;
use App\Http\Requests\Purchase\BillStoreRequest;
use App\PurchaseOrder;
use App\Repositories\BaseRepository;
use App\Stock;
use App\StockHistory;
use Illuminate\Http\Request;

/**
 * Class GrnRepository
 * @package App\Repositories\Purchase
 */
class GrnRepository extends BaseRepository
{
    protected $bill;

    /**
     * GrnRepository constructor.
     * @param Grn|null $grn
     * @param BillRepository $bill
     */
    public function __construct(Grn $grn = null, BillRepository $bill)
    {
        $this->setModel($grn ?? new Grn());
        $this->setCodePrefix('GRN', 'code');
        $this->bill = $bill;
    }

    public function grid()
    {
        $filter = request()->input('filter');
        $search = request()->input('search');
        $lastWeek = carbon()->subWeek();
        $grns = Grn::whereIn('company_id', userCompanyIds(loggedUser()))
            ->orderBy('id', 'desc')->with('purchaseOrder', 'store', 'supplier', 'company', 'items');
        if ($search) {
            $grns->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', '%' . $search . '%')
                    ->orwhere(function ($query) use ($search) {
                        $query->whereHas('supplier', function ($q) use ($search) {
                            $q->where('code', 'LIKE', '%' . $search . '%')
                                ->orWhere('full_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('display_name', 'LIKE', '%' . $search . '%');
                        });
                    });
            });
        }

        switch ($filter) {
            case 'drafted':
                $grns->where('status', 'Draft');
                break;
            case 'sent':
                $grns->where('status', 'Open');
                break;
            case 'partiallyReceived':
                $grns->where('status', 'Overdue');
                break;
            case 'received':
                $grns->where('status', 'Partially Paid');
                break;
            case 'recentlyCreated':
                $grns->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $grns->where('updated_at', '>', $lastWeek);
                break;
        }

        $grns = $grns->paginate(20);
        $grnsData = $grns->getCollection()->transform(function($item){
            $item->total = $item->items()->sum('amount');
            return $item;
        });
        $grns->setCollection($grnsData);
        return $grns->toArray();
    }

    public function save(Request $request, PurchaseOrder $order)
    {
        /** save GRN data */
        $this->model->setAttribute('code', $this->getCode());
        $this->model->setAttribute('date', carbon()->now()->toDateString());
        $this->model->setAttribute('grn_for', $order->getAttribute('po_for'));
        $this->model->setAttribute('status', 'Drafted');
        $this->model->setAttribute('transfer_by', $request->input('transfer_by'));

        $this->model->setAttribute('vehicle_id', $request->input('vehicle_id'));
        $this->model->setAttribute('odo_starts_at', $request->input('odo_starts_at'));
        $this->model->setAttribute('odo_ends_at', $request->input('odo_ends_at'));
        $this->model->setAttribute('driver', $request->input('driver'));
        $this->model->setAttribute('helper', $request->input('helper'));

        $this->model->setAttribute('vehicle_no', $request->input('vehicle_no'));
        $this->model->setAttribute('transport_name', $request->input('transport_name'));
        $this->model->setAttribute('driver_name', $request->input('driver_name'));
        $this->model->setAttribute('helper_name', $request->input('helper_name'));

        $this->model->setAttribute('loaded_by', $request->input('loaded_by'));

        $this->model->setAttribute('purchase_order_id', $order->getAttribute('id'));
        $this->model->setAttribute('prepared_by', auth()->id());
        $this->model->setAttribute('production_unit_id', $order->getAttribute('production_unit_id'));
        $this->model->setAttribute('store_id', $order->getAttribute('store_id'));
        $this->model->setAttribute('shop_id', $order->getAttribute('shop_id'));
        $this->model->setAttribute('supplier_id', $order->getAttribute('supplier_id'));
        $this->model->setAttribute('company_id', $order->getAttribute('company_id'));
        $this->model->save();

        /** add GRN Items */
        $products = $request->input('products');
        $productIds = array_get($products, 'product_id');
        $batch_no = array_get($products, 'batch_no');
        $manufacture = array_get($products, 'manufacture');
        $expiry = array_get($products, 'expiry');
        $brand = array_get($products, 'brand');
        $grade = array_get($products, 'grade');
        $color = array_get($products, 'color');
        $packing_type = array_get($products, 'packing_type');
        $no_of_bags = array_get($products, 'no_of_bags');
        $quantity = array_get($products, 'quantity');
        $issuedQuantity = array_get($products, 'issued_qty');
        $rate = array_get($products, 'rate');
        $amount = array_get($products, 'amount');

        if ($productIds) {
            foreach ($productIds as $key => $val) {
                $grnItem = new GrnItem();
                $grnItem->setAttribute('quantity', (int)array_get($quantity, $key));
                $grnItem->setAttribute('issued_qty', (int)array_get($issuedQuantity, $key));
                $grnItem->setAttribute('pending_qty', ((int)array_get($quantity, $key) - (int)array_get($issuedQuantity, $key)));
                $grnItem->setAttribute('rate', array_get($rate, $key));
                $grnItem->setAttribute('amount', array_get($amount, $key));
                $grnItem->setAttribute('manufacture_date', array_get($manufacture, $key));
                $grnItem->setAttribute('expiry_date', array_get($expiry, $key));
                $grnItem->setAttribute('batch_no', array_get($batch_no, $key));
                $grnItem->setAttribute('grade', array_get($grade, $key));
                $grnItem->setAttribute('color', array_get($color, $key));
                $grnItem->setAttribute('packing_type', array_get($packing_type, $key));
                $grnItem->setAttribute('no_of_bags', array_get($no_of_bags, $key));
                $grnItem->setAttribute('brand', array_get($brand, $key));
                $grnItem->setAttribute('status', 'Sent');
                $grnItem->setAttribute('product_id', array_get($productIds, $key));
                $grnItem->setAttribute('grn_id', $this->model->id);
                $grnItem->save();
            }
        }

        /** save bill data */
        $request->merge(['amount' => $this->model->items()->sum('amount')]);
        $poBill = $this->bill->saveBill($request, $order);

        /** update GRN id to bill table */
        $poBill->grn_id = $this->model->id;
        $poBill->save();

        /** update bill id to GRN */
        $this->model->setAttribute('bill_id', $poBill->id);
        $this->model->save();

        $order->setAttribute('grn_created', 'Yes');
        $order->save();

        return $this->model->refresh();
    }

    public function mapGrnItems(Request $request)
    {
        $mappedItems = [];

        $products = $request->input('products');
        $product_id = $request->input('product_id');
        $batch_no = $request->input('batch_no');
        $manufacture = $request->input('manufacture');
        $expiry = $request->input('expiry');
        $brand = $request->input('brand');
        $grade = $request->input('grade');
        $color = $request->input('color');
        $packing_type = $request->input('packing_type');
        $quantity = $request->input('quantity');
        $rate = $request->input('rate');
        $amount = $request->input('amount');
        foreach ($products as $key => $product) {
            if (!$product) {
                continue;
            }
            $mappedItem = [
                'quantity' => $quantity[$key] ?? null,
                'rate' => $rate[$key] ?? null,
                'amount' => $amount[$key] ?? null,
                'manufacture_date' => $manufacture[$key] ?? null,
                'expiry_date' => $expiry[$key] ?? null,
                'batch_no' => $batch_no[$key] ?? null,
                'grade' => $grade[$key] ?? null,
                'color' => $color[$key] ?? null,
                'packing_type' => $packing_type[$key] ?? null,
                'brand' => $brand[$key] ?? null,
                'status' => 'Sent',
                'product_id' => $product_id[$key] ?? null,
                'grn_id' => $this->model->id ?? null
            ];
            array_push($mappedItems, $mappedItem);
        }
        return $mappedItems;
    }

    public function approve(Grn $grn): array
    {
        $grn->setAttribute('status', 'Sent');
        $grn->save();
        return ['success' => true];
    }

    public function receive(Request $request, Grn $grn)
    {
        /** update grn details */
        $grn->setAttribute('odo_ends_at', $request->input('odo_ends_at'));
        $grn->setAttribute('unloaded_by', $request->input('unloaded_by'));
        $grn->setAttribute('status', 'Received');
        $grn->save();

        $order = $grn->purchaseOrder;
        $order->setAttribute('grn_received', 'Yes');
        $order->save();

        /** update products details */
        $products = $request->input('products');
        $productIds = array_get($products, 'product_id');
        $receivedQty = array_get($products, 'received_qty');

        if($grn->grn_for == 'PUnit') {

            if ($productIds) {
                foreach ($productIds as $keyPro => $valPro) {
                    /** get grn item */
                    $grnItem = GrnItem::where('grn_id', $grn->id)
                        ->where('product_id', $keyPro)->first();

                    $grnItem->received_qty = (int)array_get($receivedQty, $keyPro);
                    $grnItem->rejected_qty = ($grnItem->issued_qty - (int)array_get($receivedQty, $keyPro));
                    $grnItem->received_amount = ($grnItem->rate * (int)array_get($receivedQty, $keyPro));
                    $grnItem->status = 'Received';
                    $grnItem->save();

                    /** update stock history */
                    $receivedTo = Stock::where('store_id', $grn->store_id)
                        ->where('product_id', $keyPro)
                        ->first();

                    if($receivedTo){
                        $receivedTo->available_stock = ((float)$receivedTo->available_stock + (int)array_get($receivedQty, $keyPro));
                        $receivedTo->save();

                        /** add stock history as stock available & received */
                        $history = new StockHistory();
                        $history->setAttribute('stock_id', $receivedTo->getAttribute('id'));
                        $history->setAttribute('quantity', (int)array_get($receivedQty, $keyPro));
                        $history->setAttribute('type', 'Purchase');
                        $history->setAttribute('transaction', 'In');
                        $history->setAttribute('trans_date', carbon()->now()->toDateString());
                        $history->setAttribute('trans_description', 'Stock purchased from '.$grn->supplier->display_name);
                        $history->save();
                    }else{
                        /** add new stock if existing stock not available */
                        $newStock = new Stock();
                        $newStock->setAttribute('store_id', $grn->store_id);
                        $newStock->setAttribute('available_stock', (int)array_get($receivedQty, $keyPro));
                        $newStock->setAttribute('product_id', $keyPro);
                        $newStock->setAttribute('notes', 'Stock purchased from '.$grn->supplier->display_name);
                        $newStock->setAttribute('type', 'Auto');
                        $newStock->setAttribute('company_id', $grn->company_id);
                        $newStock->setAttribute('min_stock_level', '5000');
                        $newStock->save();

                        /** add new stock history */
                        $newStockHistory = new StockHistory();
                        $newStockHistory->setAttribute('stock_id', $newStock->getAttribute('id'));
                        $newStockHistory->setAttribute('quantity', (int)array_get($receivedQty, $keyPro));
                        $newStockHistory->setAttribute('type', 'Purchase');
                        $newStockHistory->setAttribute('transaction', 'In');
                        $newStockHistory->setAttribute('trans_date', carbon()->now()->toDateString());
                        $newStockHistory->setAttribute('trans_description', 'Stock purchased from '.$grn->supplier->display_name);
                        $newStockHistory->save();
                    }
                }
                /** update bill amount as per received qty */
                $billAmount = $grn->items()->sum('received_amount');
                $bill = $grn->bill;
                $bill->amount = $billAmount;
                $bill->save();

                /** record transaction */
                $this->recordTransactionPUnit($grn, false);
            }

        } else if($grn->grn_for == 'Store') {
            if ($productIds) {
                foreach ($productIds as $keyPro => $valPro) {
                    /** get grn item */
                    $grnItem = GrnItem::where('grn_id', $grn->id)
                        ->where('product_id', $keyPro)->first();

                    $grnItem->received_qty = (int)array_get($receivedQty, $keyPro);
                    $grnItem->rejected_qty = ($grnItem->issued_qty - (int)array_get($receivedQty, $keyPro));
                    $grnItem->received_amount = ($grnItem->rate * (int)array_get($receivedQty, $keyPro));
                    $grnItem->status = 'Received';
                    $grnItem->save();

                    /** update stock history */
                    $receivedTo = Stock::where('store_id', $grn->store_id)
                        ->where('product_id', $keyPro)
                        ->first();

                    if($receivedTo){
                        $receivedTo->available_stock = ((float)$receivedTo->available_stock + (int)array_get($receivedQty, $keyPro));
                        $receivedTo->save();

                        /** add stock history as stock available & received */
                        $history = new StockHistory();
                        $history->setAttribute('stock_id', $receivedTo->getAttribute('id'));
                        $history->setAttribute('quantity', (int)array_get($receivedQty, $keyPro));
                        $history->setAttribute('type', 'Purchase');
                        $history->setAttribute('transaction', 'In');
                        $history->setAttribute('trans_date', carbon()->now()->toDateString());
                        $history->setAttribute('trans_description', 'Stock purchased from '.$grn->supplier->display_name);
                        $history->save();
                    }else{
                        /** add new stock if existing stock not available */
                        $newStock = new Stock();
                        $newStock->setAttribute('store_id', $grn->store_id);
                        $newStock->setAttribute('available_stock', (int)array_get($receivedQty, $keyPro));
                        $newStock->setAttribute('product_id', $keyPro);
                        $newStock->setAttribute('notes', 'Stock purchased from '.$grn->supplier->display_name);
                        $newStock->setAttribute('type', 'Auto');
                        $newStock->setAttribute('company_id', $grn->company_id);
                        $newStock->setAttribute('min_stock_level', '5000');
                        $newStock->save();

                        /** add new stock history */
                        $newStockHistory = new StockHistory();
                        $newStockHistory->setAttribute('stock_id', $newStock->getAttribute('id'));
                        $newStockHistory->setAttribute('quantity', (int)array_get($receivedQty, $keyPro));
                        $newStockHistory->setAttribute('type', 'Purchase');
                        $newStockHistory->setAttribute('transaction', 'In');
                        $newStockHistory->setAttribute('trans_date', carbon()->now()->toDateString());
                        $newStockHistory->setAttribute('trans_description', 'Stock purchased from '.$grn->supplier->display_name);
                        $newStockHistory->save();
                    }

                    /** update stock history for from store */
                    $receivedFrom = Stock::where('store_id', $grn->supplier->supplierable_id)
                        ->where('product_id', $keyPro)
                        ->first();
                    if($receivedFrom){
                        $receivedFrom->available_stock = ((float)$receivedFrom->available_stock - (int)array_get($receivedQty, $keyPro));
                        $receivedFrom->save();

                        /** add stock history as stock available & received */
                        $fromHistory = new StockHistory();
                        $fromHistory->setAttribute('stock_id', $receivedFrom->getAttribute('id'));
                        $fromHistory->setAttribute('quantity', (int)array_get($receivedQty, $keyPro));
                        $fromHistory->setAttribute('type', 'Purchase');
                        $fromHistory->setAttribute('transaction', 'Out');
                        $fromHistory->setAttribute('trans_date', carbon()->now()->toDateString());
                        $fromHistory->setAttribute('trans_description', 'Stock sold to '.$grn->store->name);
                        $fromHistory->save();
                    }
                }
                /** update bill amount as per received qty */
                $billAmount = $grn->items()->sum('received_amount');
                $bill = $grn->bill;
                $bill->amount = $billAmount;
                $bill->save();

                /** record transaction */
                $this->recordTransactionStore($grn, false);
            }
        } else if($grn->grn_for == 'Shop') {

        }
    }

    /**
     * @param Grn $grn
     * @param bool $isEdit
     */
    public function recordTransactionStore(Grn $grn, $isEdit = false)
    {
        /** First transaction */
        // Eg: Finished Goods sales by AGM Production to Thampy Store
        // eg: Sales - AGM Production - CR
        // eg: Anna Industry - DR (Store Company Account)

        $order = $grn->purchaseOrder;
        $supplier = $order->supplier;
        $store = $grn->store;
        $supplierId = $supplier->supplierable_id;
        $bill = $grn->bill;

        if($order->supply_from === 'PUnit'){
            $creditAccount1 = Account::where('account_type_id', 11)
                ->where('prefix', 'Sales')
                ->where('accountable_id', $supplierId)
                ->where('accountable_type', 'App\ProductionUnit')
                ->first();
        }else if($order->supply_from === 'Store'){
            $creditAccount1 = Account::where('account_type_id', 11)
                ->where('prefix', 'Sales')
                ->where('accountable_id', $supplierId)
                ->where('accountable_type', 'App\Store')
                ->first();
        }else{
            $creditAccount1 = Account::where('id', 8)
                ->first();
        }

        $debitAccount1 = Account::where('account_type_id', 3)
            ->where('prefix', 'Company')
            ->where('accountable_id', $store->company_id)
            ->where('accountable_type', 'App\Company')
            ->first();

        recordTransaction($grn, $debitAccount1, $creditAccount1, [
            'date' => now()->toDateString(),
            'type' => 'Deposit',
            'amount' => $bill->amount,
            'auto_narration' => $supplier->display_name.' sold and sent goods to '.$store->name,
            'manual_narration' => $supplier->display_name.' sold and sent goods to '.$store->name,
            'tx_type_id' => 59,
            'supplier_id' => $grn->supplier_id,
            'company_id' => $grn->company_id,
        ], 'GoodsSold', $isEdit);

        /** Second transaction */
        // Eg: Finished Goods purchase by Thampy Store
        // eg: Purchase - Thampy Store - DR
        // eg: AGM Production - CR (AGM Account)

        $debitAccount2 = Account::where('account_type_id', 3)
            ->where('prefix', 'Purchase')
            ->where('accountable_id', $store->id)
            ->where('accountable_type', 'App\Store')
            ->first();

        if($order->supply_from === 'PUnit') {
            $creditAccount2 = Account::where('account_type_id', 3)
                ->where('prefix', 'Unit')
                ->where('accountable_id', $supplierId)
                ->where('accountable_type', 'App\ProductionUnit')
                ->first();
        }else if($order->supply_from === 'Store'){
            $creditAccount2 = Account::where('account_type_id', 3)
                ->where('prefix', 'Store')
                ->where('accountable_id', $supplierId)
                ->where('accountable_type', 'App\Store')
                ->first();
        }else{
            $creditAccount2 = Account::where('account_type_id', 3)
                ->where('prefix', 'Company')
                ->where('accountable_id', $grn->getAttribute('company_id'))
                ->where('accountable_type', 'App\Company')
                ->first();
        }

        recordTransaction($grn, $debitAccount2, $creditAccount2, [
            'date' => now()->toDateString(),
            'type' => 'Deposit',
            'amount' => $bill->amount,
            'auto_narration' => $store->name.' purchased and received goods from '.$supplier->display_name,
            'manual_narration' => $store->name.' purchased and received goods from '.$supplier->display_name,
            'tx_type_id' => 58,
            'supplier_id' => $grn->supplier_id,
            'company_id' => $grn->company_id,
        ], 'GoodsPurchased', $isEdit);
    }

    public function recordTransactionPUnit(Grn $grn, $isEdit = false)
    {
        /** First transaction */
        // Eg: Raw materials purchase by AGM from out Supplier
        // eg: Purchase - AGM Production - DR
        // eg: Anna Industry - CR (PUnit Company Account)

        $order = $grn->purchaseOrder;
        $supplier = $order->supplier;
        $productionUnit = $grn->productionUnit;
        $bill = $grn->bill;

        $creditAccount1 = Account::where('account_type_id', 3)
            ->where('prefix', 'Purchase')
            ->where('accountable_id', $grn->getAttribute('production_unit_id'))
            ->where('accountable_type', 'App\ProductionUnit')
            ->first();

        $debitAccount1 = Account::where('account_type_id', 3)
            ->where('prefix', 'Company')
            ->where('accountable_id', $grn->getAttribute('company_id'))
            ->where('accountable_type', 'App\Company')
            ->first();

        recordTransaction($grn, $debitAccount1, $creditAccount1, [
            'date' => now()->toDateString(),
            'type' => 'Deposit',
            'amount' => $bill->amount,
            'auto_narration' => $productionUnit->name.' purchased and received goods from '.$supplier->display_name,
            'manual_narration' => $productionUnit->name.' purchased and received goods from '.$supplier->display_name,
            'tx_type_id' => 59,
            'supplier_id' => $grn->getAttribute('supplier_id'),
            'company_id' => $grn->getAttribute('company_id'),
        ], 'GoodsPurchased', $isEdit);

        /** Second transaction */
        // Eg: Raw materials purchase by AGM from out Supplier
        // eg: AGM Production - DR (AGM Account)
        // eg: Account Payable - CR

        $debitAccount2 = Account::where('account_type_id', 3)
            ->where('prefix', 'Unit')
            ->where('accountable_id', $grn->getAttribute('production_unit_id'))
            ->where('accountable_type', 'App\ProductionUnit')
            ->first();

        $creditAccount2 = Account::find(8);

        recordTransaction($grn, $debitAccount2, $creditAccount2, [
            'date' => now()->toDateString(),
            'type' => 'Deposit',
            'amount' => $bill->amount,
            'auto_narration' => $supplier->display_name.' sold and sent goods to '.$productionUnit->name,
            'manual_narration' => $supplier->display_name.' sold and sent goods to '.$productionUnit->name,
            'tx_type_id' => 59,
            'supplier_id' => $grn->getAttribute('supplier_id'),
            'company_id' => $grn->getAttribute('company_id'),
        ], 'GoodsSold', $isEdit);
    }

    /**
     * @param string $method
     * @param Grn|null $grn
     * @return array
     */
    public function breadcrumbs(string $method, Grn $grn = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Goods receipt note'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Goods receipt note', 'route' => 'purchase.grn.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Goods receipt note', 'route' => 'purchase.grn.index'],
                ['text' => $grn->code ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Goods receipt note', 'route' => 'purchase.grn.index'],
                ['text' => $grn->code ?? ''],
                ['text' => 'Edit'],
            ],
            'print' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Goods receipt note', 'route' => 'purchase.grn.index'],
                ['text' => $grn->code ?? ''],
                ['text' => 'Print GRN'],
            ],
            'receive' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Goods receipt note', 'route' => 'purchase.grn.index'],
                ['text' => $grn->code ?? ''],
                ['text' => 'Receive GRN'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}