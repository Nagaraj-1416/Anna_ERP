<?php

use Illuminate\Database\Seeder;

class TransactionTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transaction_types = [
            [
                'code' => 'TRN001',
                'name' => 'Sales',
                'short_name' => 'SAL',
                'notes' => 'Transaction type for sales',
                'mode' => 'MoneyIn',
                'is_default' => 'Yes',
                'is_active' => 'Yes',
            ],
            [
                'code' => 'TRN002',
                'name' => 'Purchase',
                'short_name' => 'PUR',
                'notes' => 'Transaction type for purchases',
                'mode' => 'MoneyOut',
                'is_default' => 'No',
                'is_active' => 'Yes',
            ],
            [
                'code' => 'TRN003',
                'name' => 'Expense',
                'short_name' => 'EXP',
                'notes' => 'Transaction type for expenses',
                'mode' => 'MoneyOut',
                'is_default' => 'No',
                'is_active' => 'Yes',
            ],
            [
                'code' => 'TRN004',
                'name' => 'Income',
                'short_name' => 'INC',
                'notes' => 'Transaction type for income',
                'mode' => 'MoneyIn',
                'is_default' => 'No',
                'is_active' => 'Yes',
            ],
            [
                'code' => 'TRN005',
                'name' => 'Transfer',
                'short_name' => 'TRF',
                'notes' => 'Transaction type for transfers',
                'mode' => 'MoneyIn',
                'is_default' => 'No',
                'is_active' => 'Yes',
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($transaction_types as $key => $transaction_type) {
            $transaction_types[$key]['created_at'] = $now;
            $transaction_types[$key]['updated_at'] = $now;
        }

        \App\TransactionType::insert($transaction_types);
    }
}
