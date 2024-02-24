<?php

use Illuminate\Database\Seeder;

class StockshortageitemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stockshortageitems = [
            [
                'date' => '2024-02-01',
                'qty' => '50',
                'amount' => '1000.00',
                'rate' => '25.00',
                'product_id' => 1,
                'stock_id' => 1,
                'store_id' => 1,
                'stock_shortage_id' => 1,
            ],
            [
                'date' => '2024-02-01',
                'qty' => '40',
                'amount' => '800.00',
                'rate' => '20.00',
                'product_id' => 2,
                'stock_id' => 2,
                'store_id' => 2,
                'stock_shortage_id' => 2,
            ],
            [
                'date' => '2024-02-01',
                'qty' => '30',
                'amount' => '600.00',
                'rate' => '15.00',
                'product_id' => 3,
                'stock_id' => 3,
                'store_id' => 3,
                'stock_shortage_id' => 3,
            ],
            [
                'date' => '2024-02-01',
                'qty' => '60',
                'amount' => '1200.00',
                'rate' => '20.00',
                'product_id' => 1,
                'stock_id' => 4,
                'store_id' => 4,
                'stock_shortage_id' => 4,
            ],
            [
                'date' => '2024-02-01',
                'qty' => '45',
                'amount' => '900.00',
                'rate' => '20.00',
                'product_id' => 2,
                'stock_id' => 5,
                'store_id' => 5,
                'stock_shortage_id' => 5,
            ],
            [
                'date' => '2024-02-01',
                'qty' => '55',
                'amount' => '1100.00',
                'rate' => '20.00',
                'product_id' => 3,
                'stock_id' => 3,
                'store_id' => 6,
                'stock_shortage_id' => 6,
            ],
            [
                'date' => '2024-02-01',
                'qty' => '35',
                'amount' => '700.00',
                'rate' => '20.00',
                'product_id' => 1,
                'stock_id' => 4,
                'store_id' => 7,
                'stock_shortage_id' => 7,
            ],
            [
                'date' => '2024-02-01',
                'qty' => '65',
                'amount' => '1300.00',
                'rate' => '20.00',
                'product_id' => 2,
                'stock_id' => 4,
                'store_id' => 8,
                'stock_shortage_id' => 8,
            ],
            [
                'date' => '2024-02-01',
                'qty' => '47',
                'amount' => '940.00',
                'rate' => '20.00',
                'product_id' => 3,
                'stock_id' => 5,
                'store_id' => 9,
                'stock_shortage_id' => 9,
            ],
            [
                'date' => '2024-02-01',
                'qty' => '70',
                'amount' => '1400.00',
                'rate' => '20.00',
                'product_id' => 1,
                'stock_id' => 3,
                'store_id' => 10,
                'stock_shortage_id' => 10,
            ],
        ];
            
$now = \Carbon\Carbon::now();
foreach ($stockshortageitems as $key => $stockshortageitem) {
    $stockshortageitems[$key]['created_at'] = $now;
    $stockshortageitems[$key]['updated_at'] = $now;
}
\App\StockShortageItem::insert($stockshortageitems);
    }
}