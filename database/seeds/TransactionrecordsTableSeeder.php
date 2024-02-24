<?php

use Illuminate\Database\Seeder;

class TransactionrecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transaction_records = [
            [
                'date' => '2024-02-01',
                'amount' => '500.00',
                'type' => 'Debit',
                'account_id' => 1,
                'transaction_id' => 1,
            ],
            [
                'date' => '2024-02-02',
                'amount' => '200.00',
                'type' => 'Credit',
                'account_id' => 2,
                'transaction_id' => 2,
            ],
            [
                'date' => '2024-02-03',
                'amount' => '700.00',
                'type' => 'Debit',
                'account_id' => 3,
                'transaction_id' => 3,
            ],
            [
                'date' => '2024-02-04',
                'amount' => '150.00',
                'type' => 'Credit',
                'account_id' => 4,
                'transaction_id' => 4,
            ],
            [
                'date' => '2024-02-05',
                'amount' => '600.00',
                'type' => 'Debit',
                'account_id' => 5,
                'transaction_id' => 5,
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($transaction_records as $key => $transaction_record) {
            $transaction_records[$key]['created_at'] = $now;
            $transaction_records[$key]['updated_at'] = $now;
        }

        \App\TransactionRecord::insert($transaction_records);
    }
}
