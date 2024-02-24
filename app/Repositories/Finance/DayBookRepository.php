<?php

namespace App\Repositories\Finance;

use App\Account;
use App\Company;
use App\Expense;
use App\Invoice;
use App\InvoicePayment;
use App\Rep;
use App\Repositories\BaseRepository;
use App\SalesExpense;
use App\SalesHandover;
use App\SalesHandoverExcess;
use App\SalesHandoverShortage;
use App\SalesOrder;
use App\Transaction;
use App\TransactionRecord;
use App\Transfer;

/**
 * Class DayBookRepository
 * @package App\Repositories\Finance
 */
class DayBookRepository extends BaseRepository
{
    public function byCompanyDetails()
    {
        $data = [];
        $request = request();
        $companyId = $request->input('companyId');
        $fromDate = $request->input('date');

        $company = Company::where('id', $companyId)->first();
        $repIds = Rep::where('company_id', $company->id)->pluck('id')->toArray();
        $reps = Rep::where('company_id', $company->id)->get();

        $reps = $reps->map(function (Rep $rep) use($fromDate) {
            $rep->cash_sales = getDayBookData($rep, $fromDate)['cashSales'];
            $rep->credit_sales = getDayBookData($rep, $fromDate)['creditSales'];
            $rep->collection = getDayBookData($rep, $fromDate)['collection'];
            $rep->returns = getDayBookData($rep, $fromDate)['totalReturns'];
            $rep->expenses = getDayBookData($rep, $fromDate)['totalExpenses'];
            $rep->transfers = getDayBookData($rep, $fromDate)['totalTransfers'];
            $rep->excesses = getDayBookData($rep, $fromDate)['totalExcesses'];
            $rep->shortages = getDayBookData($rep, $fromDate)['totalShortages'];
            return $rep;
        });

        /** get cash sales */
        $cashOrders = SalesOrder::where('company_id', $company->id)
            ->whereDate('order_date', $fromDate)
            ->whereIn('status', ['Open', 'Closed'])
            ->get();
        $cashPayments = $cashOrders->pluck('payments')->collapse();
        $cashSales = $cashPayments->sum('payment');

        /** get credit sales */
        $creditOrders = SalesOrder::where('company_id', $company->id)
            ->where('order_date', $fromDate)
            ->where('status', 'Open')
            ->where('is_credit_sales', 'Yes')
            ->with('customer')->get();
        $creditSales = $creditOrders->sum('total');

        /** get cash collection */
        $payments = InvoicePayment::where('company_id', $company->id)
            ->where('payment_date', $fromDate)
            ->where('status', 'Paid')
            ->whereHas('order', function ($q) use ($fromDate) {
                $q->whereDate('order_date', '<', $fromDate);
            })
            ->with('order', 'customer')
            ->get();
        $collection = $payments->sum('payment');

        /** get expenses */
        $expenses = SalesExpense::where('company_id', $company->id)
            ->where('expense_date', $fromDate)
            ->with('type', 'preparedBy')->get();
        $totalExpenses = $expenses->sum('amount');

        /** get transfers */
        $accountIds = Account::whereIn('accountable_id', $repIds)
            ->where('accountable_type', 'App\Rep')
            ->pluck('id')->toArray();

        $transfers = TransactionRecord::where('date', $fromDate)
            ->whereIn('account_id', $accountIds)
            ->whereHas('transaction', function ($q) {
                $q->where('action', 'Transfer')->where('transactionable_type', 'App\SalesHandover');
            })->with('transaction')->get();
        $totalTransfers = $transfers->sum('amount');

        /** get excess */
        $excesses = SalesHandoverExcess::whereIn('rep_id', $repIds)
            ->where('date', $fromDate)->get();
        $totalExcesses = $excesses->sum('amount');

        /** get shortage */
        $shortages = SalesHandoverShortage::whereIn('rep_id', $repIds)
            ->where('date', $fromDate)->get();
        $totalShortages = $shortages->sum('amount');

        /** get sales returns | as Credit */
        $returns = InvoicePayment::where('company_id', $company->id)
            ->where('payment_date', $fromDate)
            ->where('payment_mode', 'Customer Credit')
            ->where('status', 'Paid')
            ->with('order', 'customer')
            ->get();
        $totalReturns = $returns->sum('payment');

        /** get debit and credit totals */
        $debitTotal = ($creditSales + $totalExpenses + $totalTransfers + $totalShortages + $totalReturns);
        $creditTotal = ($cashSales + $creditSales + $collection + $totalExcesses);

        $data['company'] = $company->toArray();
        $data['fromRange'] = $fromDate;

        $data['cashOrders'] = $cashOrders;
        $data['cashSales'] = $cashSales;

        $data['creditOrders'] = $creditOrders;
        $data['creditSales'] = $creditSales;

        $data['payments'] = $payments;
        $data['collection'] = $collection;

        $data['returns'] = $returns;
        $data['totalReturns'] = $totalReturns;

        $data['expenses'] = $expenses;
        $data['totalExpenses'] = $totalExpenses;

        $data['transfers'] = $transfers;
        $data['totalTransfers'] = $totalTransfers;

        $data['excesses'] = $excesses;
        $data['totalExcesses'] = $totalExcesses;

        $data['shortages'] = $shortages;
        $data['totalShortages'] = $totalShortages;

        $data['debitTotal'] = $debitTotal;
        $data['creditTotal'] = $creditTotal;

        $data['reps'] = $reps;

        return $data;
    }

    public function byRep()
    {
        $data = [];
        $request = request();

        $repId= $request->input('repId');
        $fromDate= $request->input('date');

        $rep = Rep::where('id', $repId)->first();
        $staff = $rep->staff;
        $user = $staff->user;

        /** get cash sales */
        $cashOrders = SalesOrder::where('rep_id', $repId)
            ->whereDate('order_date', $fromDate)
            ->whereIn('status', ['Open', 'Closed'])
            ->get();

        $cashOrderIds = SalesOrder::where('rep_id', $repId)
            ->whereDate('order_date', $fromDate)
            ->whereIn('status', ['Open', 'Closed'])
            ->pluck('id');
        $cashPayments = InvoicePayment::whereIn('sales_order_id', $cashOrderIds)
            ->whereDate('payment_date', $fromDate)->get();
        $cashSales = $cashPayments->where('status', 'Paid')->sum('payment');

        /** get credit sales */
        $totalOrders = SalesOrder::where('rep_id', $repId)
            ->where('order_date', $fromDate)
            ->whereIn('status', ['Open', 'Closed'])
            ->with('customer')->get();
        $creditOrders = SalesOrder::where('rep_id', $repId)
            ->where('order_date', $fromDate)
            ->where('status', '!=', 'Canceled')
            ->with('customer')->get();
        $creditOrders = $creditOrders->map(function (SalesOrder $order) use ($fromDate){
            $order->balance = soOutstandingByDate($order->id, $fromDate)['balance'];
            return $order;
        });
        $creditOrders = $creditOrders->reject(function ($item) {
            return $item->balance == 0;
        });
        $totalsSales = $totalOrders->sum('total');
        //$creditSales = ($totalsSales - $cashSales);
        $creditSales = $creditOrders->sum('balance');

        /** get cash collection */
        $payments = InvoicePayment::where('prepared_by', $user->id)
            ->where('payment_date', $fromDate)
            ->where('status', 'Paid')
            ->whereHas('order', function ($q) use ($fromDate) {
                $q->whereDate('order_date', '<', $fromDate);
            })
            ->with('order', 'customer')
            ->get();
        $collection = $payments->sum('payment');

        /** get expenses */
        $expenses = SalesExpense::where('prepared_by', $user->id)
            ->where('expense_date', $fromDate)
            ->with('type', 'preparedBy')->get();
        $totalExpenses = $expenses->sum('amount');

        /** get transfers */
        $accountIds = Account::where('accountable_id', $rep->id)
            ->where('accountable_type', 'App\Rep')
            ->pluck('id')->toArray();

        $transfers = TransactionRecord::where('date', $fromDate)
            ->whereIn('account_id', $accountIds)
            ->whereHas('transaction', function ($q) {
                $q->where('action', 'Transfer')->where('transactionable_type', 'App\SalesHandover');
            })->with('transaction')->get();
        $totalTransfers = $transfers->sum('amount');

        /** get excess */
        $excesses = SalesHandoverExcess::where('rep_id', $repId)
            ->where('date', $fromDate)->get();
        $totalExcesses = $excesses->sum('amount');

        /** get shortage */
        $shortages = SalesHandoverShortage::where('rep_id', $repId)
            ->where('date', $fromDate)->get();
        $totalShortages = $shortages->sum('amount');

        /** get sales returns | as Credit */
        $returns = InvoicePayment::where('prepared_by', $user->id)
            ->where('payment_date', $fromDate)
            ->where('payment_mode', 'Customer Credit')
            ->where('status', 'Paid')
            ->with('order', 'customer')
            ->get();
        $totalReturns = $returns->sum('payment');

        /** get debit and credit totals */
        $debitTotal = ($creditSales + $totalExpenses + $totalTransfers + $totalShortages + $totalReturns);
        $creditTotal = ($cashSales + $creditSales + $collection + $totalExcesses);

        $data['rep'] = $rep->toArray();
        $data['fromRange'] = $fromDate;

        $data['cashOrders'] = $cashOrders;
        $data['cashSales'] = $cashSales;

        $data['creditOrders'] = $creditOrders;
        $data['creditSales'] = $creditSales;

        $data['payments'] = $payments;
        $data['collection'] = $collection;

        $data['returns'] = $returns;
        $data['totalReturns'] = $totalReturns;

        $data['expenses'] = $expenses;
        $data['totalExpenses'] = $totalExpenses;

        $data['transfers'] = $transfers;
        $data['totalTransfers'] = $totalTransfers;

        $data['excesses'] = $excesses;
        $data['totalExcesses'] = $totalExcesses;

        $data['shortages'] = $shortages;
        $data['totalShortages'] = $totalShortages;

        $data['debitTotal'] = $debitTotal;
        $data['creditTotal'] = $creditTotal;

        return $data;
    }

    public function byCompany()
    {
        $data = [];
        $request = request();
        $companyId = $request->input('companyId');
        $fromDate = $request->input('date');
        $preDate = carbon($request->input('date'))->subDay()->toDateString();

        $company = Company::where('id', $companyId)->first();
        $repIds = Rep::where('company_id', $company->id)->pluck('id')->toArray();
        $reps = Rep::where('company_id', $company->id)->get();

        $reps = $reps->map(function (Rep $rep) use($fromDate) {
            $rep->transfers = getDayBookData($rep, $fromDate)['totalTransfers'];
            return $rep;
        });

        /** get opening */
        $opRecord = $this->byCompanyPreDay($company, $preDate);
        $openingBalType = $opRecord['openingBalType'];
        $openingBal = $opRecord['openingBal'];

        /** get transfers */
        /*$accountIds = Account::where('accountable_id', $company->id)
            ->where('accountable_type', 'App\Company')
            ->pluck('id')->toArray();*/

        $accountIds = Account::where('accountable_id', $company->id)
            ->where('accountable_type', 'App\Company')
            ->orWhere('company_id', $company->id)
            ->whereIn('prefix', ['Cash', 'CIH', 'Bank'])
            ->pluck('id')->toArray();

        $transfers = TransactionRecord::where('date', $fromDate)
            ->whereIn('account_id', $accountIds)
            ->whereHas('transaction', function ($q) {
                $q->where('action', 'Transfer')->where('transactionable_type', 'App\SalesHandover');
            })->with('transaction')->get();
        $totalTransfers = $transfers->sum('amount');

        /** get cash account */
        $cashAccountId = Account::where('accountable_id', $company->id)
            ->where('accountable_type', 'App\Company')
            ->where('prefix', 'Cash')
            ->where('account_type_id', 1)
            ->pluck('id')->toArray();

        /** get cash account */
        $cashAccount = Account::where('accountable_id', $company->id)
            ->where('accountable_type', 'App\Company')
            ->where('prefix', 'Cash')
            ->where('account_type_id', 1)
            ->first();

        $openingCashAccBal = accRunningBalAsDate($cashAccount, carbon($preDate))['endBal'];
        $closingCashAccBal = accRunningBalAsDate($cashAccount, carbon($fromDate))['endBal'];

        /** get cash transfers */
        $cashTransfers = TransactionRecord::where('date', $fromDate)
            ->where('account_id', $cashAccountId)
            ->whereHas('transaction', function ($q) {
                $q->where('action', 'Transfer')->where('transactionable_type', 'App\SalesHandover');
            })->with('transaction')->get();
        $cashTransfers = $cashTransfers->map(function ($cashTransfer) {
            $handOver = SalesHandover::find($cashTransfer->transaction->transactionable_id);
            $cashTransfer->route = $handOver->dailySale->route->name;
            return $cashTransfer;
        });
        $totalCashTransfers = $cashTransfers->sum('amount');

        /** get CIH account */
        $cihAccountId = Account::where('accountable_id', $company->id)
            ->where('accountable_type', 'App\Company')
            ->where('prefix', 'CIH')
            ->where('account_type_id', 19)
            ->pluck('id')->toArray();

        /** get cheque account */
        $cihAccount = Account::where('accountable_id', $company->id)
            ->where('accountable_type', 'App\Company')
            ->where('prefix', 'CIH')
            ->where('account_type_id', 19)
            ->first();

        $openingChequeAccBal = accRunningBalAsDate($cihAccount, carbon($preDate))['endBal'];
        $closingChequeAccBal = accRunningBalAsDate($cihAccount, carbon($fromDate))['endBal'];

        /** get cheque transfers */
        $chequeTransfers = TransactionRecord::where('date', $fromDate)
            ->where('account_id', $cihAccountId)
            ->whereHas('transaction', function ($q) {
                $q->where('action', 'Transfer')->where('transactionable_type', 'App\SalesHandover');
            })->with('transaction')->get();
        $chequeTransfers = $chequeTransfers->map(function ($chequeTransfer) {
            $handOver = SalesHandover::find($chequeTransfer->transaction->transactionable_id);
            $chequeTransfer->route = $handOver->dailySale->route->name;
            return $chequeTransfer;
        });
        $totalChequeTransfers = $chequeTransfers->sum('amount');

        /** get all the expenses */
        $expenses = Expense::where('company_id', $company->id)
            ->where('expense_category', 'Office')
            ->where('expense_date', $fromDate)
            ->with('type', 'preparedBy', 'expenseAccount')->get();
        $totalExpenses = $expenses->sum('amount');

        /** get all deposits */
        $deposits = TransactionRecord::where('date', $fromDate)
            ->whereIn('account_id', $accountIds)
            ->whereHas('transaction', function ($q) {
                $q->where('action', 'Transfer')->where('transactionable_type', 'App\Transfer');
            })->with('transaction')->get();

        $transferDebitRecords = $deposits->where('type', 'Debit');
        $transferCreditRecords = $deposits->where('type', 'Credit');

        $transferDebitBal = $deposits->where('type', 'Debit')->sum('amount');
        $transferCreditBal = $deposits->where('type', 'Credit')->sum('amount');

        $totalDeposits = $deposits->sum('amount');

        /** get goods purchases */
        $productionPurchaseAccounts = Account::where('prefix', 'Purchase')
            ->where('company_id', $company->id)
            ->whereIn('accountable_type', ['App\ProductionUnit', 'App\Store', 'App\Company'])
            ->pluck('id')->toArray();

        $purchases = TransactionRecord::where('date', $fromDate)
            ->whereIn('account_id', $productionPurchaseAccounts)
            ->whereHas('transaction', function ($q) use($company){
                $q->where('action', 'GoodsPurchased')
                    ->where('transactionable_type', 'App\Grn');
            })->with('transaction')->get();
        $totalPurchases = $purchases->sum('amount');

        /** get goods sold */
        $productionSalesAccounts = Account::where('prefix', 'Sales')
            ->where('company_id', $company->id)
            ->whereIn('accountable_type', ['App\ProductionUnit', 'App\Store', 'App\Company'])
            ->pluck('id')->toArray();

        $goodsSold = TransactionRecord::where('date', $fromDate)
            ->whereIn('account_id', $productionSalesAccounts)
            ->whereHas('transaction', function ($q) {
                $q->where('action', 'GoodsSold')->where('transactionable_type', 'App\Grn');
            })->with('transaction')->get();
        $totalGoodsSold = $goodsSold->sum('amount');

        /** get all returned cheques */
        $returnedCheques = Transaction::where('date', $fromDate)
            ->where('company_id', $company->id)
            ->where('customer_id', null)
            ->where('action', 'ChequeBounced')->get();
        $totalReturnedCheques = $returnedCheques->sum('amount');

        /** other transactions */
        $tranRecords = TransactionRecord::where('date', $fromDate)
            ->whereIn('account_id', $accountIds)
            ->whereHas('transaction', function ($q) {
                $q->where('category', 'Manual')
                    ->where('transactionable_id', null)
                    ->where('transactionable_type', null);
            })->with('transaction', 'transaction.txType')->get();

        $transDebitRecords = $tranRecords->where('type', 'Debit');
        $transCreditRecords = $tranRecords->where('type', 'Credit');

        $transDebitBal = $tranRecords->where('type', 'Debit')->sum('amount');
        $transCreditBal = $tranRecords->where('type', 'Credit')->sum('amount');

        /** get debit and credit totals */
        $debitTotal = ($totalExpenses + $totalPurchases + $transferCreditBal + $transCreditBal + $totalReturnedCheques);
        if($openingBalType == 'Debit'){
            $debitTotal = ($debitTotal + $openingBal);
        }
        $creditTotal = ($totalTransfers + $transferDebitBal + $transDebitBal + $totalGoodsSold);
        if($openingBalType == 'Credit'){
            $creditTotal = ($creditTotal + $openingBal);
        }

        if($debitTotal > $creditTotal){
            $closingBalType = 'Debit';
            $closingBal = ($debitTotal - $creditTotal);
        }else{
            $closingBalType = 'Credit';
            $closingBal = ($creditTotal - $debitTotal);
        }

        $data['company'] = $company->toArray();
        $data['fromRange'] = $fromDate;

        $data['openingCashAccBal'] = $openingCashAccBal;
        $data['openingChequeAccBal'] = $openingChequeAccBal;

        $data['transfers'] = $transfers;
        $data['totalTransfers'] = $totalTransfers;

        $data['purchases'] = $purchases;
        $data['totalPurchases'] = $totalPurchases;

        $data['goodsSold'] = $goodsSold;
        $data['totalGoodsSold'] = $totalGoodsSold;

        $data['expenses'] = $expenses;
        $data['totalExpenses'] = $totalExpenses;

        $data['deposits'] = $deposits;
        $data['totalDeposits'] = $totalDeposits;

        $data['returnedCheques'] = $returnedCheques;
        $data['totalReturnedCheques'] = $totalReturnedCheques;

        $data['cashTransfers'] = $cashTransfers;
        $data['totalCashTransfers'] = $totalCashTransfers;

        $data['chequeTransfers'] = $chequeTransfers;
        $data['totalChequeTransfers'] = $totalChequeTransfers;

        $data['transferDebitRecords'] = $transferDebitRecords;
        $data['transferDebitBal'] = $transferDebitBal;

        $data['transferCreditRecords'] = $transferCreditRecords;
        $data['transferCreditBal'] = $transferCreditBal;

        $data['debitRecords'] = $transDebitRecords;
        $data['debitBal'] = $transDebitBal;

        $data['creditRecords'] = $transCreditRecords;
        $data['creditBal'] = $transCreditBal;

        $data['openingBalType'] = $openingBalType;
        $data['openingBal'] = $openingBal;

        $data['closingBalType'] = $closingBalType;
        $data['closingBal'] = $closingBal;

        $data['debitTotal'] = $debitTotal;
        $data['creditTotal'] = $creditTotal;

        $data['closingCashAccBal'] = $closingCashAccBal;
        $data['closingChequeAccBal'] = $closingChequeAccBal;

        $data['reps'] = $reps;

        return $data;
    }

    public function byCompanyPreDay($company, $date)
    {
        $data = [];

        /** get transfers */
        /*$accountIds = Account::where('accountable_id', $company->id)
            ->where('accountable_type', 'App\Company')
            ->pluck('id')->toArray();*/

        $accountIds = Account::where('accountable_id', $company->id)
            ->where('accountable_type', 'App\Company')
            ->orWhere('company_id', $company->id)
            ->whereIn('prefix', ['Cash', 'CIH', 'Bank'])
            ->pluck('id')->toArray();

        $transfers = TransactionRecord::where('date', '<=', $date)
            ->whereIn('account_id', $accountIds)
            ->whereHas('transaction', function ($q) {
                $q->where('action', 'Transfer')->where('transactionable_type', 'App\SalesHandover');
            })->with('transaction')->get();
        $totalTransfers = $transfers->sum('amount');

        /** get all the expenses */
        $expenses = Expense::where('company_id', $company->id)
            ->where('expense_category', 'Office')
            ->where('expense_date', '<=', $date)
            ->with('type', 'preparedBy', 'expenseAccount')->get();
        $totalExpenses = $expenses->sum('amount');

        /** get all deposits */
        $deposits = TransactionRecord::where('date', '<=', $date)
            ->whereIn('account_id', $accountIds)
            ->whereHas('transaction', function ($q) {
                $q->where('action', 'Transfer')->where('transactionable_type', 'App\Transfer');
            })->with('transaction')->get();

        $transferDebitBal = $deposits->where('type', 'Debit')->sum('amount');
        $transferCreditBal = $deposits->where('type', 'Credit')->sum('amount');

        /** other transactions */
        $tranRecords = TransactionRecord::where('date', '<=', $date)
            ->whereIn('account_id', $accountIds)
            ->whereHas('transaction', function ($q) {
                $q->where('category', 'Manual')
                    ->where('transactionable_id', null)
                    ->where('transactionable_type', null);
            })->with('transaction', 'transaction.txType')->get();

        $transDebitBal = $tranRecords->where('type', 'Debit')->sum('amount');
        $transCreditBal = $tranRecords->where('type', 'Credit')->sum('amount');

        /** get goods purchases */
        $productionPurchaseAccounts = Account::where('prefix', 'Purchase')
            ->where('company_id', $company->id)
            ->whereIn('accountable_type', ['App\ProductionUnit', 'App\Store', 'App\Company'])
            ->pluck('id')->toArray();

        $purchases = TransactionRecord::where('date', '<=', $date)
            ->whereIn('account_id', $productionPurchaseAccounts)
            ->whereHas('transaction', function ($q) use($company){
                $q->where('action', 'GoodsPurchased')
                    ->where('transactionable_type', 'App\Grn');
            })->with('transaction')->get();
        $totalPurchases = $purchases->sum('amount');

        /** get goods sold */
        $productionSalesAccounts = Account::where('prefix', 'Sales')
            ->where('company_id', $company->id)
            ->whereIn('accountable_type', ['App\ProductionUnit', 'App\Store', 'App\Company'])
            ->pluck('id')->toArray();

        $goodsSold = TransactionRecord::where('date', '<=', $date)
            ->whereIn('account_id', $productionSalesAccounts)
            ->whereHas('transaction', function ($q) {
                $q->where('action', 'GoodsSold')->where('transactionable_type', 'App\Grn');
            })->with('transaction')->get();
        $totalGoodsSold = $goodsSold->sum('amount');

        /** get all returned cheques */
        $returnedCheques = Transaction::where('date', '<=', $date)
            ->where('company_id', $company->id)
            ->where('customer_id', null)
            ->where('action', 'ChequeBounced')->get();
        $totalReturnedCheques = $returnedCheques->sum('amount');

        /** get debit and credit totals */
        $debitTotal = ($totalExpenses + $totalPurchases + $transferCreditBal + $transCreditBal + $totalReturnedCheques);
        $creditTotal = ($totalTransfers + $transferDebitBal + $transDebitBal + $totalGoodsSold);

        /** get cash account */
        $cashAccountId = Account::where('accountable_id', $company->id)
            ->where('accountable_type', 'App\Company')
            ->where('prefix', 'Cash')
            ->where('account_type_id', 1)
            ->pluck('id')->toArray();

        /** get CIH account */
        $cihAccountId = Account::where('accountable_id', $company->id)
            ->where('accountable_type', 'App\Company')
            ->where('prefix', 'CIH')
            ->where('account_type_id', 19)
            ->pluck('id')->toArray();

        $cashAccOpBal = Account::where('id', $cashAccountId)->sum('opening_balance');
        $cashAccOpBalType = Account::where('id', $cashAccountId)->first()->opening_balance_type;
        if($cashAccOpBalType == 'Debit'){
            $creditTotal = $creditTotal + $cashAccOpBal;
        }else{
            $debitTotal = $debitTotal + $cashAccOpBal;
        }

        $cihAccOpBal = Account::where('id', $cihAccountId)->sum('opening_balance');
        $cihAccOpBalType = Account::where('id', $cihAccountId)->first()->opening_balance_type;
        if($cihAccOpBalType == 'Debit'){
            $creditTotal = $creditTotal + $cihAccOpBal;
        }else{
            $debitTotal = $debitTotal + $cihAccOpBal;
        }

        if($debitTotal > $creditTotal){
            $openingBalType = 'Debit';
            $openingBal = ($debitTotal - $creditTotal);
        }else{
            $openingBalType = 'Credit';
            $openingBal = ($creditTotal - $debitTotal);
        }

        $data['openingBalType'] = $openingBalType;
        $data['openingBal'] = $openingBal;

        return $data;
    }

}