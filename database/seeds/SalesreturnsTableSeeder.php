<?php

use Illuminate\Database\Seeder;

class SalesreturnsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $salesreturns = [
            [
                'code' => 'SR0001',
                'date' => '2024-02-01',
                'notes' => 'Sales return 1',
                'status' => 'Open',
                'is_printed' => 'Yes',
                'daily_sale_id' => 1,
                'route_id' => 1,
                'rep_id' => 1,
                'customer_id' => 1,
                'prepared_by' => 1,
                'company_id' => 1,
            ],
            [
                'code' => 'SR0002',
                'date' => '2024-02-02',
                'notes' => 'Sales return 2',
                'status' => 'Processed',
                'is_printed' => 'No',
                'daily_sale_id' => 2,
                'route_id' => 1,
                'rep_id' => 2,
                'customer_id' => 1,
                'prepared_by' => 1,
                'company_id' => 1,
            ],
            [
                'code' => 'SR0003',
                'date' => '2024-02-03',
                'notes' => 'Sales return 3',
                'status' => 'Closed',
                'is_printed' => 'Yes',
                'daily_sale_id' => 3,
                'route_id' => 1,
                'rep_id' => 1,
                'customer_id' => 1,
                'prepared_by' => 1,
                'company_id' => 1,
            ],
            [
                'code' => 'SR0004',
                'date' => '2024-02-04',
                'notes' => 'Sales return 4',
                'status' => 'Open',
                'is_printed' => 'Yes',
                'daily_sale_id' => 1,
                'route_id' => 1,
                'rep_id' => 2,
                'customer_id' => 1,
                'prepared_by' => 1,
                'company_id' => 1,
            ],
            [
                'code' => 'SR0005',
                'date' => '2024-02-05',
                'notes' => 'Sales return 5',
                'status' => 'Processed',
                'is_printed' => 'No',
                'daily_sale_id' => 3,
                'route_id' => 1,
                'rep_id' => 5,
                'customer_id' => 1,
                'prepared_by' => 1,
                'company_id' => 1,
            ],
            [
                'code' => 'SR0006',
                'date' => '2024-02-06',
                'notes' => 'Sales return 6',
                'status' => 'Closed',
                'is_printed' => 'Yes',
                'daily_sale_id' => 1,
                'route_id' => 1,
                'rep_id' => 1,
                'customer_id' => 1,
                'prepared_by' => 1,
                'company_id' => 1,
            ],
            [
                'code' => 'SR0007',
                'date' => '2024-02-07',
                'notes' => 'Sales return 7',
                'status' => 'Open',
                'is_printed' => 'Yes',
                'daily_sale_id' => 4,
                'route_id' => 1,
                'rep_id' => 2,
                'customer_id' => 1,
                'prepared_by' => 1,
                'company_id' => 1,
            ],
            [
                'code' => 'SR0008',
                'date' => '2024-02-08',
                'notes' => 'Sales return 8',
                'status' => 'Processed',
                'is_printed' => 'No',
                'daily_sale_id' => 2,
                'route_id' => 4,
                'rep_id' => 2,
                'customer_id' => 1,
                'prepared_by' => 1,
                'company_id' => 1,
            ],
            [
                'code' => 'SR0009',
                'date' => '2024-02-09',
                'notes' => 'Sales return 9',
                'status' => 'Closed',
                'is_printed' => 'Yes',
                'daily_sale_id' => 3,
                'route_id' => 4,
                'rep_id' => 3,
                'customer_id' => 1,
                'prepared_by' => 1,
                'company_id' => 1,
            ],
            [
                'code' => 'SR0010',
                'date' => '2024-02-10',
                'notes' => 'Sales return 10',
                'status' => 'Open',
                'is_printed' => 'Yes',
                'daily_sale_id' => 2,
                'route_id' => 3,
                'rep_id' => 1,
                'customer_id' => 1,
                'prepared_by' => 1,
                'company_id' => 1,
            ],
        ];
        
        $now = \Carbon\Carbon::now();
        foreach ($salesreturns as $key => $salesreturn) {
            $salesreturns[$key]['created_at'] = $now;
            $salesreturns[$key]['updated_at'] = $now;
        }

        \App\SalesReturn::insert($salesreturns);
    }
}