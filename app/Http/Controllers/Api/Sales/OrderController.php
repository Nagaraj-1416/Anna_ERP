<?php

namespace App\Http\Controllers\Api\Sales;


use App\Customer;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Sales\OrderStoreRequest;
use App\Http\Requests\Api\Sales\ReasonRequest;
use App\Http\Resources\SalesOrderResource;
use App\Repositories\Sales\OrderRepository;
use App\SalesOrder;
use Illuminate\Http\Request;

class OrderController extends ApiController
{
    /**
     * @var OrderRepository
     */
    protected $order;

    /**
     * OrderController constructor.
     * @param OrderRepository $order
     */
    public function __construct(OrderRepository $order)
    {
        $this->order = $order;
    }

    /**
     * list the related order items
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $orders = SalesOrder::where('prepared_by', auth()->id())->with('customer')->get();
        return SalesOrderResource::collection($orders);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function todayIndex()
    {
        $orders = $this->order->todayIndex();
        return SalesOrderResource::collection($orders);
    }

    /**
     * store new order item
     * @param OrderStoreRequest $request
     * @return SalesOrderResource
     */
    public function store(OrderStoreRequest $request)
    {
        $order = $this->order->save($request, true);
        $order->load('products', 'customer');
        return new SalesOrderResource($order);
    }

    /**
     * return the details of a order
     * @param SalesOrder $order
     * @return SalesOrderResource
     */
    public function show(SalesOrder $order)
    {
        $order->load('products', 'invoices', 'payments.bank', 'customer.addresses', 'company.addresses');
        if ($order->customer) {
            $outstanding = array_get(cusOutstanding($order->customer), 'balance');
            $order->setAttribute('customer_outstanding', $outstanding ? number_format($outstanding, 2) : 0.00);
        }
        return new SalesOrderResource($order);
    }

    /**
     * Update existing sales order
     * @param SalesOrder $order
     * @param OrderStoreRequest $request
     * @return SalesOrderResource
     */
    public function update(SalesOrder $order, OrderStoreRequest $request)
    {
        $this->order->setModel($order);
        $order = $this->order->update($request, true);
        $order->load('products', 'customer');
        return new SalesOrderResource($order);
    }

    /**
     * Update the order status
     * @param SalesOrder $order
     * @param Request $request
     * @return SalesOrderResource
     */
    public function updateStatus(SalesOrder $order, Request $request)
    {
        $request->validate([
            'status' => 'required|in:"Scheduled", "Draft", "Awaiting Approval", "Open", "Closed", "Canceled"',
        ]);
        $this->order->setModel($order);
        $order = $this->order->updateStatus($request);
        $order->load('products', 'customer');
        return new SalesOrderResource($order);
    }

    /**
     * Delete existing sales order
     * @param SalesOrder $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(SalesOrder $order)
    {
        $results = $this->order->delete($order);
        return response()->json($results);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function salesProducts(){
        $results = $this->order->salesProducts();
        return response()->json(['data' => $results]);
    }

    /**
     * @param ReasonRequest $request
     * @param SalesOrder $order
     * @return SalesOrderResource
     */
    public function cancel(ReasonRequest $request, SalesOrder $order){
        $request->merge(['cancel_notes_order' => $request->input('reason')]);
        $order = $this->order->cancelOrder($order, $request);
        $order->load('products', 'customer');
        return new SalesOrderResource($order);
    }

    public function isPrinted(SalesOrder $order, Request $request)
    {
        $request->validate(['is_order_printed' => 'required|in:"Yes","No"']);
        return new SalesOrderResource($this->order->isPrinted($order, $request));
    }
}
