<?php

namespace App\Http\Controllers\Sales;

use App\Customer;
use App\Estimate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\CancelRequest;
use App\Http\Requests\Sales\InvoiceStoreRequest;
use App\Http\Requests\Sales\OrderStoreRequest;
use App\Http\Requests\Sales\PaymentStoreRequest;
use App\Invoice;
use App\Jobs\StockUpdateJob;
use App\Repositories\Sales\EstimateRepository;
use App\Repositories\Sales\InquiryRepository;
use App\Repositories\Sales\InvoiceRepository;
use App\Repositories\Sales\PaymentRepository;
use App\SalesInquiry;
use App\SalesOrder;
use App\Repositories\Sales\OrderRepository;
use App\Stock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use PDF;

class OrderController extends Controller
{
    /**
     * @var OrderRepository
     */
    protected $order;

    /**
     * @var InquiryRepository
     */
    protected $inquiry;

    /**
     * @var
     */
    protected $invoice;

    /**
     * @var
     */
    protected $payment;

    protected $estimate;

    /**
     * OrderController constructor.
     * @param OrderRepository $order
     * @param InquiryRepository $inquiry
     * @param InvoiceRepository $invoice
     * @param PaymentRepository $payment
     * @param EstimateRepository $estimate
     */
    public function __construct(OrderRepository $order, InquiryRepository $inquiry, InvoiceRepository $invoice, PaymentRepository $payment, EstimateRepository $estimate)
    {
        $this->order = $order;
        $this->inquiry = $inquiry;
        $this->invoice = $invoice;
        $this->payment = $payment;
        $this->estimate = $estimate;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', $this->order->getModel());
        $breadcrumb = $this->order->breadcrumbs('index');
        $orders = $this->order->getOrders();
        if (\request()->ajax()) {
            return response()->json($orders);
        }
        return view('sales.order.index', compact('breadcrumb'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function dataTableData(Request $request)
    {
        if (\request()->ajax()) {
            return $this->order->dataTable($request);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->order->getModel());
        $inquiry = null;
        $breadcrumb = $this->order->breadcrumbs('create');
        if (\request()->input('inquiry')) {
            /** @var SalesInquiry $inquiry */
            $inquiry = SalesInquiry::find(request()->input('inquiry'));
            if ($inquiry) {
                $inquiry->load('products');
                $inquiry->setAttribute('product_items', $this->inquiry->productItems($inquiry));
                $inquiry->setAttribute('customer_name', $inquiry->customer->display_name ?? '');
                $inquiry->setAttribute('business_type_name', $inquiry->businessType->name ?? '');
                $inquiry->setAttribute('sales_type', 'Retail');
            }
        }

        if (\request()->input('estimation')) {
            $estimate = Estimate::find(request()->input('estimation'));
            if ($estimate) {
                $estimate->load('products');
                $estimate->setAttribute('product_items', $this->estimate->productItems($estimate));
                $estimate->setAttribute('customer_name', $estimate->customer->display_name ?? '');
                $estimate->setAttribute('business_type_name', $estimate->businessType->name ?? '');
                $estimate->setAttribute('sales_type', 'Retail');
            }
        }

        $customer = request()->input(['phoneOrder'], null);
        $customer = Customer::find($customer);

        return view('sales.order.create', compact('breadcrumb', 'inquiry', 'estimate', 'customer'));
    }

    /**
     * @param OrderStoreRequest $request
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(OrderStoreRequest $request)
    {
        $this->authorize('store', $this->order->getModel());
        $order = $this->order->save($request);

        if($order->status == 'Draft'){
            alert()->success('Order details drafted successfully', 'Success')->persistent();
            return redirect()->route('sales.order.show', [$order]);
        }else{
            alert()->success('Order details added successfully, please confirm the order details to generate invoice!', 'Success')->persistent();
            return redirect()->route('sales.order.confirm', [$order]);
        }
    }

    /**
     * @param SalesOrder $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function confirm(SalesOrder $order)
    {
        $this->authorize('confirm', $order);

        $company = $order->company;
        $companyAddress = $company->addresses()->first();
        $customer = $order->customer;
        $address = $customer->addresses()->first();
        $items = $order->products;
        $invoices = $order->invoices;
        $payments = $order->payments;

        $breadcrumb = $this->order->breadcrumbs('confirm', $order);
        return view('sales.order.wizard.confirm', compact('breadcrumb', 'order', 'company', 'companyAddress',
            'address', 'items', 'invoices', 'customer', 'payments'));
    }

    /**
     * @param SalesOrder $order
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function doConfirm(SalesOrder $order)
    {
        $this->authorize('confirm', $order);
        $order->setAttribute('status', 'Open');
        $order->save();

        /** get order items */
        $items = $order->products;

        /** release stock from selected store */
        $this->stockUpdate('Out', $items, $order);

        alert()->success('Order confirmed successfully, please generate invoice to record payments!', 'Success')->persistent();
        return redirect()->route('sales.order.invoice', [$order]);
    }

    /**
     * @param $type
     * @param $items
     * @param $transable
     */
    public function stockUpdate($type, $items, $transable)
    {
        $data = [];
        foreach ($items as $item) {
            $data[$item->id] = [];
            $productId = $item->pivot->product_id;
            $storeId = $item->pivot->store_id;
            $quantity = $item->pivot->quantity;
            $stock = Stock::where('product_id', $productId)->where('store_id', $storeId)->first();
            $data[$item->id]['quantity'] = $quantity;
            $data[$item->id]['stock'] = $stock;
            $data[$item->id]['transable'] = $transable;
        }
        dispatch(new StockUpdateJob($type, $data));
    }

    /**
     * @param SalesOrder $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function invoice(SalesOrder $order)
    {
        $this->authorize('generateInvoice', $order);
        $breadcrumb = $this->order->breadcrumbs('invoice', $order);
        $company = $order->company;
        $companyAddress = $company->addresses()->first();
        $customer = $order->customer;
        $address = $customer->addresses()->first();
        $items = $order->products;
        $invoices = $order->invoices;
        $payments = $order->payments;
        $orderAmount = $order->total;
        $invoicedAmount = $invoices->sum('amount');
        $pendingOrderAmount = $orderAmount - $invoicedAmount;
        return view('sales.order.wizard.invoice',
            compact('breadcrumb', 'order', 'customer', 'address', 'items', 'company', 'companyAddress',
                'invoices', 'payments', 'pendingOrderAmount'));
    }

    /**
     * @param InvoiceStoreRequest $request
     * @param SalesOrder $order
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function generateInvoice(InvoiceStoreRequest $request, SalesOrder $order)
    {
        $this->authorize('generateInvoice', $order);
        $invoice = $this->invoice->save($request, $order);
        alert()->success('Invoice generated successfully, you can now record the received payments!', 'Success')->persistent();
        return redirect()->route('sales.order.payment', [$order, $invoice]);
    }

    /**
     * @param SalesOrder $order
     * @param Invoice $invoice
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function payment(SalesOrder $order, Invoice $invoice)
    {
        $this->authorize('recordPayment', $order);
        $breadcrumb = $this->order->breadcrumbs('payment', $order);
        return view('sales.order.wizard.payment', compact('breadcrumb', 'order', 'invoice'));
    }

    /**
     * @param PaymentStoreRequest $request
     * @param SalesOrder $order
     * @param Invoice $invoice
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function recordPayment(PaymentStoreRequest $request, SalesOrder $order, Invoice $invoice)
    {
        $this->authorize('recordPayment', $order);
        $this->payment->save($request, $invoice);
        alert()->success('Payment recorded successfully, please visit respective order/invoice to view more information!', 'Success')->persistent();
        return redirect()->route('sales.invoice.show', [$invoice]);
    }

    /**
     * @param SalesOrder $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(SalesOrder $order)
    {
        $this->authorize('show', $order);
        $breadcrumb = $this->order->breadcrumbs('show', $order);
        $company = $order->company;
        $companyAddress = $company->addresses()->first();
        $customer = $order->customer ?? new Customer();
        $address = $customer->count() ? $customer->addresses()->first() : collect([]);
        $items = $order->products;
        $invoices = $order->invoices;
        $payments = $order->payments;
        $orderAmount = $order->total;
        $invoicedAmount = $invoices->sum('amount');
        $pendingOrderAmount = $orderAmount - $invoicedAmount;
        if (\request()->ajax()) return response()->json(['orders' => $order->toArray(), 'payments' => $order->payments()->with('bank')->get()->first()]);
        return view('sales.order.show',
            compact('breadcrumb', 'order', 'customer', 'address', 'items', 'company', 'companyAddress',
                'invoices', 'payments', 'pendingOrderAmount'));
    }

    /**
     * @param SalesOrder $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(SalesOrder $order)
    {
        $this->authorize('edit', $order);
        $order->load('businessType', 'customer');
        if ($order->businessType) {
            $order->setAttribute('business_type_name', $order->businessType->name);
        }
        if ($order->customer) {
            $order->setAttribute('customer_name', $order->customer->display_name);
        }
        if ($order->salesRep) {
            $order->setAttribute('rep_name', $order->salesRep->name);
        }
        if ($order->priceBook) {
            $order->setAttribute('price_book_name', $order->priceBook->name);
        }
        if ($order->salesLocation) {
            $order->setAttribute('sales_location_name', $order->salesLocation->name);
        }
        $order->setAttribute('product_items', $this->order->productItems($order));
        $breadcrumb = $this->order->breadcrumbs('edit', $order);
        return view('sales.order.edit', compact('breadcrumb', 'order', 'productItems'));
    }

    /**
     * @param OrderStoreRequest $request
     * @param SalesOrder $order
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(OrderStoreRequest $request, SalesOrder $order)
    {
        $this->authorize('update', $order);
        $this->order->setModel($order);
        $this->order->update($request);

        /** get order items */
        $items = $order->products;

        /** release stock from selected store */
        $this->stockUpdate('Out', $items, $order);

        alert()->success('Sales order updated successfully', 'Success')->persistent();
        return redirect()->route('sales.order.show', [$order]);
    }

    /**
     * @param SalesOrder $order
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(SalesOrder $order): JsonResponse
    {
        $this->authorize('delete', $order);
        $response = $this->order->delete($order);
        return response()->json($response);
    }

    /**
     * @param SalesOrder $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function clone(SalesOrder $order)
    {
        $this->authorize('clone', $order);
        $order->load('businessType', 'customer');
        if ($order->businessType) {
            $order->setAttribute('business_type_name', $order->businessType->name);
        }
        if ($order->customer) {
            $order->setAttribute('customer_name', $order->customer->display_name);
        }
        if ($order->salesRep) {
            $order->setAttribute('rep_name', $order->salesRep->name);
        }
        if ($order->priceBook) {
            $order->setAttribute('price_book_name', $order->priceBook->name);
        }
        $order->setAttribute('product_items', $this->order->productItems($order));
        $breadcrumb = $this->order->breadcrumbs('clone', $order);
        return view('sales.order.clone', compact('breadcrumb', 'order', 'productItems'));
    }

    /**
     * @param OrderStoreRequest $request
     * @return RedirectResponse
     */
    public function copy(OrderStoreRequest $request)
    {
        $order = $this->order->save($request);
        alert()->success('Sales order cloned successfully', 'Success')->persistent();
        return redirect()->route('sales.order.show', [$order]);
    }

    /**
     * @param SalesOrder $order
     * @param string $type
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function export(SalesOrder $order, $type = 'PDF')
    {
        $this->authorize('export', $order);
        if ($type == 'PDF') {
            $this->pdfExport($order);
        }
    }

    /**
     * @param $order
     * @return mixed
     */
    public function pdfExport($order)
    {
        $company = $order->company;
        $companyAddress = $company->addresses()->first();
        $customer = $order->customer;
        $address = $customer->addresses()->first();
        $items = $order->products;
        $invoices = $order->invoices;
        $payments = $order->payments;

        $data = [];
        $data['order'] = $order;
        $data['company'] = $company;
        $data['companyAddress'] = $companyAddress;
        $data['customer'] = $customer;
        $data['address'] = $address;
        $data['items'] = $items;
        $data['invoices'] = $invoices;
        $data['payments'] = $payments;

        $pdf = PDF::loadView('sales.order.export', $data);
        return $pdf->download(env('APP_NAME') . ' - Sales Order (' . $order->order_no . ')' . '.pdf');
    }

    /**
     * @param SalesOrder $order
     * @return JsonResponse
     * @throws \Exception
     */
    public function approve(SalesOrder $order): JsonResponse
    {
        $response = $this->order->approve($order);
        return response()->json($response);
    }

    /**
     * @param SalesOrder $order
     * @return JsonResponse
     * @throws \Exception
     */
    public function convert(SalesOrder $order): JsonResponse
    {
        $response = $this->order->convert($order);
        return response()->json($response);
    }

    /**
     * @param SalesOrder $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function printView(SalesOrder $order)
    {
        $this->authorize('print', $order);
        $company = $order->company;
        $companyAddress = $company->addresses()->first();
        $customer = $order->customer;
        $address = $customer->addresses()->first();
        $items = $order->products;
        $invoices = $order->invoices;
        $payments = $order->payments;

        $breadcrumb = $this->order->breadcrumbs('print', $order);
        return view('sales.order.print', compact('breadcrumb', 'order', 'company', 'companyAddress',
            'address', 'items', 'invoices', 'customer', 'payments'));
    }

    /**
     * @param SalesOrder $order
     * @param CancelRequest $request
     * @return RedirectResponse
     */
    public function cancelOrder(SalesOrder $order, CancelRequest $request)
    {
        $this->order->cancelOrder($order, $request);
        alert()->success('Order canceled successfully', 'Success')->persistent();
        return redirect()->route('sales.order.show', [$order]);
    }

    public function allowPrint($orderId)
    {
        $order = SalesOrder::where('id', $orderId)->first();
        $order->setAttribute('is_order_printed', 'No');
        $order->save();
        return $order->refresh();
    }

    public function updateToCredit($orderId)
    {
        $order = SalesOrder::where('id', $orderId)->first();
        $order->setAttribute('is_credit_sales', 'Yes');
        $order->setAttribute('status', 'Open');
        $order->save();

        /** update invoice status to Open if Paid */
        $invoice = Invoice::where('sales_order_id', $orderId)->first();
        $invoice->status = 'Open';
        $invoice->save();

        return $order->refresh();
    }

    public function creditOrders()
    {
        $breadcrumb = $this->order->breadcrumbs('credit-orders');
        if (request()->ajax()) {
            $data = $this->order->creditOrders();
            return response()->json($data);
        }
        return view('sales.order.credit-orders', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function creditOrdersExport()
    {
        $data = $this->order->creditOrders();
        $pdf = PDF::loadView('sales.order.export.credit-orders', $data);
        return $pdf->download(env('APP_NAME') . ' - Credit Orders.pdf');
    }

}
