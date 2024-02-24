<?php

use Illuminate\Database\Seeder;

class DailystocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $daily_stocks = [
            [
                'pre_allocation_id' => 1,
                'sales_location' => 'Van',
                'sales_location_id' => 1,
                'route_id' => 1,
                'rep_id' => 1,
                'store_id' => 1,
                'prepared_by' => 1,
                'notes' => 'stock 1',
                'status' => 'Allocated',
                'company_id' => 1,
            ],
            [
                'pre_allocation_id' => 2,
                'sales_location' => 'Shop',
                'sales_location_id' => 2,
                'route_id' => 2,
                'rep_id' => 2,
                'store_id' => 2,
                'prepared_by' => 1,
                'notes' => 'stock 2',
                'status' => 'Pending',
                'company_id' => 1,
            ],
            [
                'pre_allocation_id' => 3,
                'sales_location' => 'Other',
                'sales_location_id' => 3,
                'route_id' => 3,
                'rep_id' => 3,
                'store_id' => 3,
                'prepared_by' => 1,
                'notes' => 'stock 3',
                'status' => 'Canceled',
                'company_id' => 1,
            ],
            [
                'pre_allocation_id' => 4,
                'sales_location' => 'Van',
                'sales_location_id' => 1,
                'route_id' => 1,
                'rep_id' => 1,
                'store_id' => 1,
                'prepared_by' => 1,
                'notes' => 'stock 4',
                'status' => 'Allocated',
                'company_id' => 1,
            ],
            [
                'pre_allocation_id' => 1,
                'sales_location' => 'Shop',
                'sales_location_id' => 2,
                'route_id' => 2,
                'rep_id' => 2,
                'store_id' => 2,
                'prepared_by' => 1,
                'notes' => 'stock 5',
                'status' => 'Pending',
                'company_id' => 1,
            ],
            [
                'pre_allocation_id' => 3,
                'sales_location' => 'Other',
                'sales_location_id' => 3,
                'route_id' => 3,
                'rep_id' => 3,
                'store_id' => 3,
                'prepared_by' => 1,
                'notes' => 'stock 6',
                'status' => 'Canceled',
                'company_id' => 1,
            ],
            [
                'pre_allocation_id' => 4,
                'sales_location' => 'Van',
                'sales_location_id' => 1,
                'route_id' => 1,
                'rep_id' => 1,
                'store_id' => 1,
                'prepared_by' => 1,
                'notes' => 'stock 7',
                'status' => 'Allocated',
                'company_id' => 1,
            ],
            [
                'pre_allocation_id' => 1,
                'sales_location' => 'Shop',
                'sales_location_id' => 2,
                'route_id' => 2,
                'rep_id' => 2,
                'store_id' => 2,
                'prepared_by' => 1,
                'notes' => 'stock 8',
                'status' => 'Pending',
                'company_id' => 1,
            ],
            [
                'pre_allocation_id' => 2,
                'sales_location' => 'Other',
                'sales_location_id' => 3,
                'route_id' => 3,
                'rep_id' => 3,
                'store_id' => 3,
                'prepared_by' => 1,
                'notes' => 'stock 9',
                'status' => 'Canceled',
                'company_id' => 1,
            ],
            [
                'pre_allocation_id' => 3,
                'sales_location' => 'Van',
                'sales_location_id' => 1,
                'route_id' => 1,
                'rep_id' => 1,
                'store_id' => 1,
                'prepared_by' => 1,
                'notes' => 'stock 10',
                'status' => 'Allocated',
                'company_id' => 1,
            ],
        ];
            $now = \Carbon\Carbon::now();
            foreach ($daily_stocks as $key => $daily_stock) {
                $daily_stocks[$key]['created_at'] = $now;
                $daily_stocks[$key]['updated_at'] = $now;
            }
            \App\DailyStock::insert($daily_stocks);   
    }
}