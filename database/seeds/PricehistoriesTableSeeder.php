<?php

use Illuminate\Database\Seeder;

class PricehistoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pricehistory = [
            [
                'date' => \Carbon\Carbon::now(),
                'updated_by' => 1,
                'price_book_id' => 1,
            ],
            [
                'date' => \Carbon\Carbon::now(),
                'updated_by' => 1,
                'price_book_id' => 2,
            ],
            [
                'date' => \Carbon\Carbon::now(),
                'updated_by' => 1,
                'price_book_id' => 3,
            ],
            [
                'date' => \Carbon\Carbon::now(),
                'updated_by' => 1,
                'price_book_id' => 4,
            ],
            [
                'date' => \Carbon\Carbon::now(),
                'updated_by' => 1,
                'price_book_id' => 5,
            ],
            [
                'date' => \Carbon\Carbon::now(),
                'updated_by' => 1,
                'price_book_id' => 1,
            ],
            [
                'date' => \Carbon\Carbon::now(),
                'updated_by' => 1,
                'price_book_id' => 2,
            ],
            [
                'date' => \Carbon\Carbon::now(),
                'updated_by' => 1,
                'price_book_id' => 3,
            ],
            [
                'date' => \Carbon\Carbon::now(),
                'updated_by' => 1,
                'price_book_id' => 4,
            ],
            [
                'date' => \Carbon\Carbon::now(),
                'updated_by' => 1,
                'price_book_id' => 5,
            ],
        ];
            $now = \Carbon\Carbon::now();
        foreach ($pricehistory as $key => $add) {
            $pricehistory[$key]['created_at'] = $now;
            $pricehistory[$key]['updated_at'] = $now;
        }

        \App\PriceHistory::insert($pricehistory);
    }
}