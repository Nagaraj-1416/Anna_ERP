<?php

use Illuminate\Database\Seeder;

class StockexcessitemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stockexcessitems = [
            [
                'date' => '2024-02-22',
                'qty' => '60',
                'rate' => '100',
                'amount' => '1000.00',
                'product_id' => 1,
                'stock_id' => 1,
                'store_id' => 1,
                'stock_excess_id' => 1,
            ],
            [
                'date' => '2024-03-10',
                'qty' => '70',
                'rate' => '120',
                'amount' => '1500.00',
                'product_id' => 2,
                'stock_id' => 2,
                'store_id' => 1,
                'stock_excess_id' => 2,
            ],
            [
                'date' => '2024-04-15',
                'qty' => '80',
                'rate' => '110',
                'amount' => '1800.00',
                'product_id' => 3,
                'stock_id' => 1,
                'store_id' => 2,
                'stock_excess_id' => 3,
            ],
            [
                'date' => '2024-05-20',
                'qty' => '90',
                'rate' => '130',
                'amount' => '2000.00',
                'product_id' => 1,
                'stock_id' => 2,
                'store_id' => 2,
                'stock_excess_id' => 4,
            ],
            [
                'date' => '2024-06-25',
                'qty' => '100',
                'rate' => '140',
                'amount' => '2500.00',
                'product_id' => 2,
                'stock_id' => 1,
                'store_id' => 1,
                'stock_excess_id' => 5,
            ],
            [
                'date' => '2024-07-12',
                'qty' => '110',
                'rate' => '125',
                'amount' => '2700.00',
                'product_id' => 3,
                'stock_id' => 2,
                'store_id' => 1,
                'stock_excess_id' => 6,
            ],
            [
                'date' => '2024-08-18',
                'qty' => '120',
                'rate' => '135',
                'amount' => '3000.00',
                'product_id' => 1,
                'stock_id' => 1,
                'store_id' => 2,
                'stock_excess_id' => 7,
            ],
            [
                'date' => '2024-09-22',
                'qty' => '130',
                'rate' => '145',
                'amount' => '3200.00',
                'product_id' => 2,
                'stock_id' => 2,
                'store_id' => 2,
                'stock_excess_id' => 8,
            ],
            [
                'date' => '2024-10-28',
                'qty' => '140',
                'rate' => '130',
                'amount' => '3500.00',
                'product_id' => 3,
                'stock_id' => 1,
                'store_id' => 1,
                'stock_excess_id' => 9,
            ],
            [
                'date' => '2024-11-30',
                'qty' => '150',
                'rate' => '140',
                'amount' => '3800.00',
                'product_id' => 1,
                'stock_id' => 2,
                'store_id' => 1,
                'stock_excess_id' => 10,
            ],
        ];
        $now = \Carbon\Carbon::now();
        foreach ($stockexcessitems as $key => $stockexcessitem) {
            $stockexcessitems[$key]['created_at'] = $now;
            $stockexcessitems[$key]['updated_at'] = $now;
        }

        \App\StockExcessItem::insert($stockexcessitems);
    }
}