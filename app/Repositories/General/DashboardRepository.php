<?php

namespace App\Repositories\General;

use App\Allowance;
use App\Http\Requests\General\AllowanceRequest;
use App\Http\Resources\RouteResource;
use App\Invoice;
use App\InvoicePayment;
use App\Repositories\BaseRepository;
use App\Route;
use App\SalesOrder;
use Doctrine\DBAL\Schema\SQLServerSchemaManager;

/**
 * Class DashboardRepository
 * @package App\Repositories\General
 */
class DashboardRepository extends BaseRepository
{
    /**
     * DashboardRepository constructor.
     */
    public function __construct()
    {

    }

    public function apiIndex()
    {
        $date = carbon()->now();
        $user = auth()->user();
        /** @var Allowance $allocationQuery */
        $allocationQuery = getRepAllocation($date->toDateString(), $date->toDateString(), $user);
        $allocation = $allocationQuery->first();

        $customers = getAllAllocatedCustomers($allocationQuery, $user, false);
        $customerIds = $customers->pluck('id')->toArray();
        $routeIds = $allocationQuery->pluck('route_id')->toArray();
        $routes = Route::whereIn('id', $routeIds)->with(['customers' => function($q) use($customerIds){
            $q->whereIn('id', $customerIds);
        }])->get();
        // mapping routes data
        $routes = $routes->map(function ($item) use($allocation){
            $item->customers =  $item->customers->map(function ($item) use($allocation){
                $outstanding = array_get(cusOutstanding($item), 'balance');
                $item->outstanding = $outstanding ? $outstanding : 0.00;
                $orders = $this->customerOrdersByDate($allocation->from_date, $allocation->to_date , $item);
                $item->no_of_order = $orders->count();
                $item->total_sales = $orders->sum('total');

                $invoices = $this->customerInvoicesByDate($allocation->from_date, $allocation->to_date, $orders, $item);
                $item->no_of_invoice = $invoices->count();
                $item->total_invoiced = $invoices->sum('amount');

                $payments = $this->customerPaymentsByDate($allocation->from_date, $allocation->to_date, $invoices, $item);
                $item->received_payment = $payments->sum('payment');
                $reminding = $orders->sum('total') - $payments->sum('payment');
                $item->payment_reminding = $reminding;
                $oldSales = oldCollectionByCustomer($item);
                $item->old_sales = $oldSales['totalPaid'];
                return $item;
            });
            return $item;
        });

        if (!$routes->count()) return [];
        return RouteResource::collection($routes);
    }

    public function customerOrdersByDate($from, $to, $customer){
        return SalesOrder::where('customer_id', $customer->id)->whereBetween('order_date', [$from, $to])->get();
    }

    public function customerInvoicesByDate($from, $to, $orders, $customer)
    {
        $ordersId = $orders->pluck('id')->toArray();
        return Invoice::whereIn('sales_order_id', $ordersId)->whereHas('order', function ($query) use ($customer, $from, $to){
            $query->where('customer_id', $customer->id)->whereBetween('order_date', [$from, $to]);
        })->where('customer_id', $customer->id)->whereBetween('invoice_date', [$from, $to])->get();
    }

    public function customerPaymentsByDate($from, $to, $invoices, $customer){
        $invoicesId = $invoices->pluck('id')->toArray();
        return InvoicePayment::whereIn('invoice_id', $invoicesId)->whereHas('invoice', function ($query) use ($customer, $from, $to){
            $query->whereHas('order', function ($query) use ($customer, $from, $to){
                $query->where('customer_id', $customer->id)->whereBetween('order_date', [$from, $to]);;
            })->where('customer_id', $customer->id);;
        })->where('status', 'Paid')->where('customer_id', $customer->id)
            ->whereBetween('payment_date', [$from, $to])->get();
    }
}
