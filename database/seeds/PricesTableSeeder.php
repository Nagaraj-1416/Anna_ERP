<?php

use Illuminate\Database\Seeder;

class PricesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pricebooks = [
            [
                'price'=>5600.67,
                'range_start_from'=>10000,
                'range_end_to'=>50000,
                'product_id'=>1,
                'price_book_id'=> 1,
            ],
            [
                'price'=>67600.67,
                'range_start_from'=>100000,
                'range_end_to'=>500000,
                'product_id'=>1,
                'price_book_id'=> 1,
            ],
            [
                'price'=>59000.67,
                'range_start_from'=>100000,
                'range_end_to'=>500000,
                'product_id'=>1,
                'price_book_id'=> 1,
            ],
            [
                'price'=>56000.67,
                'range_start_from'=>100000,
                'range_end_to'=>500000,
                'product_id'=>1,
                'price_book_id'=> 1,
            ],
            [
                'price'=>560087.67,
                'range_start_from'=>20000,
                'range_end_to'=>90000,
                'product_id'=>1,
                'price_book_id'=> 1,
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($pricebooks as $key => $brand) {
            $pricebooks[$key]['created_at'] = $now;
            $pricebooks[$key]['updated_at'] = $now;
        }

        \App\Price::insert($pricebooks);
    }
}
