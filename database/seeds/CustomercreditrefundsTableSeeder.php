<?php

use Illuminate\Database\Seeder;

class CustomercreditrefundsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customercreditrefunds = [
            [
                'refunded_on' => '2024-02-15',
                'amount' => '150.00',
                'notes' => 'Payment for supplies',
                'payment_mode' => 'Cash',
                'cheque_no' => 'CHQ12345',
                'cheque_date' => '2024-02-15',
                'account_no' => '1234567890',
                'deposited_date' => '2024-02-16',
                'bank_id' => 1,
                'status' => 'Paid',
                'reason_to_cancel' => 'Product not received',
                'refunded_from' => 1,
                'credit_id' => 1,
            ],
            [
                'refunded_on' => '2024-02-14',
                'amount' => '175.00',
                'notes' => 'Payment for services',
                'payment_mode' => 'Cash',
                'cheque_no' => 'CHQ67890',
                'cheque_date' => '2024-02-14',
                'account_no' => '0987654321',
                'deposited_date' => '2024-02-15',
                'bank_id' => 1,
                'status' => 'Paid',
                'reason_to_cancel' => 'Service not satisfactory',
                'refunded_from' => 2,
                'credit_id' => 2,
            ],
            [
                'refunded_on' => '2024-02-13',
                'amount' => '200.00',
                'notes' => 'Refund for overpayment',
                'payment_mode' => 'Cash',
                'cheque_no' => 'CHQ24680',
                'cheque_date' => '2024-02-13',
                'account_no' => '1357924680',
                'deposited_date' => '2024-02-14',
                'bank_id' => 1,
                'status' => 'Paid',
                'reason_to_cancel' => 'Duplicate payment',
                'refunded_from' => 3,
                'credit_id' => 3,
            ],
            [
                'refunded_on' => '2024-02-12',
                'amount' => '225.00',
                'notes' => 'Refund for returned items',
                'payment_mode' => 'Cash',
                'cheque_no' => 'CHQ13579',
                'cheque_date' => '2024-02-12',
                'account_no' => '0246813579',
                'deposited_date' => '2024-02-13',
                'bank_id' => 1,
                'status' => 'Paid',
                'reason_to_cancel' => 'Defective products',
                'refunded_from' => 4,
                'credit_id' => 4,
            ],
            [
                'refunded_on' => '2024-02-11',
                'amount' => '250.00',
                'notes' => 'Refund for canceled order',
                'payment_mode' => 'Cash',
                'cheque_no' => 'CHQ02468',
                'cheque_date' => '2024-02-11',
                'account_no' => '3579024681',
                'deposited_date' => '2024-02-12',
                'bank_id' => 1,
                'status' => 'Canceled',
                'reason_to_cancel' => 'Changed mind',
                'refunded_from' => 5,
                'credit_id' => 5,
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($customercreditrefunds as $key => $brand) {
            $customercreditrefunds[$key]['created_at'] = $now;
            $customercreditrefunds[$key]['updated_at'] = $now;
        }

        \App\CustomerCreditRefund::insert($customercreditrefunds);
    }
}
