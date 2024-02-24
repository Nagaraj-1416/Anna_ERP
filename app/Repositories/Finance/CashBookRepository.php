<?php

namespace App\Repositories\Finance;

use App\Account;
use App\Company;
use App\InvoicePayment;
use App\Rep;
use App\Repositories\BaseRepository;
use App\SalesExpense;
use App\SalesOrder;
use App\TransactionRecord;

/**
 * Class CashBookRepository
 * @package App\Repositories\Finance
 */
class CashBookRepository extends BaseRepository
{
    public function byCompany()
    {
        $data = [];
        $request = request();
        $companyId = $request->input('companyId');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        $company = Company::where('id', $companyId)->first();

        $data['company'] = $company->toArray();
        $data['fromRange'] = $fromDate;
        $data['toRange'] = $toDate;

        return $data;
    }

    public function byRep()
    {
        $data = [];
        $request = request();
        $repId= $request->input('repId');
        $fromDate= $request->input('fromDate');
        $toDate= $request->input('toDate');

        $rep = Rep::where('id', $repId)->first();
        $staff = $rep->staff;
        $user = $staff->user;

        $orders = SalesOrder::where('rep_id', $repId)
            ->whereBetween('order_date', [$fromDate, $toDate])->with('customer')->get();
        $totalSales = $orders->sum('total');

        $cashPayments = InvoicePayment::where('prepared_by', $user->id)
            ->whereBetween('payment_date', [$fromDate, $toDate])
            ->where('payment_mode', 'Cash')->with('order', 'customer')
            ->get();
        $totalCash = $cashPayments->sum('payment');

        $chequePayments = InvoicePayment::where('prepared_by', $user->id)
            ->whereBetween('payment_date', [$fromDate, $toDate])
            ->where('payment_mode', 'Cheque')->with('order', 'customer')
            ->get();
        $totalCheques = $chequePayments->sum('payment');

        $expenses = SalesExpense::where('prepared_by', $user->id)
            ->whereBetween('expense_date', [$fromDate, $toDate])
            ->with('type', 'preparedBy')->get();
        $totalExpenses = $expenses->sum('amount');

        $accountIds = Account::where('accountable_id', $rep->id)
            ->where('accountable_type', 'App\Rep')
            ->pluck('id')->toArray();

        $transfers = TransactionRecord::whereBetween('date', [$fromDate, $toDate])
            ->whereIn('account_id', $accountIds)
            ->whereHas('transaction', function ($q) {
                $q->where('action', 'Transfer')->where('transactionable_type', 'App\SalesHandover');
            })->with('transaction')->get();
        $totalTransfers = $transfers->sum('amount');

        $debitTotal = ($totalCash + $totalCheques);
        $creditTotal = ($totalSales + $totalExpenses);

        $cashOnHand = (($totalCash - $totalExpenses) + $totalCheques) - $totalTransfers;

        $data['rep'] = $rep->toArray();
        $data['fromRange'] = $fromDate;
        $data['toRange'] = $toDate;
        $data['sales'] = $orders;
        $data['totalSales'] = $totalSales;
        $data['cashPayments'] = $cashPayments;
        $data['totalCash'] = $totalCash;
        $data['chequePayments'] = $chequePayments;
        $data['totalCheques'] = $totalCheques;
        $data['expenses'] = $expenses;
        $data['totalExpenses'] = $totalExpenses;
        $data['transfers'] = $transfers;
        $data['totalTransfers'] = $totalTransfers;
        $data['debitTotal'] = $debitTotal;
        $data['creditTotal'] = $creditTotal;
        $data['cashOnHand'] = $cashOnHand;

        return $data;
    }

}