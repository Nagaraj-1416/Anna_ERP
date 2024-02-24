<?php

namespace App\Http\Controllers\Purchase;

use App\Bill;
use App\Brand;
use App\Http\Controllers\Controller;
use App\Http\Requests\Purchase\BillStoreRequest;
use App\Http\Requests\Purchase\CancelRequest;
use App\Http\Requests\Purchase\OrderStoreRequest;
use App\Http\Requests\Purchase\PaymentStoreRequest;
use App\PurchaseOrder;
use App\PurchaseRequest;
use App\PurchaseRequestItem;
use App\Repositories\Purchase\BillRepository;
use App\Repositories\Purchase\OrderRepository;
use App\Repositories\Purchase\PaymentRepository;
use App\Repositories\Purchase\RequestRepository;
use App\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PDF;

class OrderController extends Controller
{
    /**
     * @var OrderRepository
     */
    protected $order;

    /**
     * @var BillRepository
     */
    protected $bill;

    /**
     * @var PaymentRepository
     */
    protected $payment;

    protected $poRequest;

    /**
     * OrderController constructor.
     * @param OrderRepository $order
     * @param BillRepository $bill
     * @param PaymentRepository $payment
     * @param RequestRepository $poRequest
     */
    public function __construct(OrderRepository $order,
                                BillRepository $bill,
                                PaymentRepository $payment, RequestRepository $poRequest)
    {
        $this->order = $order;
        $this->bill = $bill;
        $this->payment = $payment;
        $this->poRequest = $poRequest;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', $this->order->getModel());
        $breadcrumb = $this->order->breadcrumbs('index');
        if (\request()->ajax()) {
            $orders = $this->order->getOrders();
            return response()->json($orders);
        }
        return view('purchases.order.index', compact('breadcrumb'));
    }

    public function requests()
    {
        $breadcrumb = $this->poRequest->breadcrumbs('requests');
        if (\request()->ajax()) {
            $orders = $this->order->getRequests();
            return response()->json($orders);
        }
        return view('purchases.order.request.index', compact('breadcrumb'));
    }

    public function confirmRequest(PurchaseRequest $purchaseRequest)
    {
        $breadcrumb = $this->order->breadcrumbs('request-confirm');
        $company = $purchaseRequest->company;
        $companyAddress = $company->addresses()->first();
        $items = $purchaseRequest->items()->where('status', 'Pending')->get();
        return view('purchases.order.request.confirm', compact('breadcrumb',
            'company', 'companyAddress', 'items', 'purchaseRequest'));
    }

    public function doConfirmRequest(PurchaseRequest $purchaseRequest)
    {
        $request = request();
        $request->validate([
            'supplier_id' => 'required'
        ]);

        $items = $request->input('items');
        $itemIds = array_get($items, 'product_id');

        /** update selected items */
        if ($itemIds) {
            foreach ($itemIds as $key => $itemVal) {
                $poItem = $purchaseRequest->items()
                    ->where('product_id', $key)
                    ->where('purchase_request_id', $purchaseRequest->id)->first();
                $poItem->status = 'Confirmed';
                $poItem->save();
            }
        }

        $purchaseRequest->setAttribute('supplier_id', $request->input('supplier_id'));

        if($purchaseRequest->getAttribute('request_for') == 'Store'){
            $unitSupplier = Supplier::where('id', $request->input('supplier_id'))
                ->where('supplierable_type', 'App\ProductionUnit')
                ->first();
            if($unitSupplier){
                $purchaseRequest->setAttribute('supply_from', 'PUnit');
                $purchaseRequest->setAttribute('production_unit_id', $unitSupplier->supplierable_id);
            }
            $storeSupplier = Supplier::where('id', $request->input('supplier_id'))
                ->where('supplierable_type', 'App\Store')
                ->first();
            if($storeSupplier){
                $purchaseRequest->setAttribute('supply_from', 'Store');
                $purchaseRequest->setAttribute('supply_store_id', $storeSupplier->supplierable_id);
            }
        }

        $purchaseRequest->setAttribute('request_mode', $request->input('po_mode'));

        $pendingCounts = $purchaseRequest->items()->where('status', 'Pending')->count();
        if($pendingCounts == 0){
            $purchaseRequest->setAttribute('status', 'Completed');
        }else{
            $purchaseRequest->setAttribute('status', 'Drafted');
        }
        $purchaseRequest->save();

        /** generate Purchase Order */
        $confirmedItems = PurchaseRequestItem::whereIn('product_id', array_keys($itemIds, 0))
            ->where('status', 'Confirmed')
            ->where('purchase_request_id', $purchaseRequest->id)
            ->get();

        $store = $purchaseRequest->store;

        $this->order->generatePOFromRequest($purchaseRequest, $confirmedItems, $store);

        alert()->success('Purchase request confirmed successfully!', 'Success')->persistent();
        return redirect()->route('purchase.order.index');
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function dataTableData(Request $request)
    {
        $this->authorize('index', $this->order->getModel());
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
        $breadcrumb = $this->order->breadcrumbs('create');
        $brand = Brand::first();
        return view('purchases.order.create', compact('breadcrumb', 'brand'));
    }

    /**
     * @param OrderStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(OrderStoreRequest $request)
    {
        $this->authorize('store', $this->order->getModel());
        $order = $this->order->save($request);
        alert()->success('Order drafted successfully, please confirm and approve the order details to generate bill!', 'Success')->persistent();
        return redirect()->route('purchase.order.show', [$order]);
    }

    /**
     * @param PurchaseOrder $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function confirm(PurchaseOrder $order)
    {
        $this->authorize('confirm', $order);
        $company = $order->company;
        $companyAddress = $company->addresses()->first();
        $supplier = $order->supplier;
        $address = $supplier->addresses()->first();
        $items = $order->products;
        $bills = $order->bills;
        $payments = $order->payments;

        $breadcrumb = $this->order->breadcrumbs('confirm', $order);
        return view('purchases.order.wizard.confirm', compact('breadcrumb', 'order', 'company', 'companyAddress',
            'address', 'items', 'bills', 'supplier', 'payments'));
    }

    /**
     * @param PurchaseOrder $order
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function doConfirm(PurchaseOrder $order)
    {
        $this->authorize('confirm', $order);
        $order->setAttribute('status', 'Open');
        $order->save();
        alert()->success('Order confirmed & approved successfully, please generate bill to record payments!', 'Success')->persistent();
        return redirect()->route('purchase.order.bill', [$order]);
    }

    /**
     * @param PurchaseOrder $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function bill(PurchaseOrder $order)
    {
        $this->authorize('generateBill', $order);
        $breadcrumb = $this->order->breadcrumbs('bill', $order);
        $company = $order->company;
        $companyAddress = $company->addresses()->first();
        $supplier = $order->supplier;
        $address = $supplier->addresses()->first();
        $items = $order->products;
        $bills = $order->bills;
        $payments = $order->payments;
        $orderAmount = $order->total;
        $billedAmount = $bills->sum('amount');
        $pendingAmount = $orderAmount - $billedAmount;
        return view('purchases.order.wizard.bill',
            compact('breadcrumb', 'order', 'supplier', 'address', 'items', 'company', 'companyAddress',
                'bills', 'payments', 'pendingAmount'));
    }

    /**
     * @param BillStoreRequest $request
     * @param PurchaseOrder $order
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function generateBill(BillStoreRequest $request, PurchaseOrder $order)
    {
        $this->authorize('generateBill', $order);
        $bill = $this->bill->save($request, $order);
        alert()->success('Bill generated successfully, you can now record the received payments!', 'Success')->persistent();
        return redirect()->route('purchase.order.payment', [$order, $bill]);
    }

    /**
     * @param PurchaseOrder $order
     * @param Bill $bill
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function payment(PurchaseOrder $order, Bill $bill)
    {
        $this->authorize('recordPayment', $order);
        $breadcrumb = $this->order->breadcrumbs('payment', $order);
        return view('purchases.order.wizard.payment', compact('breadcrumb', 'order', 'bill'));
    }

    /**
     * @param PaymentStoreRequest $request
     * @param PurchaseOrder $order
     * @param Bill $bill
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function recordPayment(PaymentStoreRequest $request, PurchaseOrder $order, Bill $bill)
    {
        $this->authorize('recordPayment', $order);
        $this->payment->save($request, $bill);
        alert()->success('Payment recorded successfully, please visit respective order/bill to view more information!', 'Success')->persistent();
        return redirect()->route('purchase.bill.show', [$bill]);
    }

    /**
     * @param PurchaseOrder $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(PurchaseOrder $order)
    {
        $this->authorize('show', $order);
        $breadcrumb = $this->order->breadcrumbs('show', $order);
        $company = $order->company;
        $companyAddress = $company->addresses()->first();
        $supplier = $order->supplier;
        $address = $supplier ? $supplier->addresses()->first() : '';
        $items = $order->products()->wherePivot('status', 'Pending')->get();
        $bills = $order->bills;
        $payments = $order->payments;
        $grns = $order->grns;
        $totalAmount = $order->total;
        $billedAmount = $bills->sum('amount');
        $pendingAmount = $totalAmount - $billedAmount;
        return view('purchases.order.show',
            compact('breadcrumb', 'order', 'company', 'companyAddress', 'supplier', 'address', 'items',
                'bills', 'payments', 'pendingAmount', 'grns'));
    }

    /**
     * @param PurchaseOrder $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(PurchaseOrder $order)
    {
        $this->authorize('edit', $order);
        $order->load('supplier', 'products');
        if ($order->supplier) {
            $order->setAttribute('supplier_name', $order->supplier->display_name);
        }
        $order->setAttribute('product_items', $this->order->productItems($order));
        $breadcrumb = $this->order->breadcrumbs('edit', $order);
        return view('purchases.order.edit', compact('breadcrumb', 'order'));
    }

    /**
     * @param OrderStoreRequest $request
     * @param PurchaseOrder $order
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(OrderStoreRequest $request, PurchaseOrder $order)
    {
        $this->authorize('update', $order);
        $this->order->setModel($order);
        $this->order->update($request);
        alert()->success('Purchase order updated successfully', 'Success')->persistent();
        return redirect()->route('purchase.order.show', [$order]);
    }

    /**
     * @param PurchaseOrder $order
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(PurchaseOrder $order): JsonResponse
    {
        $this->authorize('delete', $order);
        $response = $this->order->delete($order);
        return response()->json($response);
    }

    /**
     * @param PurchaseOrder $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function clone(PurchaseOrder $order)
    {
        $this->authorize('clone', $order);
        $order->load('businessType', 'supplier', 'products');
        if ($order->businessType) {
            $order->setAttribute('business_type_name', $order->businessType->name);
        }
        if ($order->supplier) {
            $order->setAttribute('supplier_name', $order->supplier->display_name);
        }
        $order->setAttribute('product_items', $this->order->productItems($order));
        $breadcrumb = $this->order->breadcrumbs('clone', $order);
        return view('purchases.order.clone', compact('breadcrumb', 'order', 'productItems'));
    }

    /**
     * @param OrderStoreRequest $request
     * @param PurchaseOrder $order
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function copy(OrderStoreRequest $request, PurchaseOrder $order)
    {
        $this->authorize('clone', $order);
        $this->order->save($request);
        alert()->success('Order copied successfully', 'Success')->persistent();
        return redirect()->route('purchase.order.index');
    }

    /**
     * @param PurchaseOrder $order
     * @return JsonResponse
     * @throws \Exception
     */
    public function approve(PurchaseOrder $order): JsonResponse
    {
        $this->authorize('approve', $order);
        $response = $this->order->approve($order);
        return response()->json($response);
    }

    /**
     * @param PurchaseOrder $order
     * @return JsonResponse
     * @throws \Exception
     */
    public function convert(PurchaseOrder $order): JsonResponse
    {
        $this->authorize('convert', $order);
        $response = $this->order->convert($order);
        return response()->json($response);
    }

    /**
     * @param PurchaseOrder $order
     * @param string $type
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function export(PurchaseOrder $order, $type = 'PDF')
    {
        $this->authorize('export', $order);
        if ($type == 'PDF') {
            $this->pdfExport($order);
        }
    }

    /**
     * @param PurchaseOrder $order
     * @return mixed
     */
    public function pdfExport(PurchaseOrder $order)
    {
        $company = $order->company;
        $companyAddress = $company->addresses()->first();
        $supplier = $order->supplier;
        $address = $supplier->addresses()->first();
        $items = $order->products;
        $bills = $order->bills;
        $payments = $order->payments;

        $data = [];
        $data['order'] = $order;
        $data['company'] = $company;
        $data['companyAddress'] = $companyAddress;
        $data['supplier'] = $supplier;
        $data['address'] = $address;
        $data['items'] = $items;
        $data['bills'] = $bills;
        $data['payments'] = $payments;

        $pdf = PDF::loadView('purchases.order.export', $data);
        return $pdf->download($order->po_no . '.pdf');
    }

    /**
     * @param PurchaseOrder $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function printView(PurchaseOrder $order)
    {
        $this->authorize('print', $order);
        $company = $order->company;
        $companyAddress = $company->addresses()->first();
        $supplier = $order->supplier;
        $address = $supplier->addresses()->first();
        $items = $order->products;
        $bills = $order->bills;
        $payments = $order->payments;

        $breadcrumb = $this->order->breadcrumbs('print', $order);
        return view('purchases.order.print', compact('breadcrumb', 'order', 'company', 'companyAddress',
            'address', 'items', 'bills', 'supplier', 'payments'));
    }

    /**
     * @param PurchaseOrder $order
     * @param CancelRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelOrder(PurchaseOrder $order, CancelRequest $request)
    {
        $this->order->cancelOrder($order, $request);
        alert()->success('Order canceled successfully', 'Success')->persistent();
        return redirect()->route('purchase.order.show', [$order]);
    }
}
