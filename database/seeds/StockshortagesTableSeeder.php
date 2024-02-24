<?php

use Illuminate\Database\Seeder;

class StockshortagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stockshortages = [
            [
                'date' => '2024-02-01',
                'amount' => '1000.00',
                'status' => 'Drafted',
                'notes' => 'stock shortage entry 1',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-01',
                'approved_by' => 1,
                'approved_on' => '2024-02-01',
                'route_id' => 1,
                'rep_id' => 1,
                'staff_id' => 1,
                'daily_sale_id' => 1,
                'sales_handover_id' => 1,
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-02',
                'amount' => '1500.00',
                'status' => 'Drafted',
                'notes' => 'stock shortage entry 2',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-02',
                'approved_by' => 1,
                'approved_on' => '2024-02-02',
                'route_id' => 2,
                'rep_id' => 2,
                'staff_id' => 1,
                'daily_sale_id' => 2,
                'sales_handover_id' => 2,
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-03',
                'amount' => '800.00',
                'status' => 'Drafted',
                'notes' => 'stock shortage entry 3',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-03',
                'approved_by' => 1,
                'approved_on' => '2024-02-03',
                'route_id' => 3,
                'rep_id' => 3,
                'staff_id' => 1,
                'daily_sale_id' => 3,
                'sales_handover_id' => 3,
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-04',
                'amount' => '1200.00',
                'status' => 'Drafted',
                'notes' => 'stock shortage entry 4',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-04',
                'approved_by' => 1,
                'approved_on' => '2024-02-04',
                'route_id' => 1,
                'rep_id' => 4,
                'staff_id' => 1,
                'daily_sale_id' => 4,
                'sales_handover_id' => 1,
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-05',
                'amount' => '900.00',
                'status' => 'Drafted',
                'notes' => 'stock shortage entry 5',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-05',
                'approved_by' => 1,
                'approved_on' => '2024-02-05',
                'route_id' => 2,
                'rep_id' => 1,
                'staff_id' => 1,
                'daily_sale_id' => 3,
                'sales_handover_id' => 2,
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-06',
                'amount' => '1100.00',
                'status' => 'Drafted',
                'notes' => 'stock shortage entry 6',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-06',
                'approved_by' => 1,
                'approved_on' => '2024-02-06',
                'route_id' => 3,
                'rep_id' => 2,
                'staff_id' => 1,
                'daily_sale_id' => 4,
                'sales_handover_id' => 3,
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-07',
                'amount' => '700.00',
                'status' => 'Drafted',
                'notes' => 'stock shortage entry 7',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-07',
                'approved_by' => 1,
                'approved_on' => '2024-02-07',
                'route_id' => 1,
                'rep_id' => 3,
                'staff_id' => 1,
                'daily_sale_id' => 2,
                'sales_handover_id' => 1,
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-08',
                'amount' => '1300.00',
                'status' => 'Drafted',
                'notes' => 'stock shortage entry 8',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-08',
                'approved_by' => 1,
                'approved_on' => '2024-02-08',
                'route_id' => 2,
                'rep_id' => 4,
                'staff_id' => 1,
                'daily_sale_id' => 3,
                'sales_handover_id' => 2,
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-09',
                'amount' => '950.00',
                'status' => 'Drafted',
                'notes' => 'stock shortage entry 9',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-09',
                'approved_by' => 1,
                'approved_on' => '2024-02-09',
                'route_id' => 3,
                'rep_id' => 1,
                'staff_id' => 1,
                'daily_sale_id' => 1,
                'sales_handover_id' => 3,
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-10',
                'amount' => '1600.00',
                'status' => 'Drafted',
                'notes' => 'stock shortage entry 10',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-10',
                'approved_by' => 1,
                'approved_on' => '2024-02-10',
                'route_id' => 1,
                'rep_id' => 2,
                'staff_id' => 1,
                'daily_sale_id' => 1,
                'sales_handover_id' => 1,
                'company_id' => 1,
            ],
        ];
            
$now = \Carbon\Carbon::now();
foreach ($stockshortages as $key => $stockshortage) {
    $stockshortages[$key]['created_at'] = $now;
    $stockshortages[$key]['updated_at'] = $now;
}
\App\StockShortage::insert($stockshortages);
    }
}