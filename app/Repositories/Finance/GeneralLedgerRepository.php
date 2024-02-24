<?php

namespace App\Repositories\Finance;

use App\Account;
use App\Company;
use App\Customer;
use App\Repositories\BaseRepository;

use App\Supplier;
use App\Transaction;
use App\TransactionRecord;
use App\TransactionType;
use Illuminate\Http\Request;

/**
 * Class GeneralLedgerRepository
 * @package App\Repositories\Report
 */
class GeneralLedgerRepository extends BaseRepository
{
    public function generalLedger()
    {
        $request = request();

        $companyId = $request->input('company');
        $accountId = $request->input('account');
        $supplierId = $request->input('supplier_id');
        $customerId = $request->input('customer_id');
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();

        $data = [];

        $company = Company::where('id', $companyId)->first();
        $account = Account::where('id', $accountId)->first();

        if (!$account) return [];

        $accOpBal = $account->opening_balance;
        $accOpBalType = $account->opening_balance_type;

        /** get initial account balance as at date */
        $transRecordsOp = TransactionRecord::where('date', '<=', carbon($fromDate)->subDay()->toDateString())
            ->where('account_id', $account->id)
            ->get();

        $debitBal1 = $transRecordsOp->where('type', 'Debit')->sum('amount');
        if ($accOpBalType == 'Debit') {
            $debitBal1 = ($accOpBal + $debitBal1);
        }

        $creditBal1 = $transRecordsOp->where('type', 'Credit')->sum('amount');
        if ($accOpBalType == 'Credit') {
            $creditBal1 = ($accOpBal + $creditBal1);
        }

        $intBal = $debitBal1 - $creditBal1;
        $intBal2 = $debitBal1 - $creditBal1;

        $intBalType = '';
        if($debitBal1 > $creditBal1){
            $intBalType = 'Debit';
        }
        elseif($debitBal1 < $creditBal1){
            $intBalType = 'Credit';
        }

        $data['intBal'] = $intBal;
        $data['intBalView'] = abs($intBal);
        $data['intBalType'] = $intBalType;
        /** END */

        /** get given date range trans and balances */
        $transRecords = TransactionRecord::where('date', '>=', $fromDate)
            ->where('date', '<=', $toDate)
            ->where('account_id', $account->id)
            ->when($supplierId, function ($query) use (&$supplierId) {
                return $query->whereHas('transaction', function ($query) use (&$supplierId) {
                    return $query->where('supplier_id', $supplierId);
                });
            })
            ->when($customerId, function ($query) use (&$customerId) {
                return $query->whereHas('transaction', function ($query) use (&$customerId) {
                    return $query->where('customer_id', $customerId);
                });
            })
            ->with('transaction', 'transaction.txType')->orderBy('date', 'asc')->get();

        $transRecords->map(function ($tran) use (&$intBal2, $intBalType) {
            if ($tran->type == 'Debit') {
                $balance = $intBal2 + $tran->amount;
            }else{
                $balance = $intBal2 ? ($intBal2 - $tran->amount) : 0;
            }
            $intBal2 = $balance;

            $tran->balance = $balance;
            $tran->balanceView = abs($balance);

            $tran->tran_type = $tran->transaction->txType->name;

            /** make description to each action */
            if($tran->transaction->action == 'InvoiceCreation'){
                $tran->tran_des_short = 'Sales In';
                $tran->tran_ref_no = $tran->transaction->transactionable && $tran->transaction->transactionable->order ? $tran->transaction->transactionable->order->ref : '';
                $tran->tran_ref_id = $tran->transaction->transactionable && $tran->transaction->transactionable->order ? $tran->transaction->transactionable->order->id : '';
                $tran->tran_ref_url = '/sales/order/';
            }else if($tran->transaction->action == 'PaymentCreation'){
                $tran->tran_des_short = 'Cash In';
                $tran->tran_ref_no = $tran->transaction->transactionable && $tran->transaction->transactionable->invoice ? $tran->transaction->transactionable->invoice->ref : '';
                $tran->tran_ref_id = $tran->transaction->transactionable && $tran->transaction->transactionable->invoice ? $tran->transaction->transactionable->invoice->id : '';
                $tran->tran_ref_url = '/sales/invoice/';
            }else if($tran->transaction->action == 'SalesReturn'){
                $tran->tran_des_short = 'Sales Return';
                $tran->tran_ref_no = $tran->transaction->transactionable ? $tran->transaction->transactionable->code : '';
                $tran->tran_ref_id = $tran->transaction->transactionable ? $tran->transaction->transactionable->id : '';
                $tran->tran_ref_url = '/sales/return/';
            }else if($tran->transaction->action == 'PaymentCancel'){
                $tran->tran_des_short = 'Payment Cancel';
                $tran->tran_ref_no = $tran->transaction->transactionable && $tran->transaction->transactionable->invoice ? $tran->transaction->transactionable->invoice->ref : '';
                $tran->tran_ref_id = $tran->transaction->transactionable && $tran->transaction->transactionable->invoice ? $tran->transaction->transactionable->invoice->id : '';
                $tran->tran_ref_url = '/sales/invoice/';
            }else if($tran->transaction->action == 'InvoiceCancel'){
                $tran->tran_des_short = 'Invoice Cancel';
                $tran->tran_ref_no = $tran->transaction->transactionable ? $tran->transaction->transactionable->ref : '';
                $tran->tran_ref_id = $tran->transaction->transactionable ? $tran->transaction->transactionable->id : '';
                $tran->tran_ref_url = '/sales/invoice/';
            }else if($tran->transaction->action == 'ChequeRealised'){
                $tran->tran_des_short = 'Cheque Realised';
                $tran->tran_ref_no = '';
                $tran->tran_ref_id = '';
                $tran->tran_ref_url = '';
            }else if($tran->transaction->action == 'ChequeBounced'){
                $tran->tran_des_short = 'Cheque Bounced';
                $tran->tran_ref_no = '';
                $tran->tran_ref_id = '';
                $tran->tran_ref_url = '';
            }else if($tran->transaction->action == 'Transfer'){
                $tran->tran_des_short = 'Transfer';
                $tran->tran_ref_no = '';
                $tran->tran_ref_id = '';
                $tran->tran_ref_url = '';
            }else if($tran->transaction->action == 'Expense'){
                $tran->tran_des_short = 'Expense';
                $tran->tran_ref_no = '';
                $tran->tran_ref_id = '';
                $tran->tran_ref_url = '';
            }

            return $tran;
        });

        $debitBal = $transRecords->where('type', 'Debit')->sum('amount');
        if ($intBalType == 'Debit') {
            $debitBal = ($intBal + $debitBal);
        }
        $creditBal = $transRecords->where('type', 'Credit')->sum('amount');
        if ($intBalType == 'Credit') {
            $creditBal = ($intBal + $creditBal);
        }

        $endBal = $debitBal > abs($creditBal) ? ($debitBal - abs($creditBal)) : (abs($creditBal) - $debitBal);

        $data['trans'] = $transRecords;
        $data['debitBal'] = $debitBal;
        $data['creditBal'] = abs($creditBal);
        $data['endBal'] = abs($endBal);
        $data['request'] = $request->toArray();
        return $data;
    }

    public function getCustomersAndSuppliers(): array
    {
        $companyId = request()->input('company');

        if (!$companyId) return ['customers' => [], 'suppliers' => []];

        $customers = Customer::whereHas('journals', function ($query) use (&$companyId) {
            return $query->where('company_id', $companyId);
        })->select(['id', 'full_name'])->get();

        $suppliers = Supplier::whereHas('journals', function ($query) use (&$companyId) {
            return $query->where('company_id', $companyId);
        })->select(['id', 'full_name'])->get();


        return compact('customers', 'suppliers');
    }

    public function generalLedger_()
    {
        $request = request();

        $companyId = $request->input('company');
        $accountId = $request->input('account');
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();

        $data = [];

        $company = Company::where('id', $companyId)->first();
        $account = Account::where('id', $accountId)->first();
        $accOpBal = $account->opening_balance;
        $accOpBalType = $account->opening_balance_type;

        /** get initial account balance as at date */
        $transRecordsOp = TransactionRecord::where('date', '<=', carbon($fromDate)->subDay()->toDateString())
            ->where('account_id', $account->id)
            ->whereHas('transaction', function ($q) use($company) {
                $q->where('company_id', $company->id);
            })
            ->get();

        $debitBal1 = $transRecordsOp->where('type', 'Debit')->sum('amount');
        if ($accOpBalType == 'Debit') {
            $debitBal1 = ($accOpBal + $debitBal1);
        }

        $creditBal1 = $transRecordsOp->where('type', 'Credit')->sum('amount');
        if ($accOpBalType == 'Credit') {
            $creditBal1 = ($accOpBal + $creditBal1);
        }

        $intBal = $debitBal1 - $creditBal1;
        $intBal2 = $debitBal1 - $creditBal1;

        $intBalType = '';
        if($debitBal1 > $creditBal1){
            $intBalType = 'Debit';
        }
        elseif($debitBal1 < $creditBal1){
            $intBalType = 'Credit';
        }

        $data['intBal'] = $intBal;
        $data['intBalView'] = abs($intBal);
        $data['intBalType'] = $intBalType;
        /** END */

        /** get given date range trans and balances */
        $transRecords = TransactionRecord::where('date', '>=', $fromDate)
            ->where('date', '<=', $toDate)
            ->where('account_id', $account->id)
            ->with('transaction', 'transaction.txType')->orderBy('date', 'asc')->get();

        $transRecords->map(function ($tran) use (&$intBal2, $intBalType) {
            if ($tran->type == 'Debit') {
                $balance = $intBal2 + $tran->amount;
            }else{
                $balance = $intBal2 ? ($intBal2 - $tran->amount) : 0;
            }
            $intBal2 = $balance;

            $tran->balance = $balance;
            $tran->balanceView = abs($balance);

            $tran->tran_type = $tran->transaction->txType->name;

            /** make description to each action */
            if($tran->transaction->action == 'InvoiceCreation'){
                $tran->tran_des_short = 'Sales In';
                $tran->tran_ref_no = $tran->transaction->transactionable && $tran->transaction->transactionable->order ? $tran->transaction->transactionable->order->ref : '';
                $tran->tran_ref_id = $tran->transaction->transactionable && $tran->transaction->transactionable->order ? $tran->transaction->transactionable->order->id : '';
                $tran->tran_ref_url = '/sales/order/';
            }else if($tran->transaction->action == 'PaymentCreation'){
                $tran->tran_des_short = 'Cash In';
                $tran->tran_ref_no = $tran->transaction->transactionable && $tran->transaction->transactionable->invoice ? $tran->transaction->transactionable->invoice->ref : '';
                $tran->tran_ref_id = $tran->transaction->transactionable && $tran->transaction->transactionable->invoice ? $tran->transaction->transactionable->invoice->id : '';
                $tran->tran_ref_url = '/sales/invoice/';
            }else if($tran->transaction->action == 'SalesReturn'){
                $tran->tran_des_short = 'Sales Return';
                $tran->tran_ref_no = $tran->transaction->transactionable ? $tran->transaction->transactionable->code : '';
                $tran->tran_ref_id = $tran->transaction->transactionable ? $tran->transaction->transactionable->id : '';
                $tran->tran_ref_url = '/sales/return/';
            }else if($tran->transaction->action == 'PaymentCancel'){
                $tran->tran_des_short = 'Payment Cancel';
                $tran->tran_ref_no = $tran->transaction->transactionable && $tran->transaction->transactionable->invoice ? $tran->transaction->transactionable->invoice->ref : '';
                $tran->tran_ref_id = $tran->transaction->transactionable && $tran->transaction->transactionable->invoice ? $tran->transaction->transactionable->invoice->id : '';
                $tran->tran_ref_url = '/sales/invoice/';
            }else if($tran->transaction->action == 'InvoiceCancel'){
                $tran->tran_des_short = 'Invoice Cancel';
                $tran->tran_ref_no = $tran->transaction->transactionable ? $tran->transaction->transactionable->ref : '';
                $tran->tran_ref_id = $tran->transaction->transactionable ? $tran->transaction->transactionable->id : '';
                $tran->tran_ref_url = '/sales/invoice/';
            }else if($tran->transaction->action == 'ChequeRealised'){
                $tran->tran_des_short = 'Cheque Realised';
                $tran->tran_ref_no = '';
                $tran->tran_ref_id = '';
                $tran->tran_ref_url = '';
            }else if($tran->transaction->action == 'ChequeBounced'){
                $tran->tran_des_short = 'Cheque Bounced';
                $tran->tran_ref_no = '';
                $tran->tran_ref_id = '';
                $tran->tran_ref_url = '';
            }else if($tran->transaction->action == 'Transfer'){
                $tran->tran_des_short = 'Transfer';
                $tran->tran_ref_no = '';
                $tran->tran_ref_id = '';
                $tran->tran_ref_url = '';
            }else if($tran->transaction->action == 'Expense'){
                $tran->tran_des_short = 'Expense';
                $tran->tran_ref_no = '';
                $tran->tran_ref_id = '';
                $tran->tran_ref_url = '';
            }

            return $tran;
        });

        $debitBal = $transRecords->where('type', 'Debit')->sum('amount');
        if ($intBalType == 'Debit') {
            $debitBal = ($intBal + $debitBal);
        }
        $creditBal = $transRecords->where('type', 'Credit')->sum('amount');
        if ($intBalType == 'Credit') {
            $creditBal = ($intBal + $creditBal);
        }

        $endBal = $debitBal > abs($creditBal) ? ($debitBal - abs($creditBal)) : (abs($creditBal) - $debitBal);

        $data['trans'] = $transRecords;
        $data['debitBal'] = $debitBal;
        $data['creditBal'] = abs($creditBal);
        $data['endBal'] = abs($endBal);
        $data['request'] = $request->toArray();
        return $data;
    }

}
