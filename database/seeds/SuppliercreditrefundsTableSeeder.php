<?php

use Illuminate\Database\Seeder;

class SuppliercreditrefundsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supplier_credits_refunds = [
[
        'refunded_on' => '2024-02-01',
        'amount' => '150.00',
        'notes' => 'Payment for supplies',
        'payment_mode' => 'Direct Deposit',
        'cheque_no' => 'CHQ123',
        'cheque_date' => '2024-01-20',
        'account_no' => 'ACC456',
        'deposited_date' => '2024-01-25',
        'bank_id' => 1,
        'status' => 'Paid',
        'reason_to_cancel' => 'Cancelled due to duplicate payment',
        'refunded_to' => 1,
        'credit_id' => 1,
    ],
    [
        'refunded_on' => '2024-02-02',
        'amount' => '200.00',
        'notes' => 'Payment for services',
        'payment_mode' => 'Cash',
        'cheque_no' => 'CHQ456',
        'cheque_date' => '2024-01-21',
        'account_no' => 'ACC789',
        'deposited_date' => '2024-01-26',
        'bank_id' => 2,
        'status' => 'Canceled',
        'reason_to_cancel' => 'Incorrect amount entered',
        'refunded_to' => 2,
        'credit_id' => 2,
    ],
    [
        'refunded_on' => '2024-02-03',
        'amount' => '300.00',
        'notes' => 'Payment for equipment',
        'payment_mode' => 'Direct Deposit',
        'cheque_no' => 'CHQ789',
        'cheque_date' => '2024-01-22',
        'account_no' => 'ACC101112',
        'deposited_date' => '2024-01-27',
        'bank_id' => 3,
        'status' => 'Paid',
        'reason_to_cancel' => 'Cancelled due to supplier issue',
        'refunded_to' => 3,
        'credit_id' => 3,
    ],
    [
        'refunded_on' => '2024-02-04',
        'amount' => '250.00',
        'notes' => 'Payment for repairs',
        'payment_mode' => 'Cash',
        'cheque_no' => 'CHQ101112',
        'cheque_date' => '2024-01-23',
        'account_no' => 'ACC131415',
        'deposited_date' => '2024-01-28',
        'bank_id' => 4,
        'status' => 'Canceled',
        'reason_to_cancel' => 'Supplier requested cancellation',
        'refunded_to' => 4,
        'credit_id' => 4,
    ],
    [
        'refunded_on' => '2024-02-05',
        'amount' => '180.00',
        'notes' => 'Payment for maintenance',
        'payment_mode' => 'Direct Deposit',
        'cheque_no' => 'CHQ131415',
        'cheque_date' => '2024-01-24',
        'account_no' => 'ACC161718',
        'deposited_date' => '2024-01-29',
        'bank_id' => 5,
        'status' => 'Paid',
        'reason_to_cancel' => 'Cancelled by customer request',
        'refunded_to' => 5,
        'credit_id' => 5,
    ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($supplier_credits_refunds as $key => $supplier_credits_refund) {
            $supplier_credits_refunds[$key]['created_at'] = $now;
            $supplier_credits_refunds[$key]['updated_at'] = $now;
        }

        \App\SupplierCreditRefund::insert($supplier_credits_refunds);
    }
}
