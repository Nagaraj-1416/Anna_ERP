<?php

use Illuminate\Database\Seeder;

class DailysalecreditordersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dailysalescreditorders = [
            [
                'daily_sale_id' => 1,
                'customer_id' => 1,
                'sales_order_id' => 5,
                'added_stage' => 'First',
            ],
            [
                'daily_sale_id' => 2,
                'customer_id' => 1,
                'sales_order_id' => 4,
                'added_stage' => 'Later',
            ],
            [
                'daily_sale_id' => 3,
                'customer_id' => 1,
                'sales_order_id' => 3,
                'added_stage' => 'First',
            ],
            [
                'daily_sale_id' => 4,
                'customer_id' => 1,
                'sales_order_id' => 2,
                'added_stage' => 'Later',
            ],
            [
                'daily_sale_id' => 2,
                'customer_id' => 1,
                'sales_order_id' => 1,
                'added_stage' => 'First',
            ],
            [
                'daily_sale_id' => 3,
                'customer_id' => 1,
                'sales_order_id' => 1,
                'added_stage' => 'Later',
            ],
            [
                'daily_sale_id' => 2,
                'customer_id' => 1,
                'sales_order_id' => 1,
                'added_stage' => 'First',
            ],
            [
                'daily_sale_id' => 3,
                'customer_id' => 1,
                'sales_order_id' => 2,
                'added_stage' => 'Later',
            ],
            [
                'daily_sale_id' => 2,
                'customer_id' => 1,
                'sales_order_id' => 3,
                'added_stage' => 'First',
            ],
            [
                'daily_sale_id' => 1,
                'customer_id' => 1,
                'sales_order_id' => 4,
                'added_stage' => 'Later',
            ],
        ];
        $now = \Carbon\Carbon::now();
        foreach ($dailysalescreditorders as $key => $dailysalescreditorder) {
            $dailysalescreditorders[$key]['created_at'] = $now;
            $dailysalescreditorders[$key]['updated_at'] = $now;
        }
        \App\DailySaleCreditOrder::insert($dailysalescreditorders);
    }
}