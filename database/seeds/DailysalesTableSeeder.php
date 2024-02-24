<?php

use Illuminate\Database\Seeder;

class DailysalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $daily_sales = [
            [
                'code' => 'DS0001',
                'day_type' => 'Single',
                'from_date' => '2024-02-01',
                'to_date' => '2024-02-01',
                'days' => 'Monday',
                'sales_location' => 'Van',
                'sales_location_id' => 1,
                'vehicle_id' => 1,
                'rep_id' => 1,
                'route_id' => 1,
                'notes' => 'Sales for Monday route',
                'status' => 'Draft',
                'prepared_by' => 1,
                'company_id' => 1,
                'is_logged_in' => 'Yes',
                'is_logged_out' => 'No',
                'logged_in_at' => null,
                'logged_out_at' => null,
                'start_time' => null,
                'end_time' => null,
                'nxt_day_al_route' => 1,
            ],
            [
                'code' => 'DS0002',
                'day_type' => 'Single',
                'from_date' => '2024-02-02',
                'to_date' => '2024-02-02',
                'days' => 'Tuesday',
                'sales_location' => 'Shop',
                'sales_location_id' => 2,
                'vehicle_id' => 2,
                'rep_id' => 2,
                'route_id' => 2,
                'notes' => 'Sales for Tuesday route',
                'status' => 'Draft',
                'prepared_by' => 2,
                'company_id' => 2,
                'is_logged_in' => 'Yes',
                'is_logged_out' => 'No',
                'logged_in_at' => null,
                'logged_out_at' => null,
                'start_time' => null,
                'end_time' => null,
                'nxt_day_al_route' => 2,
            ],
            [
                'code' => 'DS0003',
                'day_type' => 'Single',
                'from_date' => '2024-02-03',
                'to_date' => '2024-02-03',
                'days' => 'Wednesday',
                'sales_location' => 'Shop',
                'sales_location_id' => 3,
                'vehicle_id' => 3,
                'rep_id' => 3,
                'route_id' => 3,
                'notes' => 'Sales for Wednesday route',
                'status' => 'Draft',
                'prepared_by' => 3,
                'company_id' => 3,
                'is_logged_in' => 'Yes',
                'is_logged_out' => 'No',
                'logged_in_at' => null,
                'logged_out_at' => null,
                'start_time' => null,
                'end_time' => null,
                'nxt_day_al_route' => 3,
            ],
            [
                'code' => 'DS0004',
                'day_type' => 'Single',
                'from_date' => '2024-02-04',
                'to_date' => '2024-02-04',
                'days' => 'Thursday',
                'sales_location' => 'Van',
                'sales_location_id' => 2,
                'vehicle_id' => 4,
                'rep_id' => 4,
                'route_id' => 4,
                'notes' => 'Sales for Thursday route',
                'status' => 'Draft',
                'prepared_by' => 4,
                'company_id' => 4,
                'is_logged_in' => 'Yes',
                'is_logged_out' => 'No',
                'logged_in_at' => null,
                'logged_out_at' => null,
                'start_time' => null,
                'end_time' => null,
                'nxt_day_al_route' => 4,
            ],
            [
                'code' => 'DS0005',
                'day_type' => 'Single',
                'from_date' => '2024-02-05',
                'to_date' => '2024-02-05',
                'days' => 'Friday',
                'sales_location' => 'Shop',
                'sales_location_id' => 3,
                'vehicle_id' => 4,
                'rep_id' => 5,
                'route_id' => 5,
                'notes' => 'Sales for Friday route',
                'status' => 'Draft',
                'prepared_by' => 5,
                'company_id' => 4,
                'is_logged_in' => 'Yes',
                'is_logged_out' => 'No',
                'logged_in_at' => null,
                'logged_out_at' => null,
                'start_time' => null,
                'end_time' => null,
                'nxt_day_al_route' => 5,
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($daily_sales as $key => $daily_sale) {
            $daily_sales[$key]['created_at'] = $now;
            $daily_sales[$key]['updated_at'] = $now;
        }

        \App\DailySale::insert($daily_sales);
    }
}
