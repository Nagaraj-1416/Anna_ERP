<?php

namespace App\Http\Controllers\API\General;


use App\DailySalesOdoReading;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\UserResource;
use App\Invoice;
use App\Repositories\Sales\InvoiceRepository;
use App\Repositories\Sales\OrderRepository;
use App\SalesOrder;

/**
 * Class GeneralController
 * @package App\Http\Controllers\API\General
 */
class GeneralController extends ApiController
{
    protected $order;
    protected $invoice;

    public function __construct(OrderRepository $order, InvoiceRepository $invoice)
    {
        $this->order = $order;
        $this->invoice = $invoice;
    }

    public function mata()
    {
        //$lastSalesOrder = SalesOrder::withTrashed()->orderBy('id', 'desc')->first();
        //$lastInvoice = Invoice::withTrashed()->orderBy('id', 'desc')->first();
        //$repClAmount = $this->getRep()->cl_amount ?? 0;
        $repClAmount = 0;
        //$repTotalAmount = $this->getRep()->totalAmount ?? 0;
        $repTotalAmount = 0;
        //$repCl = $repClAmount - $repTotalAmount;
        $repCl = 0;
        //$routeCl = ($this->getRoute()->cl_amount ?? 0) - ($this->getRoute()->totalAmount ?? 0);
        $routeCl = 0;
        $allocation = getRepAllocation(null, null, auth()->user())->first();

        //dd($allocation);

        //$allocationVachle = $allocation->vehicle;
        /*$dailySalesOdoReading = DailySalesOdoReading::where('vehicle_id', $allocationVachle->id ?? null)
            ->where('daily_sale_id', '<>', $allocation->id)
            ->get()->last();*/
        return response()->json(['data' => [
            //'last_order_ref' => $lastSalesOrder ? $lastSalesOrder->ref : null,
            'last_order_ref' => null,
            //'last_invoice_ref' => $lastInvoice ? $lastInvoice->ref : null,
            'last_invoice_ref' => null,
            'next_order_ref' => $this->order->generateRef(),
            'next_invoice_ref' => $this->invoice->generateRef(),
            'rep_cl' => max($repCl, 0),
            //'rep_total_cl' => $this->getRep()->cl_amount ?? 0,
            'rep_total_cl' => 0,
            'route_cl' => max($routeCl, 0),
            //'route_total_cl' => $this->getRoute()->cl_amount ?? 0,
            'route_total_cl' => 0,
            //'start_odo_meter_reading' => $dailySalesOdoReading->ends_at ?? 0,
            'start_odo_meter_reading' => 0,
            'allocation' => [
                'id' => $allocation ? $allocation->id : null,
                'from_date' => $allocation ? $allocation->from_date : null,
                'to_date' => $allocation ? $allocation->to_date : null,
            ]
        ]]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function date()
    {
        return response()->json(['data' => [
            'date' => carbon()->now()->toDateString(),
        ]]);
    }

    /**
     * @return null
     */
    private function getRep()
    {
        $user = auth()->user();
        if (!$user) return null;
        if (!$user->staffs()->first()) return null;
        $rep = $user->staffs()->first()->rep;
        $rep->totalAmount = ordersOutStanding($rep->salesOrders);
        return $rep;
    }

    /**
     * @return null
     */
    private function getRoute()
    {
        $allocation = getRepAllocation();
        if (!$allocation->first()) return null;
        if (!$allocation->first()->route) return null;
        $route = $allocation->first()->route()->with('customers')->first();
        $customers = $route->customers()->with('orders')->get();
        $route->totalAmount = ordersOutStanding($customers->pluck('orders')->collapse());
        return $route;
    }
}
