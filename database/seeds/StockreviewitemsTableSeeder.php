<?php

use Illuminate\Database\Seeder;

class StockreviewitemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stockreviewitems = [
            [
                'date' => '2024-02-22',
                'available_qty' => '150',
                'actual_qty' => '200',
                'excess_qty' => '12',
                'shortage_qty' => '2',
                'rate' => '12.00',
                'amount' => '1000.00',
                'excess_amount' => '100.00',
                'shortage_amount' => '20.00',
                'product_id' => 1,
                'stock_id' => 1,
                'stock_review_id' => 1,
            ],
            [
                'date' => '2024-03-10',
                'available_qty' => '160',
                'actual_qty' => '220',
                'excess_qty' => '15',
                'shortage_qty' => '5',
                'rate' => '14.00',
                'amount' => '1200.00',
                'excess_amount' => '120.00',
                'shortage_amount' => '50.00',
                'product_id' => 2,
                'stock_id' => 2,
                'stock_review_id' => 2,
            ],
            [
                'date' => '2024-04-15',
                'available_qty' => '170',
                'actual_qty' => '240',
                'excess_qty' => '18',
                'shortage_qty' => '8',
                'rate' => '16.00',
                'amount' => '1400.00',
                'excess_amount' => '140.00',
                'shortage_amount' => '60.00',
                'product_id' => 3,
                'stock_id' => 1,
                'stock_review_id' => 3,
            ],
            [
                'date' => '2024-05-20',
                'available_qty' => '180',
                'actual_qty' => '260',
                'excess_qty' => '20',
                'shortage_qty' => '10',
                'rate' => '18.00',
                'amount' => '1600.00',
                'excess_amount' => '160.00',
                'shortage_amount' => '40.00',
                'product_id' => 1,
                'stock_id' => 2,
                'stock_review_id' => 4,
            ],
            [
                'date' => '2024-06-25',
                'available_qty' => '190',
                'actual_qty' => '280',
                'excess_qty' => '22',
                'shortage_qty' => '12',
                'rate' => '20.00',
                'amount' => '1800.00',
                'excess_amount' => '2.00',
                'shortage_amount' => '180.00',
                'product_id' => 2,
                'stock_id' => 1,
                'stock_review_id' => 5,
            ],
            [
                'date' => '2024-07-12',
                'available_qty' => '200',
                'actual_qty' => '300',
                'excess_qty' => '25',
                'shortage_qty' => '15',
                'rate' => '22.00',
                'amount' => '2000.00',
                'excess_amount' => '200.00',
                'shortage_amount' => '10.00',
                'product_id' => 3,
                'stock_id' => 2,
                'stock_review_id' => 6,
            ],
            [
                'date' => '2024-08-18',
                'available_qty' => '210',
                'actual_qty' => '320',
                'excess_qty' => '28',
                'shortage_qty' => '18',
                'rate' => '24.00',
                'amount' => '2200.00',
                'excess_amount' => '220.00',
                'shortage_amount' => '60.00',
                'product_id' => 1,
                'stock_id' => 1,
                'stock_review_id' => 7,
            ],
            [
                'date' => '2024-09-22',
                'available_qty' => '220',
                'actual_qty' => '340',
                'excess_qty' => '30',
                'shortage_qty' => '20',
                'rate' => '26.00',
                'amount' => '2400.00',
                'excess_amount' => '240.00',
                'shortage_amount' => '90.00',
                'product_id' => 2,
                'stock_id' => 2,
                'stock_review_id' => 8,
            ],
            [
                'date' => '2024-10-28',
                'available_qty' => '230',
                'actual_qty' => '360',
                'excess_qty' => '32',
                'shortage_qty' => '22',
                'rate' => '28.00',
                'amount' => '2600.00',
                'excess_amount' => '260.00',
                'shortage_amount' => '70.00',
                'product_id' => 3,
                'stock_id' => 1,
                'stock_review_id' => 9,
            ],
            [
                'date' => '2024-11-30',
                'available_qty' => '240',
                'actual_qty' => '380',
                'excess_qty' => '35',
                'shortage_qty' => '25',
                'rate' => '30.00',
                'amount' => '2800.00',
                'excess_amount' => '280.00',
                'shortage_amount' => '60.00',
                'product_id' => 1,
                'stock_id' => 2,
                'stock_review_id' => 10,
            ],
        ];
            $now = \Carbon\Carbon::now();
            foreach ($stockreviewitems as $key => $stockreviewitem) {
                $stockreviewitems[$key]['created_at'] = $now;
                $stockreviewitems[$key]['updated_at'] = $now;
            }
    
            \App\StockReviewItem::insert($stockreviewitems);
    }
}