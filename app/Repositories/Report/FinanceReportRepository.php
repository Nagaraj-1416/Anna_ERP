<?php

namespace App\Repositories\Report;

use App\Account;
use App\Customer;
use App\Repositories\BaseRepository;
use App\Transaction;
use App\TransactionRecord;

/**
 * Class SalesReportRepository
 * @package App\Repositories\Report
 */
class FinanceReportRepository extends BaseRepository
{
    public function customerLedger()
    {
        $request = request();
        $request->validate([
            'customer' => 'required'
        ]);
        $customerId = $request->input('customer');
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();

        /** get customer account */
        $customer = Customer::where('id', $customerId)->first();
        $account = Account::where('accountable_id', $customerId)->where('accountable_type', 'App\Customer')->first();

        $runningBalance = customerLedger2($customer, carbon($fromDate), carbon($toDate));

        $data = [];
        $data['customer'] = $customer;
        $data['account'] = $account;
        $data['trans'] = $runningBalance['trans'];
        $data['balances'] = $runningBalance;
        return $data;
    }

    public function transfers()
    {
        $request = request();
        $request->validate([
            'company' => 'required'
        ]);

    }
}