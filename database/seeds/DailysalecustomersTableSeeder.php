<?php

use Illuminate\Database\Seeder;

class DailysalecustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dailysalecustomers=[
            [
                'daily_sale_id' => 1,
                'customer_id' => 1,
                'notes' => 'null',
                'is_visited' => 'no',
                'reason' => 'purchased in another store',
                'gps_lat' =>'',
                'gps_long' => '',
                'distance' => 10,
                'added_stage' => 'Later',
            ],
            [
                'daily_sale_id' => 2,
                'customer_id' => 1,
                'notes' => 'null',
                'is_visited' => 'yes',
                'reason' => 'null',
                'gps_lat' =>'',
                'gps_long' => '',
                'distance' => 15,
                'added_stage' => 'First',
            ],
            [
                'daily_sale_id' => 3,
                'customer_id' => 1,
                'notes' => 'null',
                'is_visited' => 'no',
                'reason' => 'purchased in another store',
                'gps_lat' =>'',
                'gps_long' => '',
                'distance' => 25,
                'added_stage' => 'First',
            ],
            [
                'daily_sale_id' => 1,
                'customer_id' => 1,
                'notes' => 'null',
                'is_visited' => 'yes',
                'reason' => 'purchased',
                'gps_lat' =>'',
                'gps_long' => '',
                'distance' => 35,
                'added_stage' => 'later',
            ],
            [
                'daily_sale_id' => 2,
                'customer_id' => 1,
                'notes' => 'null',
                'is_visited' => 'yes',
                'reason' => 'purchased',
                'gps_lat' =>'',
                'gps_long' => '',
                'distance' => 10,
                'added_stage' => 'First',
            ],
        ];
        $now = \Carbon\Carbon::now();
        foreach ($dailysalecustomers as $key => $dailysalecustomer) {
            $dailysalecustomers[$key]['created_at'] = $now;
            $dailysalecustomers[$key]['updated_at'] = $now;
        }
        \App\DailySaleCustomer::insert($dailysalecustomers);
    }
}