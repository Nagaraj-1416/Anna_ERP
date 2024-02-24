<?php

use Illuminate\Database\Seeder;

class StocktransferitemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stocktransferitems = [
            [
                'date' => \Carbon\Carbon::now(),
                'transfer_id' => 1,
                'qty' => '250',
                'stock_id' => 1,
                'product_id' => 1,
            ],
            [
                'date' => \Carbon\Carbon::now(),
                'transfer_id' => 2,
                'qty' => '200',
                'stock_id' => 2,
                'product_id' => 2,
            ],
            [
                'date' => \Carbon\Carbon::now(),
                'transfer_id' => 3,
                'qty' => '150',
                'stock_id' => 3,
                'product_id' => 3,
            ],
            [
                'date' => \Carbon\Carbon::now(),
                'transfer_id' => 2,
                'qty' => '180',
                'stock_id' => 1,
                'product_id' => 1,
            ],
            [
                'date' => \Carbon\Carbon::now(),
                'transfer_id' => 4,
                'qty' => '220',
                'stock_id' => 2,
                'product_id' => 2,
            ],
            [
                'date' => \Carbon\Carbon::now(),
                'transfer_id' => 1,
                'qty' => '170',
                'stock_id' => 3,
                'product_id' => 3,
            ],
            [
                'date' => \Carbon\Carbon::now(),
                'transfer_id' => 2,
                'qty' => '200',
                'stock_id' => 1,
                'product_id' => 1,
            ],
            [
                'date' => \Carbon\Carbon::now(),
                'transfer_id' => 3,
                'qty' => '240',
                'stock_id' => 2,
                'product_id' => 2,
            ],
            [
                'date' => \Carbon\Carbon::now(),
                'transfer_id' => 2,
                'qty' => '190',
                'stock_id' => 3,
                'product_id' => 3,
            ],
            [
                'date' => \Carbon\Carbon::now(),
                'transfer_id' => 1,
                'qty' => '210',
                'stock_id' => 1,
                'product_id' => 1,
            ],
        ];
            $now = \Carbon\Carbon::now();
            foreach ($stocktransferitems as $key => $stocktransferitem) {
                $stocktransferitems[$key]['created_at'] = $now;
                $stocktransferitems[$key]['updated_at'] = $now;
            }
    
            \App\StockTransferItem::insert($stocktransferitems);
    }
}