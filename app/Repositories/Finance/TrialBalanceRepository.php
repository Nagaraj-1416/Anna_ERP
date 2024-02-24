<?php

namespace App\Repositories\Finance;

use App\Account;
use App\Company;
use App\Customer;
use App\Repositories\BaseRepository;

use App\Transaction;
use App\TransactionRecord;
use App\TransactionType;
use Illuminate\Http\Request;

/**
 * Class TrialBalanceRepository
 * @package App\Repositories\Report
 */
class TrialBalanceRepository extends BaseRepository
{
    public function trialBalance()
    {
        $request = request();

        $companyId = $request->input('company');
        $accountId = $request->input('account');
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();

        $data = [];

        $company = Company::where('id', $companyId)->first();
        $accounts = Account::where('company_id', $companyId);

        if ($accountId) {
            $accounts = $accounts->where('id', $accountId)->with('category', 'type')->get();
        } else {
            $accounts = $accounts->with('category', 'type')->get();
        }

        $accounts = $accounts->map(function (Account $account) use ($company, $fromDate, $toDate) {
            $account->acc_op_bal = accBalanceByDate($company, $account, $fromDate, $toDate)['intBalView'];
            $account->acc_debit_bal = accBalanceByDate($company, $account, $fromDate, $toDate)['debitBal'];
            $account->acc_credit_bal = accBalanceByDate($company, $account, $fromDate, $toDate)['creditBal'];
            $account->acc_running_bal = accBalanceByDate($company, $account, $fromDate, $toDate)['endBal'];
            return $account;
        });

        $data['opBal'] = $accounts->sum('acc_op_bal');
        $data['debitBal'] = $accounts->sum('acc_debit_bal');
        $data['creditBal'] = $accounts->sum('acc_credit_bal');
        $data['endBal'] = abs($accounts->sum('acc_debit_bal') - $accounts->sum('acc_credit_bal'));
        $data['accounts'] = $accounts;
        $data['fromDate'] = $fromDate;
        $data['toDate'] = $toDate;
        return $data;
    }

}
