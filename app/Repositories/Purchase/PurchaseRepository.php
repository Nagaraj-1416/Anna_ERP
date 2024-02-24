<?php

namespace App\Repositories\Purchase;

use App\Bill;
use App\BillPayment;
use App\Product;
use App\PurchaseOrder;
use App\Repositories\BaseRepository;
use App\Supplier;

class PurchaseRepository extends BaseRepository
{
    /**
     * @param $model
     * @param null $take
     * @param null $with
     * @param null $where
     * @param null $field
     * @return array
     */
    public function index($model, $take = null, $with = null, $where = null, $field = null): array
    {
        $model = app('App\\' . $model);
        $data = [];
        $modelData = collect([]);
        if ($take && $take != 'null') {
            $modelData = $model->where('is_active', 'Yes')->orderBy('created_at', 'desc')->take($take);
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
     * @param null $status
     * @return array
     */
    public function getOrderCounts($status = null)
    {
        $data = [];
        if ($status == 'Delivery Due') {
            $order = $this->getOrderByDeliveryDue();
            $data['data'] = $order;
            $order = $order->count();
        } else if ($status == 'Delivered') {
            $order = PurchaseOrder::where('status', 'Closed')->where('delivery_status', $status)->count();
        } else if ($status) {
            $order = PurchaseOrder::where('status', $status)->count();
        } else {
            $order = PurchaseOrder::all()->count();
        }

        $data['count'] = $order;
        return $data;
    }

    /**
     * @return mixed
     */
    public function getOrderByDeliveryDue()
    {
        $orders = PurchaseOrder::whereNotIn('status', ['Canceled'])
            ->whereIn('delivery_status', ['Pending', 'Partially Delivered'])
            ->orderBy('delivery_date', 'asc')
            ->with('supplier')->take(10)->get();
        $orders = $orders->map(function ($order) {
            $order->total = number_format($order->total, 2);
            return $order;
        });
        return $orders;
    }

    /**
     * @return mixed
     */
    public function getBills()
    {
        $bills = Bill::whereNotIn('status', ['Paid', 'Canceled'])
            ->orderBy('due_date', 'asc')
            ->with(['supplier', 'order'])->take(10)->get();
        $bills = $bills->map(function ($order) {
            $order->amount = number_format($order->amount, 2);
            return $order;
        });
        return $bills;
    }

    /**
     * @return array
     */
    public function getTopFiveProducts()
    {
        $products = Product::has('purchaseOrders')->with(['purchaseOrders'])->get();
        $products = $products->transform(function ($product) {
            $product->total_amount = $product->purchaseOrders->sum('pivot.amount');
            return $product;
        });
        $products = $products->sortByDesc('total_amount')->take(10)->toArray();
        return array_values($products);
    }

    /**
     * @return array
     */
    public function getTopFiveSupplier()
    {
        $suppliers = Supplier::has('orders')->get();
        $suppliers = $suppliers->transform(function ($supplier) {
            $supplier->total_amount = $supplier->orders()->get()->sum('total');
            return $supplier;
        })->sortByDesc('total_amount')->take(10)->toArray();
        return array_values($suppliers);
    }

    /**
     * @return array
     */
    public function yearChart()
    {
        $payments = BillPayment::all();
        $payments = $payments->map(function (BillPayment $payment) {
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

        $payments = BillPayment::all();
        $payments = $payments->transform(function (BillPayment $payment) use ($returnMont) {
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
}