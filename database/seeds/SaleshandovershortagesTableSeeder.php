<?php

use Illuminate\Database\Seeder;

class SaleshandovershortagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $saleshandovershortages = [
            [
                'daily_sale_id' => 1,
                'sales_handover_id' => 1,
                'rep_id' => 1,
                'date' => '2024-02-21',
                'amount' => 1000.00,
                'submitted_by' => 1,
                'approved_by' => 1,
                'approved_at' => '2024-02-22',
                'rejected_by' => null,
                'rejected_at' => null,
                'status' => 'Approved',
            ],
            [
                'daily_sale_id' => 2,
                'sales_handover_id' => 2,
                'rep_id' => 2,
                'date' => '2024-02-20',
                'amount' => 1200.00,
                'submitted_by' => 1,
                'approved_by' => null,
                'approved_at' => null,
                'rejected_by' => 1,
                'rejected_at' => '2024-02-22',
                'status' => 'Rejected',
            ],
            [
                'daily_sale_id' => 3,
                'sales_handover_id' => 3,
                'rep_id' => 3,
                'date' => '2024-02-19',
                'amount' => 800.00,
                'submitted_by' => 1,
                'approved_by' => 1,
                'approved_at' => '2024-02-22',
                'rejected_by' => null,
                'rejected_at' => null,
                'status' => 'Approved',
            ],
            [
                'daily_sale_id' => 4,
                'sales_handover_id' => 4,
                'rep_id' => 4,
                'date' => '2024-02-18',
                'amount' => 1500.00,
                'submitted_by' => 1,
                'approved_by' => null,
                'approved_at' => null,
                'rejected_by' => 1,
                'rejected_at' => '2024-02-22',
                'status' => 'Rejected',
            ],
            [
                'daily_sale_id' => 1,
                'sales_handover_id' => 5,
                'rep_id' => 5,
                'date' => '2024-02-17',
                'amount' => 900.00,
                'submitted_by' => 1,
                'approved_by' => 1,
                'approved_at' => '2024-02-22',
                'rejected_by' => null,
                'rejected_at' => null,
                'status' => 'Approved',
            ],
            [
                'daily_sale_id' => 2,
                'sales_handover_id' => 3,
                'rep_id' => 2,
                'date' => '2024-02-16',
                'amount' => 1100.00,
                'submitted_by' => 1,
                'approved_by' => null,
                'approved_at' => null,
                'rejected_by' => 1,
                'rejected_at' => '2024-02-22',
                'status' => 'Rejected',
            ],
            [
                'daily_sale_id' => 3,
                'sales_handover_id' => 3,
                'rep_id' => 3,
                'date' => '2024-02-15',
                'amount' => 1300.00,
                'submitted_by' => 1,
                'approved_by' => 1,
                'approved_at' => '2024-02-22',
                'rejected_by' => null,
                'rejected_at' => null,
                'status' => 'Approved',
            ],
            [
                'daily_sale_id' => 4,
                'sales_handover_id' => 1,
                'rep_id' => 1,
                'date' => '2024-02-14',
                'amount' => 1000.00,
                'submitted_by' => 1,
                'approved_by' => null,
                'approved_at' => null,
                'rejected_by' => 1,
                'rejected_at' => '2024-02-22',
                'status' => 'Rejected',
            ],
            [
                'daily_sale_id' => 4,
                'sales_handover_id' => 6,
                'rep_id' => 5,
                'date' => '2024-02-13',
                'amount' => 950.00,
                'submitted_by' => 1,
                'approved_by' => 1,
                'approved_at' => '2024-02-22',
                'rejected_by' => null,
                'rejected_at' => null,
                'status' => 'Approved',
            ],
            [
                'daily_sale_id' => 1,
                'sales_handover_id' => 1,
                'rep_id' => 1,
                'date' => '2024-02-12',
                'amount' => 1400.00,
                'submitted_by' => 1,
                'approved_by' => null,
                'approved_at' => null,
                'rejected_by' => 1,
                'rejected_at' => '2024-02-22',
                'status' => 'Rejected',
            ],
        ];
            $now = \Carbon\Carbon::now();
        foreach ($saleshandovershortages as $key => $saleshandovershortage) {
            $saleshandovershortages[$key]['created_at'] = $now;
            $saleshandovershortages[$key]['updated_at'] = $now;
        }
        \App\SalesHandoverShortage::insert($saleshandovershortages);
    }
}