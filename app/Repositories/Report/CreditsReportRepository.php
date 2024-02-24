<?php

namespace App\Repositories\Report;

use App\Repositories\BaseRepository;
use App\SalesOrder;
use Illuminate\Http\Request;

/**
 * Class CreditsReportRepository
 * @package App\Repositories\Report
 */
class CreditsReportRepository extends BaseRepository
{
    public function creditByRoute()
    {
        $request = request();
        $orders = $this->getOrders($request);
        $orders = $orders->with(['invoices', 'customer', 'payments', 'salesRep', 'route', 'location', 'company'])->get();
        return $this->reportGroupByRoute($orders);
    }

    public function reportGroupByRoute($orders)
    {
        return $orders->groupBy('company_id')->map(function ($items, $key) {

            $ordersByRoute = $this->mapRouteOrders($items);

            $item = $items->first();
            return $this->sumOrderSummery($ordersByRoute, [
                'summary' => $item->company->name ?? 'OPENING ORDERS',
                'related_id' => $item->company_id,
                'related_column' => 'company_id',
                'class' => 'table-green',
                'children' => $ordersByRoute,
                'query' => [
                    'company_id' => $item->company_id,
                ],
            ]);
        });
    }

    protected function mapRouteOrders($orders)
    {
        return $orders->groupBy('route_id')->map(function ($items, $key) {
            $item = $items->first();
            return $this->getMapOrderSummary($items, [
                'summary' => $item->route->name ?? 'OPENING ORDERS',
                'related_id' => $item->route_id,
                'class' => 'table-info',
                'related_column' => 'route_id',
                'query' => [
                    'route_id' => $item->route_id,
                    'company_id' => $item->company_id,
                ],
            ]);
        });
    }

    public function creditByRep()
    {
        $request = request();
        $orders = $this->getOrders($request);
        $orders = $orders->with(['invoices', 'customer', 'payments', 'salesRep', 'route', 'location', 'company'])->get();
        return $this->reportGroupByRep($orders);
    }

    public function reportGroupByRep($orders)
    {
        return $orders->groupBy('company_id')->map(function ($items, $key) {

            $ordersByRep = $this->mapRepOrders($items);

            $item = $items->first();
            return $this->sumOrderSummery($ordersByRep, [
                'summary' => $item->company->name ?? 'OPENING ORDERS',
                'related_id' => $item->company_id,
                'related_column' => 'company_id',
                'class' => 'table-green',
                'children' => $ordersByRep,
                'query' => [
                    'company_id' => $item->company_id,
                ],
            ]);
        });
    }

    protected function mapRepOrders($orders)
    {
        return $orders->groupBy('rep_id')->map(function ($items, $key) {
            $item = $items->first();
            return $this->getMapOrderSummary($items, [
                'summary' => $item->salesRep->name ?? 'OPENING ORDERS',
                'related_id' => $item->rep_id,
                'class' => 'table-info',
                'related_column' => 'rep_id',
                'query' => [
                    'rep_id' => $item->rep_id,
                    'company_id' => $item->company_id,
                ],
            ]);
        });
    }

    public function creditByCustomer()
    {
        $request = request();
        $orders = $this->getOrders($request);
        $orders = $orders->with(['invoices', 'customer', 'payments', 'salesRep', 'route', 'location', 'company'])->get();
        return $this->reportGroupByCustomer($orders);
    }

    public function reportGroupByCustomer($orders)
    {
        return $orders->groupBy('company_id')->map(function ($items, $key) {

            $ordersByCustomer = $this->mapCustomerOrders($items);

            $item = $items->first();
            return $this->sumOrderSummery($ordersByCustomer, [
                'summary' => $item->company->name ?? 'Not Available',
                'related_id' => $item->company_id,
                'related_column' => 'company_id',
                'class' => 'table-green',
                'children' => $ordersByCustomer,
                'query' => [
                    'company_id' => $item->company_id,
                ],
            ]);
        });
    }

    protected function mapCustomerOrders($orders)
    {
        return $orders->groupBy('customer_id')->map(function ($items, $key) {
            $item = $items->first();
            return $this->getMapOrderSummary($items, [
                'summary' => $item->customer->display_name ?? 'Not Available',
                'related_id' => $item->customer_id,
                'class' => 'table-info',
                'related_column' => 'customer_id',
                'query' => [
                    'customer_id' => $item->customer_id,
                    'company_id' => $item->company_id,
                ],
            ]);
        });
    }

    protected function sumOrderSummery($orders, $otherDate = [])
    {
        $data = [
            'total_sales' => $orders->sum('total_sales'),
            'total_invoiced' => $orders->sum('total_invoiced'),
            'total_paid' => $orders->sum('total_paid'),
            'total_cash' => $orders->sum('total_cash'),
            'total_cheque' => $orders->sum('total_cheque'),
            'total_deposit' => $orders->sum('total_deposit'),
            'total_card' => $orders->sum('total_card'),
            'total_balance' => $orders->sum('total_balance'),
        ];
        return array_merge($data, $otherDate);
    }

    protected function getMapOrderSummary($orders, $otherDate = [])
    {
        $totalSales = $orders->sum('total');
        $PaymentItems = $orders->pluck('payments')->collapse();
        $invoiceItems = $orders->pluck('invoices')->collapse();
        $totalPaid = $PaymentItems->where('status', 'Paid')->sum('payment');
        $data = [
            'total_sales' => (float)$totalSales,
            'total_invoiced' => (float)$invoiceItems->sum('amount'),
            'total_paid' => (float)$totalPaid,
            'total_cash' => (float)$PaymentItems->where('payment_mode', 'Cash')->where('status', 'Paid')->sum('payment'),
            'total_cheque' => (float)$PaymentItems->where('payment_mode', 'Cheque')->where('status', 'Paid')->sum('payment'),
            'total_deposit' => (float)$PaymentItems->where('payment_mode', 'Direct Deposit')->where('status', 'Paid')->sum('payment'),
            'total_card' => (float)$PaymentItems->where('payment_mode', 'Credit Card')->where('status', 'Paid')->sum('payment'),
            'total_balance' => (float)$totalSales - $totalPaid,
        ];
        return array_merge($data, $otherDate);
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getOrders($request)
    {
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('date') ?? carbon()->toDateString();
        $company = $request->input('company_id');

        $orders = SalesOrder::whereIn('status', ['Open', 'Closed'])
            ->where('is_credit_sales', 'Yes')
            ->where('order_date', '<=', $toDate);

        if ($company) {
            $orders->where('company_id', $company);
        }
        return $orders;
    }

    public function salesList(Request $request)
    {
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('date') ?? carbon()->toDateString();

        $orders = SalesOrder::whereIn('status', ['Open', 'Closed'])
            ->where('is_credit_sales', 'Yes')
            ->where('order_date', '<=', $toDate);

        $filterColumns = ['sales_category', 'location_id', 'customer_id', 'route_id', 'rep_id', 'company_id'];

        foreach ($request->all() as $key => $value) {
            if (in_array($key, $filterColumns)) {
                $value = $value ? $value : null;
                $orders = $orders->where($key, $value);
            }
        }

        return $orders->with('customer')->get()->map(function ($item) {
            $totalSales = $item->total;
            $PaymentItems = $item->payments;
            $totalPaid = $PaymentItems->where('status', 'Paid')->sum('payment');
            return [
                'customer_name' => $item->customer->display_name ?? 'None',
                'customer_id' => $item->customer_id,
                'ref' => $item->ref,
                'id' => $item->id,
                'status' => $item->status,
                'is_credit_sales' => $item->is_credit_sales == 'No' ? 'Cash' : 'Credit',
                'order_date' => carbon($item->created_at)->toDayDateTimeString(),
                'total_sales' => (float)$totalSales,
                'total_paid' => (float)$totalPaid,
                'total_cash' => (float)$PaymentItems->where('payment_mode', 'Cash')->where('status', 'Paid')->sum('payment'),
                'total_cheque' => (float)$PaymentItems->where('payment_mode', 'Cheque')->where('status', 'Paid')->sum('payment'),
                'total_deposit' => (float)$PaymentItems->where('payment_mode', 'Direct Deposit')->where('status', 'Paid')->sum('payment'),
                'total_card' => (float)$PaymentItems->where('payment_mode', 'Credit Card')->where('status', 'Paid')->sum('payment'),
                'total_balance' => (float)$totalSales - $totalPaid,
            ];
        });
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function soldItemsList(Request $request)
    {
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();

        $orders = SalesOrder::whereIn('status', ['Open', 'Closed'])
            ->where('is_credit_sales', 'Yes')
            ->where('order_date', '<=', $toDate);

        $filterColumns = ['sales_category', 'location_id', 'customer_id', 'route_id', 'rep_id', 'company_id'];
        foreach ($request->all() as $key => $value) {
            if (in_array($key, $filterColumns)) {
                $value = $value ? $value : null;
                $orders = $orders->where($key, $value);
            }
        }
        $products = $orders->with('products.stocks')->get()->pluck('products')->collapse();
        $products = $products->groupBy('id');
        return $products->map(function ($items) {
            $item = $items->first();
            $stock = $item->stocks->first();
            return [
                'id' => $item->id,
                'quantity' => $items->sum('pivot.quantity'),
                'amount' => $items->sum('pivot.amount'),
                'name' => $item->name,
                'available_stock' => $stock ? $stock->available_stock : 0
            ];
        });
    }

}
