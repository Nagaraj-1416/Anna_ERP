<?php

namespace App\Repositories\Report;

use App\Bill;
use App\BillPayment;
use App\PurchaseOrder;
use App\Repositories\BaseRepository;
use App\Supplier;
use App\SupplierCredit;

/**
 * Class PurchaseReportRepository
 * @package App\Repositories\Report
 */
class PurchaseReportRepository extends BaseRepository
{
    /**
     * @return array
     */
    public function poBySup()
    {
        $request = request();
        $supplier = $request->input('supplier');
        $orders = $this->getPo($request);
        if ($supplier) {
            $orders->where('supplier_id', $supplier);
        }
        $data = [];
        $orders = $orders->with(['bills', 'supplier', 'payments'])->get();
        $bills = $orders->pluck('bills')->collapse();
        $payments = $orders->pluck('payments')->collapse();
        $supplierId = array_keys($orders->groupBy('supplier_id')->toArray());
        $supplier = Supplier::whereIn('id', $supplierId)->get(['id', 'display_name']);

        $data['orders'] = $orders->groupBy('supplier_id');
        $data['order_total'] = $orders->sum('total');
        $data['bill_total'] = $bills->sum('amount');
        $data['payment_total'] = $payments->sum('payment');
        $data['balance'] = $orders->sum('total') - $payments->sum('payment');
        $data['supplier'] = $supplier;
        $data['request'] = $request->toArray();
        return $data;
    }

    /**
     * @return array
     */
    public function poByPro()
    {
        $request = request();
        $productID = $request->input('product');
        $orders = $this->getPo($request);
        if ($productID) {
            $orders->where(function ($q) use ($productID) {
                $q->whereHas('products', function ($product) use ($productID) {
                    $product->where('id', $productID);
                });
            });
        }

        $data = [];
        $orders = $orders->with(['bills', 'supplier', 'payments'])->get();
        $bills = $orders->pluck('bills')->collapse();
        $payments = $orders->pluck('payments')->collapse();
        $data['orders'] = $orders;
        $data['order_total'] = $orders->sum('total');
        $data['bill_total'] = $bills->sum('amount');
        $data['payment_total'] = $payments->sum('payment');
        $data['balance'] = $orders->sum('total') - $payments->sum('payment');
        $data['request'] = $request->toArray();

        return $data;
    }

    /**
     * @return array
     */
    public function poByProCat()
    {
        $request = request();
        $categoryID = $request->input('category');
        $orders = $this->getPo($request);
        if ($categoryID) {
            $orders->where(function ($q) use ($categoryID) {
                $q->whereHas('products', function ($product) use ($categoryID) {
                    $product->where('category_id', $categoryID);
                });
            });
        }

        $data = [];
        $orders = $orders->with(['bills', 'supplier', 'payments', 'products'])->get();
        $bills = $orders->pluck('bills')->collapse();
        $payments = $orders->pluck('payments')->collapse();
        $data['orders'] = $orders;
        $data['order_total'] = $orders->sum('total');
        $data['bill_total'] = $bills->sum('amount');
        $data['payment_total'] = $payments->sum('payment');
        $data['balance'] = $orders->sum('total') - $payments->sum('payment');
        $data['request'] = $request->toArray();

        return $data;
    }

    /**
     * @return array
     */
    public function monthlyPos()
    {
        $request = request();
        // Get From To data from Request
        $year = $request->input('year') ?? carbon()->format('Y');
        $fromMonth = $request->input('fromMonth') ?? carbon()->format('M');
        $toMonth = $request->input('toMonth') ?? carbon()->format('M');

        $months = ['Jan' => 1, 'Feb' => 2, 'Mar' => 3, 'Apr' => 4, 'May' => 5, 'Jun' => 6, 'Jul' => 7, 'Aug' => 8, 'Sept' => 9, 'Oct' => 10, 'Nov' => 11, 'Dec' => 12];
        // Create a Dates
        $fromDate = carbon()->year((int)$year)->month((int)array_get($months, $fromMonth))->startOfMonth();
        $toDate = carbon()->year((int)$year)->month(((int)array_get($months, $toMonth)))->endOfMonth();
        //Merge Request for get orders
        $request->merge(['fromDate' => $fromDate->copy()->toDateString()]);
        $request->merge(['toDate' => $toDate->copy()->toDateString()]);

        //Get Orders
        $orders = $this->getPo($request)->get();

        //Check Difference
        $difference = $fromDate->diffInMonths($toDate);

        //Get All Dates
        $dates = [];
        for ($start = 0; $start <= $difference; $start++) {
            $date = $fromDate->copy()->addMonth($start)->format('M Y');
            array_push($dates, $date);
        }
        $supplierGrouped = $orders->groupBy('supplier_id');
        $data = [];
        $data['dates'] = $dates;
        $data['data'] = [];
        $data['supplier'] = [];
        $orders = $supplierGrouped->transform(function ($orders, $index) use (&$data, $dates) {
            $orders = $orders->transform(function (PurchaseOrder $order) {
                $order->group = carbon($order->order_date)->format('M Y');
                return $order;
            })->groupBy('group')->toArray();
            $supplier = Supplier::find($index);
            $data['data'][$index] = [];
            $data['supplier'][$index] = [];
            $data['supplier'][$index] = [
                'id' => $supplier->id ?? '', 'display_name' => $supplier->display_name ?? ''
            ];

            foreach ($dates as $key => $value) {
                $order = array_get($orders, $value);
                if ($order) {
                    $amounts = array_pluck($order, 'total');
                    $data['data'][$index][$value] = array_sum($amounts);
                } else {
                    $data['data'][$index][$value] = 0;
                }
            }
        });
        $data['request'] = $request->toArray();
        //Get Data
        return $data;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getPo($request)
    {
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $businessType = $request->input('businessType');
        $orders = PurchaseOrder::whereBetween('order_date', [$fromDate, $toDate]);
        if ($businessType) {
            $orders->where('business_type_id', $businessType);
        }
        return $orders;
    }

    /**
     * @return array
     */
    public function paysMade()
    {
        $request = request();
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $businessType = $request->input('businessType');
        $paymentType = $request->input('paymentType');
        $paymentMode = $request->input('paymentMode');

        $payments = BillPayment::whereBetween('payment_date', [$fromDate, $toDate]);

        if ($businessType) {
            $payments->where('business_type_id', $businessType);
        }
        if ($paymentType) {
            $payments->where('payment_type', $paymentType);
        }

        if ($paymentMode) {
            $payments->where('payment_mode', $paymentMode);
        }

        $data = [];
        $payments = $payments->with(['bill', 'supplier', 'paidThrough'])->get();
        $supplierId = array_keys($payments->groupBy('supplier_id')->toArray());
        $supplier = Supplier::whereIn('id', $supplierId)->get(['id', 'display_name']);

        $data['payments'] = $payments->groupBy('supplier_id');
        $data['payments_total'] = $payments->sum('payment');
        $data['supplier'] = $supplier->toArray();
        $data['request'] = $request->toArray();

        return $data;
    }

    /**
     * @return array
     */
    public function creditDetails()
    {
        $request = request();
        $supplier = $request->input('supplier');
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $businessType = $request->input('businessType');


        $credits = SupplierCredit::whereBetween('date', [$fromDate, $toDate]);
        if ($businessType) {
            $credits->where('business_type_id', $businessType);
        }
        if ($supplier) {
            $credits->where('supplier_id', $supplier);
        }
        $data = [];
        $credits = $credits->with(['supplier', 'refunds', 'payments'])->get();
        $supplierId = array_keys($credits->groupBy('supplier_id')->toArray());
        $supplier = Supplier::whereIn('id', $supplierId)->get(['id', 'display_name']);
        $creditsTotal = $credits->sum('amount');
        $refundedTotal = $credits->pluck('refunds')->collapse()->sum('amount');
        $paymentTotal = $credits->pluck('payments')->collapse()->sum('payment');
        $balance = $creditsTotal - ($paymentTotal + $refundedTotal);

        $data['credits'] = $credits->groupBy('supplier_id');
        $data['credits_total'] = $creditsTotal;
        $data['refunded_total'] = $refundedTotal;
        $data['payment_total'] = $paymentTotal;
        $data['balance'] = $balance;
        $data['supplier'] = $supplier;
        $data['request'] = $request->toArray();
        return $data;
    }

    /**
     * @return array
     */
    public function supplierBalance()
    {
        $request = request();
        $date = $request->input('date') ?? carbon()->toDateString();
        $suppliers = Supplier::with(['orders' => function ($orders) use ($date) {
            $orders->where('order_date', '<=', $date);
        }, 'bills' => function ($orders) use ($date) {
            $orders->where('bill_date', '<=', $date);
        }, 'payments' => function ($orders) use ($date) {
            $orders->where('payment_date', '<=', $date);
        }])->where(function ($q) use ($date) {
            $q->where(function ($q) use ($date) {
                $q->whereHas('orders', function ($orders) use ($date) {
                    $orders->where('order_date', '<=', $date);
                });
            })->orWhere(function ($q) use ($date) {
                $q->whereHas('bills', function ($orders) use ($date) {
                    $orders->where('bill_date', '<=', $date);
                });
            })->orWhere(function ($q) use ($date) {
                $q->whereHas('payments', function ($orders) use ($date) {
                    $orders->where('payment_date', '<=', $date);
                });
            });
        })->get();

        $poTotal = $suppliers->pluck('orders')->collapse()->sum('total');
        $paymentTotal = $suppliers->pluck('payments')->collapse()->sum('payment');
        $data = [];
        $data['suppliers'] = $suppliers;
        $data['po_total'] = $poTotal;
        $data['bill_total'] = $suppliers->pluck('bills')->collapse()->sum('amount');
        $data['payment_total'] = $paymentTotal;
        $data['balance'] = $poTotal - $paymentTotal;
        $data['request'] = $request->toArray();

        return $data;
    }

    /**
     * @return array
     */
    public function agingSummary()
    {
        $data = [];
        $returnData = [];
        $request = request();
        $date = $request->input('date') ?? carbon()->toDateString();
        $bills = Bill::where('due_date', '<=', $date)->with('supplier')->get()->groupBy('supplier_id');
        $supplierID = array_keys($bills->toArray());
        foreach ($bills as $key => $bill) {
            $data[$key] = getDueCollection($bill, $data[$key]);
        }

        $suppliers = Supplier::whereIn('id', $supplierID)->get()->toArray();
        $returnData['data'] = $data;
        $returnData['suppliers'] = $suppliers;
        $returnData['request'] = $request->toArray();
        return $returnData;
    }

    /**
     * @return array
     */
    public function billDetails()
    {
        $request = request();

        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $businessType = $request->input('businessType');
        $bills = Bill::whereBetween('bill_date', [$fromDate, $toDate])->with(['supplier', 'payments'])->get();
        if ($businessType) {
            $bills = $bills->where('business_type_id', $businessType);
        }
        $paymentTotal = $bills->pluck('payments')->collapse()->sum('payment');
        $billTotal = $bills->sum('amount');
        $data = [];
        $data['bills'] = $bills;
        $data['bill_total'] = $bills->sum('amount');
        $data['bill_payment'] = $paymentTotal;
        $data['balance'] = $billTotal - $paymentTotal;
        $data['request'] = $request->toArray();
        return $data;
    }

    /**
     * @return array
     */
    public function agingDetails()
    {
        $request = request();
        $data = [];
        $data['data'] = [];
        $data['data']['1-30'] = $this->getDueBillData($request, [1, 30]);
        $data['data']['31-60'] = $this->getDueBillData($request, [31, 60]);
        $data['data']['61-90'] = $this->getDueBillData($request, [61, 90]);
        $data['data']['>90'] = $this->getDueBillData($request, '>90');
        $data['request'] = $request->toArray();
        return $data;
    }

    /**
     * @param $request
     * @param $dateRange
     * @return mixed
     */
    public function getDueBillData($request, $dateRange)
    {
        $date = $request->input('date') ?? carbon()->toDateString();
        $bills = Bill::whereNotIn('status', ['Paid', 'Canceled'])->where('due_date', '<', $date)->with(['supplier', 'payments'])->get();
        $bills = $bills->filter(function ($bill) use ($dateRange) {
            $dueDate = $bill->due_date;
            $diff = carbon()->diffInDays(carbon($dueDate));
            $bill->dateRange = $dateRange;
            $bill->age = $diff;
            if (!is_array($dateRange) && $dateRange == '>90') {
                if ($diff > 90) return $bill;
                return null;
            }
            if (is_array($dateRange)) {
                $start = array_get($dateRange, 0);
                $end = array_get($dateRange, 1);
                if ($start <= $diff && $end >= $diff) return $bill;
            }
            return null;
        });
        return $bills;
    }
}