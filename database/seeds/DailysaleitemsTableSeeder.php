<?php

use Illuminate\Database\Seeder;

class DailysaleitemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dailysaleitems =[
            [
                'daily_sale_id' => 1 ,
                'product_id' => 1,
                'store_id' => 1,
                'quantity' => '10',
                'sold_qty' => '5',
                'replaced_qty' =>'2',
                'restored_qty' => '3',
                'returned_qty' => '5',
                'shortage_qty' => '1',
                'damaged_qty' => '1',
                'excess_qty' => '0',
                'notes' => 'dailysales Item1',
                'added_stage' => 'First',
            ],
            [
                'daily_sale_id' => 1 ,
                'product_id' => 1,
                'store_id' => 1,
                'quantity' => '20',
                'sold_qty' => '7',
                'replaced_qty' =>'4',
                'restored_qty' => '3',
                'returned_qty' => '0',
                'shortage_qty' => '2',
                'damaged_qty' => '1',
                'excess_qty' => '1',
                'notes' => 'dailysales Item2',
                'added_stage' => 'First',
            ],
            [
                'daily_sale_id' => 2 ,
                'product_id' => 1,
                'store_id' => 1,
                'quantity' => '35',
                'sold_qty' => '15',
                'replaced_qty' =>'0',
                'restored_qty' => '3',
                'returned_qty' => '0',
                'shortage_qty' => '1',
                'damaged_qty' => '3',
                'excess_qty' => '0',
                'notes' => 'dailysales Item3',
                'added_stage' => 'First',
            ],
            [
                'daily_sale_id' => 3 ,
                'product_id' => 1,
                'store_id' => 1,
                'quantity' => '24',
                'sold_qty' => '10',
                'replaced_qty' =>'0',
                'restored_qty' => '0',
                'returned_qty' => '2',
                'shortage_qty' => '1',
                'damaged_qty' => '0',
                'excess_qty' => '0',
                'notes' => 'dailysales Item4',
                'added_stage' => 'First',
            ],
            [
                'daily_sale_id' => 1 ,
                'product_id' => 1,
                'store_id' => 1,
                'quantity' => '10',
                'sold_qty' => '5',
                'replaced_qty' =>'2',
                'restored_qty' => '3',
                'returned_qty' => '5',
                'shortage_qty' => '1',
                'damaged_qty' => '1',
                'excess_qty' => '0',
                'notes' => 'dailysales Item5',
                'added_stage' => 'First',
            ]
            ];
            $now = \Carbon\Carbon::now();
        foreach ($dailysaleitems as $key => $dailysaleitem) {
            $dailysaleitems[$key]['created_at'] = $now;
            $dailysaleitems[$key]['updated_at'] = $now;
        }
        \App\DailySaleItem::insert($dailysaleitems);
    }
}