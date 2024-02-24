<?php

namespace App\Repositories;

use App\Bill;
use App\DailySale;
use App\Expense;
use App\Invoice;
use App\InvoicePayment;
use App\Rep;
use App\SalesOrder;
use App\Staff;

/**
 * Class DashboardStatsRepository
 * @package App\Repositories\Settings
 */
class DashboardStatsRepository extends BaseRepository
{
    /**
     * @param null $range
     * @return array
     */
    public function overDueData($range = null)
    {
        $data = [];
        if ($range) {
            $forIDs = Invoice::whereIn('company_id', userCompanyIds(loggedUser()))
                ->where('customer_id', '!=', null)->whereNotIn('status', ['Paid', 'Canceled', 'Refunded'])
                ->whereDate('due_date', '<', carbon())->get();
            $filtered = $forIDs->reject(function ($item) {
                $paymentsSum = $item->payments->where('status', 'Paid')->sum('payment');
                return $item->amount == $paymentsSum;
            });
            $invoices = $filtered->filter(function ($collection) use ($range) {
                $dueDate = $collection->due_date;
                $collection->showRoute = route('sales.invoice.show', [$collection]);
                $collection->showColumn = 'order_ref';
                $collection->oldAmount = $collection->amount;
                $collection->invAmount = number_format($collection->amount, 2);
                $collection->invDueAmount = invOutstanding($collection)['balance'];
                $collection->dueAmount = number_format(invOutstanding($collection)['balance'], 2);
                $collection->lastPayDate = invOutstanding($collection)['lastPayDate'];
                if ($collection->customer) {
                    $collection->relationColumn = 'customer_name';
                }
                $collection->customer_name = $collection->customer->display_name ?? 'None';
                $collection->order_ref = $collection->order->ref ?? 'None';
                $collection->relation_route = route('sales.customer.show', [$collection->customer]);
                $diff = carbon()->diffInDays(carbon($dueDate));
                if ($range == '>90' || $range == '&gt;90') {
                    if ($diff > 90) return $collection;
                    return null;
                }
                if ((int)$range == 60) {
                    if ($diff <= (int)$range && $diff > 30) return $collection;
                    return null;
                }
                if ((int)$range == 90) {
                    if ($diff > 60 && $diff <= (int)$range) return $collection;
                    return null;
                }
                if ($diff <= (int)$range) return $collection;
                return null;
            });
            $tablesColums = [
                'Customer' => 'customer_name',
                'Order#' => 'order_ref',
                'Order date' => 'invoice_date',
                'Amount' => 'invAmount',
                'Due amount' => 'dueAmount',
                'Last payment date' => 'lastPayDate'
            ];
            $data['data'] = $invoices;

            $data['columns'] = $tablesColums;
            $data['mainHeader'] = 'Payable & Owing';
            $data['subHeader'] = 'Invoices Payable to You';
            $data['total'] = $invoices->sum('invDueAmount');
        }
        return $data;
    }

    /**
     * @param null $range
     * @return array
     */
    public function billOverDue($range = null)
    {
        $data = [];
        if ($range) {
            $forIds = Bill::whereIn('company_id', userCompanyIds(loggedUser()))
                ->whereNotIn('status', ['Paid', 'Canceled'])->whereDate('due_date', '<', carbon())->get();
            $bills = $forIds->filter(function ($collection) use ($range) {
                $dueDate = $collection->due_date;
                $collection->showRoute = route('purchase.bill.show', [$collection]);
                $collection->showColumn = 'bill_no';
                $collection->oldAmount = $collection->amount;
                $collection->dueAmount = number_format($collection->amount, 2);
                if ($collection->supplier) {
                    $collection->relationColumn = 'supplier_name';
                }
                $collection->supplier_name = $collection->supplier->display_name ?? 'None';
                $collection->relation_route = route('purchase.supplier.show', [$collection->supplier]);
                $diff = carbon()->diffInDays(carbon($dueDate));
                if ($range == '>90' || $range == '&gt;90') {
                    if ($diff > 90) return $collection;
                    return null;
                }
                if ((int)$range == 60) {
                    if ($diff <= (int)$range && $diff > 30) return $collection;
                    return null;
                }
                if ((int)$range == 90) {
                    if ($diff > 60 && $diff <= (int)$range) return $collection;
                    return null;
                }
                if ($diff <= (int)$range) return $collection;
                return null;
            });
            $tablesColums = [
                'Supplier' => 'supplier_name',
                'Bill no' => 'bill_no',
                'Bill date' => 'bill_date',
                'Status' => 'status',
                'Amount' => 'dueAmount'
            ];
            $data['data'] = $bills;

            $data['columns'] = $tablesColums;
            $data['mainHeader'] = 'Payable & Owing';
            $data['subHeader'] = 'Bills You Owe';
            $data['total'] = $bills->sum('oldAmount');
        }

        return $data;
    }

    /**
     * @param $request
     * @return array
     */
    public function yearDataIncome($request)
    {
        $data = [];
        $isPreYear = $request->input('preYear');

        $year = carbon()->year;
        $month = carbon()->month;
        $thisYear = \carbon()->setDate($year, $month, 1);
        $from = $thisYear->copy()->startOfYear()->toDateString();
        $to = $thisYear->copy()->endOfYear()->toDateString();

        if ($isPreYear) {
            $preYear = \carbon()->now()->subYear(1);
            $from = $preYear->copy()->startOfYear()->toDateString();
            $to = $preYear->copy()->endOfYear()->toDateString();
        }
        $payments = InvoicePayment::whereIn('company_id', userCompanyIds(loggedUser()))
            ->where('status', 'Paid')
            ->whereBetween('payment_date', [$from, $to])->get();

        $payments = $payments->map(function ($payment) {
            $payment->showRoute = route('sales.invoice.show', [$payment->invoice_id]);
            $payment->ref = $payment->invoice->ref ?? '';
            if ($payment->customer) {
                $payment->relationColumn = 'customer_name';
            }
            $payment->customer_name = $payment->customer->display_name ?? 'None';
            $payment->relation_route = route('sales.customer.show', [$payment->customer]);
            $payment->showColumn = 'ref';
            $payment->dueAmount = number_format($payment->payment, 2);
            return $payment;
        });
        $tablesColumns = [
            'Customer' => 'customer_name',
            'Invoice no' => 'ref',
            'Payment date' => 'payment_date',
            'Amount' => 'dueAmount'
        ];
        $data['columns'] = $tablesColumns;
        $data['mainHeader'] = 'Net Income';
        $data['subHeader'] = 'Income amount breakdown';
        $data['data'] = $payments;
        $data['total'] = $payments->sum('payment');
        return $data;
    }

    /**
     * @param $request
     * @return array
     */
    public function yearDataExpense($request)
    {
        $data = [];
        $isPreYear = $request->input('preYear');

        $year = carbon()->year;
        $month = carbon()->month;
        $thisYear = \carbon()->setDate($year, $month, 1);
        $from = $thisYear->copy()->startOfYear()->toDateString();
        $to = $thisYear->copy()->endOfYear()->toDateString();

        if ($isPreYear) {
            $preYear = \carbon()->now()->subYear(1);
            $from = $preYear->copy()->startOfYear()->toDateString();
            $to = $preYear->copy()->endOfYear()->toDateString();
        }
        $expenses = Expense::whereIn('company_id', userCompanyIds(loggedUser()))
            ->whereIn('status', ['Approved', 'Reimbursed'])
            ->whereBetween('expense_date', [$from, $to])->get();

        $expenses = $expenses->map(function ($expense) {
            $expense->showRoute = route('expense.receipt.show', [$expense->id]);
            $expense->showColumn = 'expense_no';
            $expense->oldAmount = $expense->amount;
            $expense->dueAmount = number_format($expense->amount, 2);
            return $expense;
        });
        $tablesColums = [
            'Expense no' => 'expense_no',
            'Expense date' => 'expense_date',
            'Status' => 'status',
            'Amount' => 'dueAmount'
        ];
        $data['columns'] = $tablesColums;
        $data['mainHeader'] = 'Net Income';
        $data['subHeader'] = 'Expense amount breakdown';
        $data['data'] = $expenses;
        $data['total'] = $expenses->sum('oldAmount');

        return $data;
    }

    /**
     * @param $request
     * @return array|mixed
     */
    public function summaryData($request)
    {
        $get = $request->input('get');
        $data = [];
        if ($get == 'totalSales') {
            $data = $this->getSalesData($request);
        } else if ($get == 'payments') {
            $data = $this->getPaymentData($request);
        }

        return $data;
    }

    /**
     * @param $request
     * @return array
     */
    public function getSalesData($request)
    {
        $now = carbon()->toDateString();
        $company = $request->input('company');
        $shop = $request->input('shop');
        $rep = $request->input('rep');
        $data = [];
        $allocation = '';
        $salesOrder = SalesOrder::where('company_id', $company)->where('order_date', $now)->get();
        if ($shop) {
            $salesOrder = $salesOrder->where('sales_location_id', $shop);
        }

        if ($rep) {
            $salesOrder = $salesOrder->where('prepared_by', $rep);

            /** get staff */
            $staff = Staff::where('user_id', $rep)->first();
            $salesRep = Rep::where('staff_id', $staff->id)->first();
            $allocation = DailySale::where('from_date', '<=', $now)->where('to_date', '>=', $now)->where('rep_id', $salesRep->id)
                ->with('rep', 'vehicle', 'route', 'driver', 'labour', 'odoMeterReading', 'items', 'customers', 'preparedBy', 'orders')->get();
            $allocation = $allocation->map(function ($allocation) {
                $allocation->sales_starts_at = date("F j, Y, g:i:s a", strtotime($allocation->logged_in_at));
                $allocation->sales_ends_at = $allocation->logged_out_at ? date("F j, Y, g:i:s a", strtotime($allocation->logged_out_at)) : 'None';
                $allocation->sales_time = getDifferentTime($allocation->logged_in_at, $allocation->logged_out_at);
                return $allocation;
            })->first();
        }
        $salesOrder = $salesOrder->map(function ($salesOrder) {
            $salesOrder->showRoute = route('sales.order.show', [$salesOrder->id]);
            $salesOrder->showColumn = 'ref';
            $salesOrder->oldAmount = $salesOrder->total;
            $salesOrder->dueAmount = number_format($salesOrder->total);
            if ($salesOrder->customer) {
                $salesOrder->relationColumn = 'customer_name';
            }
            $salesOrder->customer_name = $salesOrder->customer->display_name ?? 'None';
            $salesOrder->relation_route = route('sales.customer.show', [$salesOrder->customer]);
            $payments = $salesOrder->payments;
            $salesOrder->cashSales = number_format($payments->where('payment_mode', 'Cash')->sum('payment'));
            $salesOrder->chequeSales = number_format($payments->where('payment_mode', 'Cheque')->sum('payment'));
            $salesOrder->depositSales = number_format($payments->where('payment_mode', 'Direct Deposit')->sum('payment'));
            $salesOrder->cardSales = number_format($payments->where('payment_mode', 'Credit Card')->sum('payment'));
            if ($salesOrder->is_credit_sales == 'Yes') {
                $salesOrder->is_credit_sales = 'Credit';
            } else {
                $salesOrder->is_credit_sales = 'Cash';
            }
            $salesOrder->createdAt = date("F j, Y, g:i a", strtotime($salesOrder->created_at));
            $salesOrder->total_received = number_format($payments->sum('payment'));
            $salesOrder->balance = number_format($salesOrder->oldAmount - $payments->sum('payment'));
            $salesOrder->distance = round($salesOrder->distance, 2) . 'KM' ?? '0KM';
            if ($salesOrder->gps_lat && $salesOrder->gps_long) {
                $salesOrder->distance_show_route = route('map.index', [
                    'startLat' => $salesOrder->customer->gps_lat,
                    'startLng' => $salesOrder->customer->gps_long,
                    'startInfo' => json_encode(['heading' => $salesOrder->customer->display_name, 'code' => $salesOrder->customer->tamil_name]),
                    'endLat' => $salesOrder->gps_lat,
                    'endLng' => $salesOrder->gps_long,
                    'endInfo' => json_encode(['heading' => $salesOrder->ref, 'date' => date("F j, Y, g:i a", strtotime($salesOrder->created_at)), 'rep' => $salesOrder->salesRep->name ?? ''])
                ]);
            }
            return $salesOrder;
        });
        $payments = $salesOrder->pluck('payments')->collapse();
        $tablesColums = [
            'Customer' => 'customer_name',
            'Order no' => 'ref',
            'Order date & time' => 'createdAt',
            'Distance' => 'distance',
            'Cash/Credit' => 'is_credit_sales',
            'Amount' => 'dueAmount',
            'Cash' => 'cashSales',
            'Cheque' => 'chequeSales',
            'Deposit' => 'depositSales',
            'Card' => 'cardSales',
            'Received' => 'total_received',
            'Balance' => 'balance',
        ];
        $data['allocation'] = $allocation;
        $data['columns'] = $tablesColums;
        $data['mainHeader'] = 'Today\'s Sales Summary';
        $data['subHeader'] = 'Total';
        $data['data'] = $salesOrder;
        $data['header_section'] = true;
        $data['total_columns'] = [
            'Amount' => number_format($salesOrder->sum('total'), 2),
            'Cash' => number_format($payments->where('payment_mode', 'Cash')->sum('payment'), 2),
            'Cheque' => number_format($payments->where('payment_mode', 'Cheque')->sum('payment'), 2),
            'Deposit' => number_format($payments->where('payment_mode', 'Direct Deposit')->sum('payment'), 2),
            'Card' => number_format($payments->where('payment_mode', 'Credit Card')->sum('payment'), 2),
            'Received' => number_format($payments->sum('payment'), 2),
            'Balance' => number_format($salesOrder->sum('total') - $payments->sum('payment'), 2)
        ];
        $data['total'] = $salesOrder->sum('total');
        return $data;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getPaymentData($request)
    {
        $where = $request->input('where');
        $old = $request->input('old');
        $now = carbon()->toDateString();
        $company = $request->input('company');
        $payments = InvoicePayment::where('company_id', $company)->where('payment_date', $now)->where('payment_mode', $where)->get();

        $shop = $request->input('shop');
        $rep = $request->input('rep');
        if ($shop) {
            $payments = $payments->where('sales_location_id', $shop);
        }
        if ($rep) {
            $payments = $payments->where('prepared_by', $rep);
        }
        if ($old) {
            $payments = $payments->filter(function ($value, $key) use ($now) {
                return $value->invoice->invoice_date < $now;
            });
        }
        $payments = $payments->map(function ($payment) {
            $payment->showRoute = route('sales.invoice.show', [$payment->invoice_id]);
            $payment->ref = $payment->invoice->ref ?? '';
            $payment->showColumn = 'ref';
            $payment->dueAmount = number_format($payment->payment, 2);
            if ($payment->customer) {
                $payment->relationColumn = 'customer_name';
            }
            $payment->customer_name = $payment->customer->display_name ?? 'None';
            $payment->relation_route = route('sales.customer.show', [$payment->customer]);
            return $payment;
        });

        $tablesColums = [
            'Customer' => 'customer_name',
            'Invoice no' => 'ref',
            'Payment date' => 'payment_date',
            'Amount' => 'dueAmount'
        ];
        $data['columns'] = $tablesColums;
        $data['mainHeader'] = 'Today\'s Sales Summary';
        $data['subHeader'] = $where;
        $data['data'] = $payments;
        $data['total'] = $payments->sum('payment');
        return $data;
    }

    /**
     * @param $customer
     * @return mixed
     */
    public function getPaymentDataForCustomer($customer)
    {
        $payments = $customer->payments;
        $payments = $payments->map(function ($payment) use ($customer) {
            $payment->showRoute = route('sales.invoice.show', [$payment->invoice_id]);
            $payment->ref = $payment->invoice->ref ?? '';
            $payment->showColumn = 'ref';
            $payment->dueAmount = number_format($payment->payment, 2);
            return $payment;
        });

        $tablesColums = [
            'Invoice no' => 'ref',
            'Payment date' => 'payment_date',
            'Amount' => 'dueAmount'
        ];
        $data['columns'] = $tablesColums;
        $data['mainHeader'] = $customer->display_name . ' Sales';
        $data['data'] = $payments;
        $data['total_columns'] = [
            'Amount' => number_format($payments->sum('payment'), 2)
        ];
        return $data;
    }

    /**
     * @param $rep
     * @return mixed
     */
    public function getPaymentDataForRep($rep)
    {
        $payments = $rep->salesOrders()->with('payments')->get()->pluck('payments')->collapse();
        $payments = $payments->map(function ($payment) use ($rep) {
            $payment->showRoute = route('sales.invoice.show', [$payment->invoice_id]);
            $payment->ref = $payment->invoice->ref ?? '';
            $payment->showColumn = 'ref';
            $payment->dueAmount = number_format($payment->payment, 2);
            return $payment;
        });

        $tablesColums = [
            'Invoice no' => 'ref',
            'Payment date' => 'payment_date',
            'Amount' => 'dueAmount'
        ];
        $data['columns'] = $tablesColums;
        $data['mainHeader'] = $rep->name . ' Sales';
        $data['data'] = $payments;
        $data['total_columns'] = [
            'Amount' => number_format($payments->sum('payment'), 2)
        ];
        return $data;
    }
}