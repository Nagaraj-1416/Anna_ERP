<?php

namespace App\Repositories\Sales;

use App\Account;
use App\Invoice;
use App\InvoicePayment;
use App\Product;
use App\Repositories\BaseRepository;
use App\SalesHandover;
use App\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class CashSalesRepository
 * @package App\Repositories\Sales
 */
class CashSalesRepository extends BaseRepository
{
    public $handover;

    /**
     * CashSalesRepository constructor.
     * @param SalesOrder|null $order
     */
    public function __construct(SalesOrder $order = null)
    {
        $this->setModel($order ?? new SalesOrder());
        $this->setCodePrefix('SO', 'order_no');
        $this->setRefPrefix('OR');
        $this->handover = new HandOverRepository();
    }

    public function grid()
    {
        $request = request();
        $search = $request->input('search');
        $fromDate = $request->input('from_date') ?? carbon()->toDateString();
        $toDate = $request->input('to_date') ?? carbon()->toDateString();
        $orders = SalesOrder::where('prepared_by', auth()->id())->orderBy('id', 'desc')->with('products', 'preparedBy');
        if ($search) {
            $orders->where('order_no', 'LIKE', '%' . $search . '%')
                ->orWhere('status', 'LIKE', '%' . $search . '%')
                ->orWhere('delivery_status', 'LIKE', '%' . $search . '%')
                ->orWhere('delivery_date', 'LIKE', '%' . $search . '%')
                ->orwhere(function ($query) use ($search) {
                    $query->whereHas('customer', function ($q) use ($search) {
                        $q->where('display_name', 'LIKE', '%' . $search . '%');
                    });
                });
        }
        if ($request->input('from_date')) {
            $orders->whereBetween('order_date', [$fromDate, $toDate]);
        }
        return $orders->paginate(12)->toArray();
    }

    /**
     * @param $request
     */
    public function save($request)
    {
        $order = $this->createOrder($request);
        $invoice = $this->createInvoice($order);
        if($request->input('order_mode') == 'Cash'){
            $this->createPayment($order, $invoice, $request);
        }
        $this->allocationItemUpdate($request);
    }

    /**
     * @param $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createOrder($request)
    {
        $salesOrder = $this->model->find($request->input('salesOrder'));
        if ($salesOrder) {
//            $this->setModel($salesOrder);
        }
        $items = $request->input('sales_items');
        $now = carbon();
        if (!$request->input('discount_type')) {
            $request->merge(['discount_type' => 'Amount']);
        }

        if (!$request->input('discount_rate')) {
            $request->merge(['discount_rate' => 0]);
        }
        if (!$salesOrder) {
            $request->merge(['ref' => $this->generateRef()]);
        } else {
            $request->merge(['ref' => $salesOrder->ref]);
        }

        $company = userCompany(auth()->user());

        //if (!showLocationDropdown()) {
        $location = userShopLocation();
        $allocation = getShopAllocation();
        if ($allocation) {
            $location = $allocation->salesLocation;
        }
        $request->merge(['sales_location_id' => $location ? $location->id : null]);
        //}

        $this->model->products()->detach();
        /** get listed products */
        $products = $this->mapProducts($company, $location, $request);
        $productAmounts = array_pluck($products, 'amount');
        $productAmount = array_sum($productAmounts);

        /** get given discount */
        $discount = 0;
        if ($request->input('discount_type') == 'Percentage') {
            if ($request->input('discount_type') > 0) {
                $discount = $productAmount * ($request->input('discount_rate') / 100);
            }
        } else {
            $discount = $request->input('discount_rate');
        }

        if (!$this->model->getAttribute('order_no')) {
            $this->model->setAttribute('order_no', $this->getCode());
        }

        $this->model->setAttribute('ref', $request->input('ref'));
        $this->model->setAttribute('sales_location_id', $request->input('sales_location_id'));
        $this->model->setAttribute('order_date', $now);
        $this->model->setAttribute('delivery_date', $now);
        $this->model->setAttribute('order_type', 'Direct');

        if($request->input('order_mode') == 'Cash'){
            $this->model->setAttribute('order_mode', 'Cash');
        }else{
            $this->model->setAttribute('order_mode', 'Customer');
            $this->model->setAttribute('customer_id', $request->input('customer'));
        }

        $this->model->setAttribute('sales_type', 'Retail');
        $this->model->setAttribute('sales_category', 'Shop');

        /** set subtotal, discount, adjustment and total */
        $this->model->setAttribute('sub_total', $productAmount);
        $this->model->setAttribute('discount_type', $request->input('discount_type'));
        $this->model->setAttribute('discount_rate', $request->input('discount_rate'));
        $this->model->setAttribute('discount', $discount);

        $adjustment = $request->input('adjustment') ? $request->input('adjustment') : 0;
        $this->model->setAttribute('adjustment', $adjustment);

        $totalAmount = ($productAmount - $discount) + ($adjustment);
        $this->model->setAttribute('total', $totalAmount);
        $this->model->setAttribute('status', 'Open');
        $this->model->setAttribute('prepared_by', auth()->id());
        $this->model->setAttribute('company_id', $company->id);
        $this->model->setAttribute('daily_sale_id', $this->getAllocationData()->id ?? null);
        $this->model->setAttribute('received_cash', $request->input('received'));
        $this->model->setAttribute('given_change', $request->input('change'));
        $this->model->save();

        /** attach products to order */
        $products = $this->mapProducts($company, $location, $request);
        $this->model->products()->attach($products);
        return $this->model;
    }

    /**
     * @param SalesOrder $order
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createInvoice(SalesOrder $order)
    {
        $this->setModel(new Invoice());
        $this->setCodePrefix('INV', 'invoice_no');
        $this->setRefPrefix('INV');
        $isEdit = false;
        if ($order->invoices->first()) {
            $this->setModel($order->invoices->first());
            $isEdit = true;
        }
        if (!$this->model->getAttribute('invoice_no')) {
            $this->model->setAttribute('invoice_no', $this->getCode());
        }
        $this->model->setAttribute('prepared_by', auth()->id());
        $this->model->setAttribute('invoice_date', $order->order_date);
        $this->model->setAttribute('due_date', $order->order_date);
        $this->model->setAttribute('amount', $order->total);
        $this->model->setAttribute('ref', $order->getAttribute('ref'));
        $this->model->setAttribute('sales_order_id', $order->getAttribute('id'));
        $this->model->setAttribute('customer_id', $order->getAttribute('customer_id'));
        $this->model->setAttribute('business_type_id', $order->getAttribute('business_type_id'));
        $this->model->setAttribute('company_id', $order->getAttribute('company_id'));
        $this->model->setAttribute('sales_location_id', $order->sales_location_id);
        $this->model->setAttribute('daily_sale_id', $this->getAllocationData()->id ?? null);
        $this->model->save();
        $invoice = $this->model;
        /** update order invoice status as per the invoice amount */
        /** @var SalesOrder $order */
        $order = $order->refresh();
        if ($order->getAttribute('total') == $order->invoices->sum('amount')) {
            $order->setAttribute('invoice_status', 'Invoiced');
        } else {
            $order->setAttribute('invoice_status', 'Partially Invoiced');
        }
        $order->save();

        $this->recordTransaction($invoice, $isEdit);
        return $invoice->refresh();
    }

    /**
     * @param SalesOrder $order
     * @param Invoice $invoice
     * @param $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createPayment(SalesOrder $order, invoice $invoice, $request)
    {
        $this->setModel(new InvoicePayment());
        if ($invoice->payments->first()) {
            $this->setModel($invoice->payments->first());
        }
        $this->model->setAttribute('prepared_by', auth()->id());
        $this->model->setAttribute('invoice_id', $invoice->getAttribute('id'));
        $this->model->setAttribute('sales_order_id', $invoice->getAttribute('sales_order_id'));
        $this->model->setAttribute('customer_id', $invoice->getAttribute('customer_id'));
        $this->model->setAttribute('business_type_id', $invoice->getAttribute('business_type_id'));
        $this->model->setAttribute('company_id', $invoice->getAttribute('company_id'));
        $this->model->setAttribute('sales_location_id', $invoice->sales_location_id);
        $this->model->setAttribute('payment_date', $invoice->invoice_date);
        $this->model->setAttribute('payment', $invoice->amount);
        $this->model->setAttribute('payment_mode', $request->input('payment_mode'));
        $this->model->setAttribute('payment_type', 'Final Payment');

        $this->model->setAttribute('cheque_no', $request->input('cheque_no'));
        $this->model->setAttribute('cheque_date', $request->input('cheque_date'));
        $this->model->setAttribute('deposited_date', $request->input('deposited_date'));
        $this->model->setAttribute('account_no', $request->input('account_no'));
        $this->model->setAttribute('bank_id', $request->input('cc_bank_id'));
        $this->model->setAttribute('card_holder_name', $request->input('card_holder_name'));
        $this->model->setAttribute('card_no', $request->input('card_no'));
        $this->model->setAttribute('expiry_date', $request->input('expiry_date'));

        $paymentMode = $request->input('payment_mode');

        if($paymentMode == 'Cash') {
            $depositedTo = Account::where('accountable_id', $order->sales_location_id)
                ->where('accountable_type', 'App\SalesLocation')
                ->where('account_type_id', 1)
                ->first();
            if($depositedTo){
                $this->model->setAttribute('deposited_to', $depositedTo->id);
            }else{
                $this->model->setAttribute('deposited_to', 1);
            }
        } else if ($paymentMode == 'Cheque') {
            $depositedTo = Account::where('accountable_id', $order->sales_location_id)
                ->where('accountable_type', 'App\SalesLocation')
                ->where('account_type_id', 19)
                ->first();
            if($depositedTo){
                $this->model->setAttribute('deposited_to', $depositedTo->id);
            }else{
                $this->model->setAttribute('deposited_to', 50);
            }
        } else {
            $this->model->setAttribute('deposited_to', 1);
        }

        if ($paymentMode == 'Cash') {
            $this->model->setAttribute('cheque_no', null);
            $this->model->setAttribute('cheque_date', null);
            $this->model->setAttribute('account_no', null);
            $this->model->setAttribute('deposited_date', null);
            $this->model->setAttribute('bank_id', null);
            $this->model->setAttribute('card_holder_name', null);
            $this->model->setAttribute('card_no', null);
            $this->model->setAttribute('expiry_date', null);
        } elseif ($paymentMode == 'Cheque') {
            $this->model->setAttribute('account_no', null);
            $this->model->setAttribute('deposited_date', null);
            $this->model->setAttribute('card_holder_name', null);
            $this->model->setAttribute('card_no', null);
            $this->model->setAttribute('expiry_date', null);
        } elseif ($paymentMode == 'Direct Deposit') {
            $this->model->setAttribute('cheque_no', null);
            $this->model->setAttribute('cheque_date', null);
            $this->model->setAttribute('card_holder_name', null);
            $this->model->setAttribute('card_no', null);
            $this->model->setAttribute('expiry_date', null);
        } elseif ($paymentMode == 'Credit Card') {
            $this->model->setAttribute('cheque_no', null);
            $this->model->setAttribute('cheque_date', null);
            $this->model->setAttribute('account_no', null);
            $this->model->setAttribute('deposited_date', null);
        }
        $this->model->setAttribute('daily_sale_id', $this->getAllocationData()->id ?? null);
        $this->model->save();
        $invoice = $invoice->refresh();
        /** update invoice status as per the payment */
        if ($invoice->getAttribute('amount') == $invoice->payments->sum('payment')) {
            $invoice->setAttribute('status', 'Paid');
        } else {
            $invoice->setAttribute('status', 'Partially Paid');
        }
        $invoice->save();

        if ($order && $order->total == $order->payments->sum('payment')) {
            $order->setAttribute('status', 'Closed');
            $order->setAttribute('delivery_status', 'Delivered');
            $order->save();
        }

        $this->recordPaymentTransaction($this->model);

        return $this->model->refresh();
    }

    /**
     * @param Invoice $invoice
     * @param bool $isEdit
     */
    protected function recordTransaction(Invoice $invoice, $isEdit = false)
    {
        $debitAccount = Account::find(3); // Account Receivable
        $creditAccount = Account::find(48); // Sales
        recordTransaction($invoice, $debitAccount, $creditAccount, [
            'date' => now()->toDateString(),
            'type' => 'Deposit',
            'amount' => $invoice->amount,
            'auto_narration' => 'Invoice #'.$invoice->ref.', '.number_format($invoice->amount).' created',
            'manual_narration' => 'Invoice #'.$invoice->ref.', '.number_format($invoice->amount),
            'tx_type_id' => 27,
            'customer_id' => $invoice->customer_id,
            'company_id' => $invoice->company_id,
        ], 'InvoiceCreation', $isEdit);
    }

    /**
     * @param InvoicePayment $payment
     * @param bool $isEdit
     */
    protected function recordPaymentTransaction(InvoicePayment $payment, $isEdit = false)
    {
        $debitAccount = Account::find($payment->deposited_to);
        $creditAccount = Account::find(3);
        recordTransaction($payment, $debitAccount, $creditAccount, [
            'date' => now()->toDateString(),
            'type' => 'Deposit',
            'amount' => $payment->payment,
            'auto_narration' => 'Payment '.number_format($payment->payment).' received and deposited to '.$payment->depositedTo->name,
            'manual_narration' => 'Payment '.number_format($payment->payment).' received and deposited to '.$payment->depositedTo->name,
            'tx_type_id' => 19,
            'customer_id' => $payment->customer_id,
            'company_id' => $payment->company_id,
        ], 'PaymentCreation', $isEdit);
    }

    /**
     * @param $company
     * @param $location
     * @param Request $request
     * @return array
     */
    public function mapProducts($company, $location, Request $request)
    {
        $sales_items = $request->input('sales_items');
        $mappedProducts = [];
        foreach ($sales_items as $key => $product) {
            if (!$product) continue;
            $unitType = null;
            $rate = $this->calculateProductRateShop($company, $location, $key);
            $amount = ($product['qty'] * $rate);
            $discount = 0;
            $totalAmount = $amount - $discount;
            $mappedProduct = [
                'sales_order_id' => $this->model->id ?? null,
                'product_id' => $product['id'] ?? null,
                'quantity' => $product['qty'] ?? null,
                'rate' => $rate,
                'discount' => $discount,
                'amount' => $totalAmount ?? null,
                'status' => 'Pending',
            ];
            array_push($mappedProducts, $mappedProduct);
        }
        return $mappedProducts;
    }

    /**
     * @param $product
     * @return int
     */
    public function calculateProductRate($product)
    {
        $product = Product::find($product);
        if (!$product) return 0;
        $rate = $product->retail_price;
        return $rate ? $rate : 0;
    }

    public function calculateProductRateShop($company, $location, $product)
    {
        $product = Product::find($product);
        if (!$product) return 0;
        $rate = getItemShopSellingPrice($company, $location, $product);
        return $rate ? $rate : 0;
    }

    /**
     * @param string $method
     * @param SalesOrder|null $order
     * @return array
     */
    public function breadcrumbs(string $method, SalesOrder $order = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Cash Sales'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Cash Sales', 'route' => 'cash.sales.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Cash Sales', 'route' => 'cash.sales.index'],
                ['text' => $order->code ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Cash Sales', 'route' => 'cash.sales.index'],
                ['text' => $order->code ?? ''],
                ['text' => 'Edit'],
            ],
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    /**
     * @return Collection|null
     */
    public function getAllocationData()
    {
        if (isShopLevelStaff() || isDirectorLevelStaff() || isShopManagerLevelStaff() || isCashierLevelStaff() || isAccountLevelStaff()) {
            $user = auth()->user();
            $allocation = getShopAllocation();
            return $allocation;
        }
        return null;
    }

    public function productForBarcode()
    {
        $allocation = $this->getAllocationData();
        $productIds = [];
        if ($allocation) {
            $productIds = $allocation->items->pluck('product_id')->toArray();
        }
        return Product::whereIn('id', $productIds)->where('barcode_number', \request()->input('barcode'))->get(['id', 'name', 'barcode_number'])->first();
    }

    /**
     * @param string $ids
     * @param null $q
     * @return array
     */
    public function searchProducts($ids = '', $q = null)
    {
        if (!json_decode($ids) && !$q && !is_array(json_decode($ids))) {
            $q = $ids;
            $ids = [];
        }
        $model = $this->getAllocationData();
        $productIds = [];
        if ($model && $model->count()) {
            $productIds = $model->items->whereNotIn('product_id', $ids)->pluck('product_id')->toArray();
        }
        $this->setModel(new Product);
        if (!$q) {
            $data = $this->model->whereIn('id', $productIds)->get(['id', 'name'])->toArray();
        } else {
            $data = $this->model->whereIn('id', $productIds)
                ->where(function ($query) use ($q) {
                    $query->where('name', 'LIKE', '%' . $q . '%');
                })
                ->get(['id', 'name'])
                ->toArray();
        }
        // mapping the data
        $data = array_map(function ($obj) {
            return ["name" => $obj['name'], "value" => $obj['id']];
        }, $data);
        return ["success" => true, "results" => $data];
    }

    /**
     * @param $request
     */
    public function allocationItemUpdate($request)
    {
        $allocation = $this->getAllocationData();
        if (!$allocation) {
            return;
        }

        $salesItems = $request->input('sales_items');
        foreach ($salesItems as $key => $salesItem) {
            $item = $allocation->items->where('product_id', $key)->first();
            $item->sold_qty = ($item->sold_qty + array_get($salesItem, 'qty'));
            $item->save();
        }
    }

    /**
     * @param SalesOrder $order
     */
    public function cancel(SalesOrder $order)
    {
        $order->status = 'Canceled';
        $order->save();
        $invoices = $order->invoices;
        foreach ($invoices as $invoice) {
            $invoice->status = 'Canceled';
            $invoice->save();

            /**
             * BEGIN
             * update transaction record when entire invoice cancel
             *  Transaction Type - Order Cancel
             *      DR - Sales
             *      CR - Account Receivable
             */
            $debitAccount = Account::find(48); // Sales
            $creditAccount = Account::find(3); // Account Receivable
            recordTransaction($invoice, $debitAccount, $creditAccount, [
                'date' => now()->toDateString(),
                'type' => 'Deposit',
                'amount' => $invoice->amount,
                'auto_narration' => 'Invoice amount of '.number_format($invoice->amount).' is canceled ('.
                    $invoice->ref.')',
                'manual_narration' => 'Invoice amount of '.number_format($invoice->amount).' is canceled ('.
                    $invoice->ref.')',
                'tx_type_id' => 15,
                'customer_id' => $invoice->customer_id,
                'company_id' => $invoice->company_id,
            ], 'InvoiceCancel', false);
        }
        $payments = $order->payments;
        foreach ($payments as $payment) {
            $payment->status = 'Canceled';
            $payment->save();

            /** remove payment related transaction */
            $transaction = $payment->transaction;
            $transaction->records()->delete();
            $transaction->delete();
            /** end */
        }

        $allocation = $this->getAllocationData();
        if (!$allocation) {
            return;
        }

        foreach ($order->products as $product) {
            $item = $allocation->items->where('product_id', $product->id)->first();
            $item->sold_qty = ($item->sold_qty - $product->pivot->quantity);
            $item->save();
        }
    }

    public function getHandOverData()
    {
        $data = [];
        $allocation = $this->getAllocationData();
        if (!$allocation) {
            return ['error' => 'Allocation NotFound'];
        }

        $orders = $allocation->orders;
        $invoices = $allocation->invoices;
        $payments = $allocation->payments()->with('bank')->get();
        $data['payment_collection'] = $this->handover->getPaymentsCollectedAmounts($payments)->toArray();
        $data['total_collection'] = $this->handover->getPaymentsCollectedAmounts($payments)->sum();
        $data['payments'] = $this->handover->paymentGroupByPaymentMode($payments)->toArray();
        $data['allowance'] = $allocation->allowance;
        $data['allocation'] = $allocation->load('salesHandover');
        return $data;
    }

    /**
     * @return array|\Illuminate\Database\Eloquent\Model
     */
    public function saveHandOverData()
    {
        $allocation = $this->getAllocationData();
        if (!$allocation) {
            return ['error' => 'Allocation NotFound'];
        }

        $orders = $allocation->orders;
        if (!$orders->count()) {
            return [];
        }

        $invoices = $allocation->invoices;
        $payments = $allocation->payments;
        $todayCollection = $this->handover->getPaymentsCollectedAmounts($payments);
        $todayPayments = $this->handover->paymentGroupByPaymentMode($payments);
        $noOfChequeCollected = $todayPayments->get('cheque')->count();
        $this->setModel(new SalesHandover());
        $this->setCodePrefix('SHO', 'code');
        $this->model->setAttribute('code', $this->getCode());
        $this->model->setAttribute('date', now()->toDateString());
        $this->model->setAttribute('daily_sale_id', $allocation ? $allocation->id : null);

        $this->model->setAttribute('sales', $todayCollection->sum());
        $this->model->setAttribute('cash_sales', $todayCollection->get('cash'));
        $this->model->setAttribute('cheque_sales', $todayCollection->get('cheque'));
        $this->model->setAttribute('deposit_sales', $todayCollection->get('direct_deposit'));
        $this->model->setAttribute('card_sales', $todayCollection->get('card_sales'));
        $this->model->setAttribute('credit_sales', 0.00);

        $allowance = $allocation->allowance ?? null;
        $this->model->setAttribute('total_collect', $todayCollection->sum());
        $this->model->setAttribute('cheques_count', $noOfChequeCollected);
//        $this->model->setAttribute('total_expense', $this->calculateTotalExpense($request));
        $this->model->setAttribute('allowance', $allowance);
        $this->model->setAttribute('rep_id', null);
        $this->model->setAttribute('prepared_by', auth()->id());
        $this->model->setAttribute('company_id', $company->id ?? null);
        $this->model->save();
        return $this->model->refresh();
    }
}
