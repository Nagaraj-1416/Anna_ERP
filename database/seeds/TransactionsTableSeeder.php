<?php

use Illuminate\Database\Seeder;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
$transactions = [
    [
        'code' => 'TRX001',
        'date' => '2024-02-01',
        'category' => 'Auto',
        'type' => 'Deposit',
        'amount' => 500.00,
        'auto_narration' => 'Deposit from sales',
        'manual_narration' => null,
        'notes' => 'Transaction for deposit from sales',
        'action' => 'Deposit',
        'tx_type_id' => 1,
        'transactionable_id' => null,
        'transactionable_type' => null,
        'supplier_id' => null,
        'customer_id' => 1,
        'prepared_by' => 1,
        'business_type_id' => 1,
        'company_id' => 1,
    ],
    [
        'code' => 'TRX002',
        'date' => '2024-02-02',
        'category' => 'Manual',
        'type' => 'Withdrawal',
        'amount' => 200.00,
        'auto_narration' => null,
        'manual_narration' => 'Withdrawal for expenses',
        'notes' => 'Transaction for withdrawal of expenses',
        'action' => 'Withdrawal',
        'tx_type_id' => 3,
        'transactionable_id' => null,
        'transactionable_type' => null,
        'supplier_id' => null,
        'customer_id' => null,
        'prepared_by' => 2,
        'business_type_id' => 2,
        'company_id' => 1,
    ],
    [
        'code' => 'TRX003',
        'date' => '2024-02-03',
        'category' => 'Auto',
        'type' => 'Deposit',
        'amount' => 700.00,
        'auto_narration' => 'Deposit from online sales',
        'manual_narration' => null,
        'notes' => 'Transaction for deposit from online sales',
        'action' => 'Deposit',
        'tx_type_id' => 2,
        'transactionable_id' => null,
        'transactionable_type' => null,
        'supplier_id' => null,
        'customer_id' => 2,
        'prepared_by' => 1,
        'business_type_id' => 1,
        'company_id' => 1,
    ],
    [
        'code' => 'TRX004',
        'date' => '2024-02-04',
        'category' => 'Manual',
        'type' => 'Withdrawal',
        'amount' => 150.00,
        'auto_narration' => null,
        'manual_narration' => 'Withdrawal for office supplies',
        'notes' => 'Transaction for withdrawal of office supplies',
        'action' => 'Withdrawal',
        'tx_type_id' => 4,
        'transactionable_id' => null,
        'transactionable_type' => null,
        'supplier_id' => null,
        'customer_id' => null,
        'prepared_by' => 3,
        'business_type_id' => 2,
        'company_id' => 1,
    ],
    [
        'code' => 'TRX005',
        'date' => '2024-02-05',
        'category' => 'Auto',
        'type' => 'Deposit',
        'amount' => 600.00,
        'auto_narration' => 'Deposit from affiliate program',
        'manual_narration' => null,
        'notes' => 'Transaction for deposit from affiliate program',
        'action' => 'Deposit',
        'tx_type_id' => 1,
        'transactionable_id' => null,
        'transactionable_type' => null,
        'supplier_id' => null,
        'customer_id' => 3,
        'prepared_by' => 2,
        'business_type_id' => 1,
        'company_id' => 1,
    ]
];

        $now = \Carbon\Carbon::now();
        foreach ($transactions as $key => $transaction) {
            $transactions[$key]['created_at'] = $now;
            $transactions[$key]['updated_at'] = $now;
        }

        \App\Transaction::insert($transactions);
    }
}
