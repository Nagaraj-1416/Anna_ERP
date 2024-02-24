<?php

use Illuminate\Database\Seeder;

class PricehistoryitemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pricehistoryitems = [
            [
                'price' => '250.00',
                'range_start_from' => '1919',
                'range_end_to' => '1947',
                'product_id' => 1,
                'price_book_id' => 1,
                'price_history_id' => 1
            ],
            [
                'price' => '300.00',
                'range_start_from' => '1950',
                'range_end_to' => '1960',
                'product_id' => 2,
                'price_book_id' => 2,
                'price_history_id' => 2
            ],
            [
                'price' => '350.00',
                'range_start_from' => '1961',
                'range_end_to' => '1970',
                'product_id' => 3,
                'price_book_id' => 3,
                'price_history_id' => 3
            ],
            [
                'price' => '400.00',
                'range_start_from' => '1971',
                'range_end_to' => '1980',
                'product_id' => 1,
                'price_book_id' => 4,
                'price_history_id' => 4
            ],
            [
                'price' => '450.00',
                'range_start_from' => '1981',
                'range_end_to' => '1990',
                'product_id' => 2,
                'price_book_id' => 5,
                'price_history_id' => 5
            ],
            [
                'price' => '500.00',
                'range_start_from' => '1991',
                'range_end_to' => '2000',
                'product_id' => 3,
                'price_book_id' => 1,
                'price_history_id' => 6
            ],
            [
                'price' => '550.00',
                'range_start_from' => '2001',
                'range_end_to' => '2010',
                'product_id' => 1,
                'price_book_id' => 2,
                'price_history_id' => 7
            ],
            [
                'price' => '600.00',
                'range_start_from' => '2011',
                'range_end_to' => '2020',
                'product_id' => 2,
                'price_book_id' => 3,
                'price_history_id' => 8
            ],
            [
                'price' => '650.00',
                'range_start_from' => '2021',
                'range_end_to' => '2030',
                'product_id' => 3,
                'price_book_id' => 4,
                'price_history_id' => 9
            ],
            [
                'price' => '700.00',
                'range_start_from' => '2031',
                'range_end_to' => '2040',
                'product_id' => 1,
                'price_book_id' => 5,
                'price_history_id' => 10
            ],
        ];
            $now = \Carbon\Carbon::now();
        foreach ($pricehistoryitems as $key => $pricehistoryitem) {
            $pricehistoryitems[$key]['created_at'] = $now;
            $pricehistoryitems[$key]['updated_at'] = $now;
        }

        \App\PriceHistoryItem::insert($pricehistoryitems);
    }
}