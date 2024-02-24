<?php

use Illuminate\Database\Seeder;

class DailystockitemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dailystockitems = [
            [
                'daily_stock_id' => 1,
                'product_id' => 1,
                'store_id' => 1,
                'available_qty' => '10.00',
                'default_qty' => '25.00',
                'required_qty' => '12.00',
                'issued_qty' => '8.00',
                'pending_qty' => '10.00',
            ],
            [
                'daily_stock_id' => 2,
                'product_id' => 2,
                'store_id' => 2,
                'available_qty' => '15.00',
                'default_qty' => '30.00',
                'required_qty' => '10.00',
                'issued_qty' => '5.00',
                'pending_qty' => '10.00',
            ],
            [
                'daily_stock_id' => 3,
                'product_id' => 3,
                'store_id' => 3,
                'available_qty' => '20.00',
                'default_qty' => '40.00',
                'required_qty' => '15.00',
                'issued_qty' => '10.00',
                'pending_qty' => '5.00',
            ],
            [
                'daily_stock_id' => 4,
                'product_id' => 1,
                'store_id' => 1,
                'available_qty' => '12.00',
                'default_qty' => '20.00',
                'required_qty' => '8.00',
                'issued_qty' => '5.00',
                'pending_qty' => '7.00',
            ],
            [
                'daily_stock_id' => 5,
                'product_id' => 2,
                'store_id' => 2,
                'available_qty' => '18.00',
                'default_qty' => '35.00',
                'required_qty' => '20.00',
                'issued_qty' => '15.00',
                'pending_qty' => '5.00',
            ],
            [
                'daily_stock_id' => 6,
                'product_id' => 3,
                'store_id' => 3,
                'available_qty' => '25.00',
                'default_qty' => '50.00',
                'required_qty' => '30.00',
                'issued_qty' => '20.00',
                'pending_qty' => '10.00',
            ],
            [
                'daily_stock_id' => 7,
                'product_id' => 1,
                'store_id' => 1,
                'available_qty' => '30.00',
                'default_qty' => '60.00',
                'required_qty' => '40.00',
                'issued_qty' => '25.00',
                'pending_qty' => '15.00',
            ],
            [
                'daily_stock_id' => 8,
                'product_id' => 2,
                'store_id' => 2,
                'available_qty' => '22.00',
                'default_qty' => '45.00',
                'required_qty' => '25.00',
                'issued_qty' => '18.00',
                'pending_qty' => '7.00',
            ],
            [
                'daily_stock_id' => 9,
                'product_id' => 3,
                'store_id' => 3,
                'available_qty' => '28.00',
                'default_qty' => '55.00',
                'required_qty' => '35.00',
                'issued_qty' => '20.00',
                'pending_qty' => '15.00',
            ],
            [
                'daily_stock_id' => 10,
                'product_id' => 1,
                'store_id' => 1,
                'available_qty' => '35.00',
                'default_qty' => '70.00',
                'required_qty' => '45.00',
                'issued_qty' => '30.00',
                'pending_qty' => '15.00',
            ],
        ];
        
            $now = \Carbon\Carbon::now();
        foreach ($dailystockitems as $key => $dailystockitem) {
            $dailystockitems[$key]['created_at'] = $now;
            $dailystockitems[$key]['updated_at'] = $now;
        }
        \App\DailyStockItem::insert($dailystockitems);
    }
}