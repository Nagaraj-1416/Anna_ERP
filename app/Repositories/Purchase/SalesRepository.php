<?php

namespace App\Repositories\Purchase;

use App\Customer;
use App\Invoice;
use App\InvoicePayment;
use App\Product;
use App\Rep;
use App\Repositories\BaseRepository;
use App\SalesOrder;
use Illuminate\Support\Facades\DB;

/**
 * Class SalesRepository
 * @package App\Repositories\Purchase
 */
class SalesRepository extends BaseRepository
{
    /**
     * @param $model
     * @param null $take
     * @param null $with
     * @param null $where
     * @param null $field
     * @return array
     */
    public function summary($model, $take = null, $with = null, $where = null, $field = null)
    {
        $model = app('App\\' . $model);
        $data = [];
        if ($take && $take != 'null') {
            $modelData = $model->where('is_active', 'Yes')->orderBy('id', 'desc')->take($take);
            $modelData = $modelData->get();
            $data['model'] = $modelData;
        }
        if ($where && $field) {
            $model = $model->where($field, $where);
        }
        $modelCount = $model->count();
        $data['count'] = $modelCount;
        return $data;
    }

    /**
     * @return array
     */
    public function orderSummary()
    {
        $data = [];
        $salesOrders = SalesOrder::all();
        $totalSales = $salesOrders->sum('total');
        $totalClosed = $salesOrders->where('status', 'Closed')->sum('total');
        $totalDelivered = $salesOrders->where('status', 'Closed')
            ->where('delivery_status', 'Delivered')->sum('total');
        $data['totalSales'] = number_format($totalSales, 2);
        $data['totalClosed'] = number_format($totalClosed, 2);
        $data['totalDelivered'] = number_format($totalDelivered, 2);
        return $data;
    }

    /**
     * @return array
     */
    public function invoiceSummary()
    {
        $data = [];

        $invoices = Invoice::all();
        $totalInvoiced = $invoices->sum('amount');
        $totalPaid = $invoices->where('status', 'Paid')->sum('amount');

        $data['totalInvoiced'] = number_format($totalInvoiced, 2);
        $data['totalPaid'] = number_format($totalPaid, 2);

        return $data;
    }

    /**
     * @return mixed
     */
    public function settlementDue()
    {
        return Invoice::where('due_date', '<', carbon())
            ->orderBy('due_date', 'asc')
            ->take(10)
            ->with('customer')
            ->get();
    }

    public function topCustomers($take = null)
    {
        $orders = DB::table('sales_orders')
            ->select('customer_id', DB::raw('SUM(total) as total_sales'))
            ->groupBy('customer_id')->orderBy('total_sales', 'DESC')
            ->take($take ?? 10)->get()->pluck('customer_id')->toArray();

        $customers = Customer::whereIn('id', $orders)->with('orders')->get();
        $customers = $customers->transform(function (Customer $customer) {
            $customer->setAttribute('total_amount', $customer->orders->sum('total'));
            return $customer;
        });
        $customers = $customers->sortByDesc('total_amount')->toArray();
        return array_values($customers);
    }

    /**
     * @return array
     */
    public function topProduct()
    {
        // To avoid querying deleted.
        $productIds = Product::pluck('id');

        $productsWithAmount = DB::table('product_sales_order')
            ->whereIn('product_id', $productIds)
            ->select('product_id', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('product_id')
            ->orderByDesc('total_amount')
            ->take(10)
            ->get()
            ->keyBy('product_id');

        $products = Product::whereIn('id', $productsWithAmount->pluck('product_id'))->get();

        return $products->transform(function ($product) use ($productsWithAmount) {
            $product->total_amount = $productsWithAmount[$product->id]->total_amount;
            return $product;
        })
            ->sortByDesc('total_amount')
            ->values()
            ->toArray();

//        $products = Product::has('salesOrders')->with(['salesOrders'])->get();
//        $products = $products->transform(function ($product) {
//            $product->total_amount = $product->salesOrders->sum('pivot.amount');
//            return $product;
//        });
//        $products = $products->sortByDesc('total_amount')->take(10)->toArray();
//        return array_values($products);
    }

    /**
     * @return array
     */
    public function topSalesRep()
    {
        $reps = Rep::has('salesOrders')->get();
        $reps = $reps->transform(function (Rep $rep) {
            $rep->setAttribute('total_amount', $rep->salesOrders()->get()->sum('total'));
            $rep->setAttribute('target', $rep->targets()->get()->sum('target'));
            return $rep;
        })->sortByDesc('total_amount')->take(10)->toArray();
        return array_values($reps);
    }

    /**
     * @return array
     */

    public function yearChart()
    {
        $payments = InvoicePayment::all();
        $payments = $payments->map(function (InvoicePayment $payment) {
            $payment->year = carbon($payment->payment_date)->year;
            return $payment;
        })->sortBy('year')->groupBy('year');
        $data = [];
        $data['datas'] = [];
        foreach ($payments as $key => $payment) {
            $amount = $payment->sum('payment');
            array_push($data['datas'], $amount);
        }
        $data['keys'] = array_keys($payments->toArray());
        return $data;
    }

    /**
     * @return array
     */
    public function monthChart()
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
        $thisMonth = carbon()->month;
        $returnMont = [];
        for ($start = 1; $start <= $thisMonth; $start++) {
            $returnMont[$start] = $months[$start - 1];
        }

        $payments = InvoicePayment::all();
        $payments = $payments->transform(function (InvoicePayment $payment) use ($returnMont) {
            $currentYear = carbon($payment->payment_date)->isCurrentYear();
            if (!$currentYear) return null;
            $payment->month = carbon($payment->payment_date)->month;
            return $payment;
        })->filter();
        $data = [];
        $data['datas'] = [];
        $data['keys'] = [];
        foreach ($returnMont as $key => $value) {
            $paymentsData = $payments->where('month', $key);
            $amount = $paymentsData->sum('payment');
            array_push($data['datas'], $amount);
            array_push($data['keys'], $value);
        }
        return $data;
    }

    /**00
     * @param null $take
     * @return array
     */

    public function topCustomersByPayment($take = null)
    {
        $companyCustomerIds = Customer::whereIn('company_id', userCompanyIds(loggedUser()))->pluck('id');

        $customerIdsAndPayments = DB::table((new InvoicePayment())->getTable())
            ->select(
                'customer_id',
                DB::raw('SUM(payment) as total_sales')
            )
            ->groupBy('customer_id')->orderBy('total_sales', 'DESC')
            ->take($take ?? 10)
            ->whereIn('customer_id', $companyCustomerIds)
            ->get()->keyBy('customer_id');

        $customerIds = $customerIdsAndPayments->pluck('customer_id');

        // Using DB rather than Customer model to avoid fetching append fields
        // Need to refactor
        $customers = DB::table((new Customer())->getTable())
            ->whereNull('deleted_at')
            ->whereIn('id', $customerIds)->get();

        $orderCountsByCustomerId = DB::table((new SalesOrder())->getTable())
            ->whereIn('customer_id', $customerIds)
            ->select('customer_id', DB::raw('COUNT(id) as order_count'))
            ->groupBy('customer_id')
            ->get()
            ->keyBy('customer_id');

        $customers->transform(function ($customer) use ($customerIdsAndPayments, $orderCountsByCustomerId) {
            $customer->total_amount = $customerIdsAndPayments[$customer->id]->total_sales;
            $customer->total_orders = $orderCountsByCustomerId[$customer->id]->order_count;

            return $customer;
        });

        return $customers->sortByDesc('total_amount')->values()->toArray();
    }


    public function topRepsByPayment($take = null)
    {
        $reps = Rep::whereIn('company_id', userCompanyIds(loggedUser()))
            ->has('salesOrders.payments')->get();
        $reps = $reps->transform(function (Rep $rep) {
            $rep->setAttribute('total_amount', $rep->salesOrders()->with('payments')->get()
                ->pluck('payments')->collapse()
                ->sum('payment'));
            $rep->setAttribute('total_orders', $rep->salesOrders->count());
            $rep->setAttribute('staff_id', $rep->staff->id);
            return $rep;
        })->sortByDesc('total_amount')->take(10)->toArray();
        return array_values($reps);
    }
}