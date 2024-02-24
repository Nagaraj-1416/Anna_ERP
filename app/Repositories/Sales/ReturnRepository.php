<?php

namespace App\Repositories\Sales;

use App\Account;
use App\Customer;
use App\DailySaleItem;
use App\Invoice;
use App\InvoicePayment;
use App\Product;
use App\Repositories\BaseRepository;
use App\SalesOrder;
use App\SalesReturn;
use App\SalesReturnItem;
use App\SalesReturnReplaces;
use App\SalesReturnResolution;
use Illuminate\Http\Request;

/**
 * Class ReturnRepository
 * @package App\Repositories\Sales
 */
class ReturnRepository extends BaseRepository
{
    protected $payment;

    /**
     * ReturnRepository constructor.
     * @param SalesReturn|null $salesReturn
     * @param PaymentRepository $payment
     */
    public function __construct(SalesReturn $salesReturn = null, PaymentRepository $payment)
    {
        $this->setModel($salesReturn ?? new SalesReturn());
        $this->setCodePrefix('SR');

        $this->payment = $payment;
    }

    /**
     * @param Customer $customer
     * @return \Illuminate\Support\Collection
     */
    public function productsAverageRates(Customer $customer = null)
    {
        if ($customer) {
            $customers = [$customer->id];
        } else {
            $customers = getAllAllocatedCustomers()->pluck('id')->toArray();
        }

        $products = Product::whereHas('salesOrders', function ($q) use ($customers) {
            $q->whereIn('customer_id', $customers);
        })->with(['salesOrders' => function ($q) use ($customers) {
            $q->whereIn('customer_id', $customers);
        }])->get();
        $productItems = $products->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'tamil_name' => $item->tamil_name,
                'code' => $item->code,
                'orders' => $item->salesOrders->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'customer_id' => $order->customer_id,
                        'order_no' => $order->order_no,
                        'ref' => $order->ref,
                        'rate' => $order->pivot ? $order->pivot->rate : 0,
                        'quantity' => $order->pivot ? $order->pivot->quantity : 0,
                    ];
                })
            ];
        });
        return array_values($productItems->toArray());
    }

    public function grid()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $lastWeek = carbon()->subWeek();
        $returns = SalesReturn::whereIn('company_id', userCompanyIds(loggedUser()))
            ->orderBy('id', 'desc')->with('customer', 'company', 'allocation', 'items');
        if ($search) {
            $returns->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', '%' . $search . '%')
                    ->orWhere('notes', 'LIKE', '%' . $search . '%')
                    ->orwhere(function ($query) use ($search) {
                        $query->whereHas('customer', function ($q) use ($search) {
                            $q->where('code', 'LIKE', '%' . $search . '%')
                                ->orWhere('full_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('display_name', 'LIKE', '%' . $search . '%');
                        });
                    });
            });
        }

        switch ($filter) {
            case 'recentlyCreated':
                $returns->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $returns->where('updated_at', '>', $lastWeek);
                break;
        }

        return $returns->paginate(12)->toArray();
    }

    /**
     * @param Request $request
     * @param Customer $customer
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(Request $request, Customer $customer)
    {
        $dailySales = getRepAllocation()->first();
        $dailySaleItemFirst = $dailySales->items()->first();
        $company = userCompany();
        $this->model->setAttribute('code', $this->getCode());
        $this->model->setAttribute('date', $request->input('date'));
        $this->model->setAttribute('notes', $request->input('notes'));
        $this->model->setAttribute('is_printed', $request->input('is_printed', 'No'));
        $this->model->setAttribute('daily_sale_id', $dailySales ? $dailySales->id : null);
        $this->model->setAttribute('route_id', $dailySales ? $dailySales->route_id : null);
        $this->model->setAttribute('rep_id', $dailySales ? $dailySales->rep_id : null);
        $this->model->setAttribute('customer_id', $customer->id);
        $this->model->setAttribute('prepared_by', auth()->id());
        $this->model->setAttribute('company_id', $company ? $company->id : null);
        $items = $request->input('items');
        $resolutions = $request->input('resolutions');
        $replaceItems = $request->input('return_products');
        $this->model->save();
        $this->model->refresh();
        foreach ($items as $item) {
            $returnItem = new SalesReturnItem();
            $returnItem->setAttribute('date', $this->model->date);
            $returnItem->setAttribute('qty', array_get($item, 'qty'));
            $returnItem->setAttribute('type', array_get($item, 'type'));
            $returnItem->setAttribute('sold_rate', array_get($item, 'sold_rate'));
            $returnItem->setAttribute('returned_rate', array_get($item, 'returned_rate'));
            $returnItem->setAttribute('returned_amount', array_get($item, 'returned_amount'));
            $returnItem->setAttribute('reason', array_get($item, 'reason'));
            $returnItem->setAttribute('sales_return_id', $this->model->id);
            $returnItem->setAttribute('customer_id', $this->model->customer_id);
            $returnItem->setAttribute('company_id', $this->model->company_id);
            $returnItem->setAttribute('daily_sale_id', $this->model->daily_sale_id);
            $returnItem->setAttribute('route_id', $this->model->route_id);
            $returnItem->setAttribute('rep_id', $this->model->rep_id);
            $returnItem->setAttribute('order_id', array_get($item, 'order_id'));
            $returnItem->setAttribute('product_id', array_get($item, 'product_id'));
            //$returnItem->setAttribute('manufacture_date', array_get($item, 'manufacture_date'));
            //$returnItem->setAttribute('expiry_date', array_get($item, 'expiry_date'));
            $returnItem->save();
            $returnItem->refresh();

            /** add new daily sales item if allocated sales doesn't have the item */
            /** get daily sales item details */
            if(array_get($item, 'reason') == 'Product was damaged or defective' || array_get($item, 'reason') == 'Product was expired'){
                $dailySaleItem1 = DailySaleItem::where('daily_sale_id', $dailySales->id)
                    ->where('product_id', array_get($item, 'product_id'))->first();
                if($dailySaleItem1){
                    $dailySaleItem1->damaged_qty = ($dailySaleItem1->damaged_qty + array_get($item, 'qty'));
                    $dailySaleItem1->save();
                }else{
                    $newSaleableItem1 = new DailySaleItem();
                    $newSaleableItem1->setAttribute('daily_sale_id', $dailySales->id);
                    $newSaleableItem1->setAttribute('product_id', array_get($item, 'product_id'));
                    $newSaleableItem1->setAttribute('store_id', $dailySaleItemFirst->store_id);
                    $newSaleableItem1->setAttribute('quantity', 0);
                    $newSaleableItem1->setAttribute('added_stage', 'Later');
                    $newSaleableItem1->setAttribute('damaged_qty', array_get($item, 'qty'));
                    $newSaleableItem1->save();
                }
            }else if (array_get($item, 'reason') == 'Product no longer needed' || array_get($item, 'reason') == 'Product was not moving' || array_get($item, 'reason') == 'Product did not fit the customer’s expectations'){
                $dailySaleItem = DailySaleItem::where('daily_sale_id', $dailySales->id)
                    ->where('product_id', array_get($item, 'product_id'))->first();
                if($dailySaleItem){
                    $dailySaleItem->returned_qty = ($dailySaleItem->returned_qty + array_get($item, 'qty'));
                    $dailySaleItem->save();
                }else{
                    $newSaleableItem = new DailySaleItem();
                    $newSaleableItem->setAttribute('daily_sale_id', $dailySales->id);
                    $newSaleableItem->setAttribute('product_id', array_get($item, 'product_id'));
                    $newSaleableItem->setAttribute('store_id', $dailySaleItemFirst->store_id);
                    $newSaleableItem->setAttribute('quantity', 0);
                    $newSaleableItem->setAttribute('added_stage', 'Later');
                    $newSaleableItem->setAttribute('returned_qty', array_get($item, 'qty'));
                    $newSaleableItem->save();
                }
            }
        }
        $payment = null;
        if ($resolutions) {
            foreach ($resolutions as $resolution) {
                $resolutionModel = new SalesReturnResolution();
                $resolutionModel->setAttribute('resolution', array_get($resolution, 'type'));
                $resolutionModel->setAttribute('amount', array_get($resolution, 'amount'));
                $resolutionModel->setAttribute('sales_return_id', $this->model->id);
                $resolutionModel->setAttribute('order_id', array_get($resolution, 'order_id'));
                $resolutionModel->save();

                if ($resolutionModel->resolution == 'Replace') {
                    if ($replaceItems) {
                        foreach ($replaceItems as $replaceItem) {
                            $replace = new SalesReturnReplaces();
                            $replace->setAttribute('qty', array_get($replaceItem, 'qty'));
                            $replace->setAttribute('rate', array_get($replaceItem, 'rate'));
                            $replace->setAttribute('amount', array_get($replaceItem, 'amount'));
                            $replace->setAttribute('product_id', array_get($replaceItem, 'product_id'));
                            $replace->setAttribute('resolution_id', $resolutionModel->id);
                            $replace->setAttribute('sales_return_id', $this->model->id);
                            $replace->save();
                        }
                    }
                }

                /** create a cash payment record against customer order*/
                if ($resolutionModel->resolution == 'Credit') {
                    /** get invoice */
                    $invoice = Invoice::where('sales_order_id', array_get($resolution, 'order_id'))->first();
                    if($invoice) {
                        $request->merge(['payment_notes' => 'Payment created from sales return with Credit resolution']);
                        $request->merge(['payment_date' => $request->input('date')]);
                        $request->merge(['payment' => array_get($resolution, 'amount')]);
                        $request->merge(['payment_mode' => 'Customer Credit']);
                        $request->merge(['payment_type' => 'Partial Payment']);
                        $request->merge(['daily_sale_id' => $dailySales ? $dailySales->id : null]);
                        $request->merge(['deposited_to' => 1]);
                        $payment = $this->payment->save($request, $invoice);
                        /** END */

                        /** sales return double entry */
                        $debitAccount = Account::find(51);
                        $creditAccount = Account::find(3);
                        recordTransaction($this->model, $debitAccount, $creditAccount, [
                            'date' => now()->toDateString(),
                            'type' => 'Deposit',
                            'amount' => $payment->payment,
                            'auto_narration' => 'Sales return received for '.$payment->payment.', and credit note payment set-off against invoice no: '.$invoice->ref,
                            'manual_narration' => 'Sales return received for '.$payment->payment.', and credit note payment set-off against invoice no: '.$invoice->ref,
                            'tx_type_id' => 7,
                            'customer_id' => $payment->customer_id,
                            'company_id' => $payment->company_id,
                        ], 'SalesReturn', false);

                    }
                }
            }
        }
        $model =  $this->model->refresh();
        $model->setAttribute('payment',$payment );
        return $model;
    }

    public function isPrinted(SalesReturn $return, Request $request)
    {
        $return->setAttribute('is_printed', $request->input('is_printed'));
        $return->save();
        return $return->refresh();
    }

    /**
     * @param string $method
     * @param SalesReturn|null $return
     * @return array
     */
    public function breadcrumbs(string $method, SalesReturn $return = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Returns'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Returns', 'route' => 'sales.return.index'],
                ['text' => $return->code ?? ''],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}
