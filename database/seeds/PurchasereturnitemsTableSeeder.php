<?php

use Illuminate\Database\Seeder;

class PurchasereturnitemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $purchasereturnitems = [
            [
                'ordered_qty' => '12',
                'returned_qty' => '25',
                'ordered_rate' => '699',
                'returned_rate' => '699',
                'order_amount' => '1399',
                'returned_amount' => '1399',
                'reason' => 'Color mismatch',
                'purchase_return_id' => 1,
                'order_id' => 1,
                'product_id' => 1,
                'supplier_id' => 1,
                'company_id' => 1,
            ],
            [
                'ordered_qty' => '15',
                'returned_qty' => '30',
                'ordered_rate' => '799',
                'returned_rate' => '799',
                'order_amount' => '1599',
                'returned_amount' => '1599',
                'reason' => 'Defective product',
                'purchase_return_id' => 2,
                'order_id' => 2,
                'product_id' => 2,
                'supplier_id' => 1,
                'company_id' => 1,
            ],
            [
                'ordered_qty' => '20',
                'returned_qty' => '35',
                'ordered_rate' => '899',
                'returned_rate' => '899',
                'order_amount' => '1798',
                'returned_amount' => '1798',
                'reason' => 'Size issue',
                'purchase_return_id' => 3,
                'order_id' => 3,
                'product_id' => 3,
                'supplier_id' => 1,
                'company_id' => 1,
            ],
            [
                'ordered_qty' => '10',
                'returned_qty' => '20',
                'ordered_rate' => '599',
                'returned_rate' => '599',
                'order_amount' => '1198',
                'returned_amount' => '1198',
                'reason' => 'Incorrect item received',
                'purchase_return_id' => 4,
                'order_id' => 4,
                'product_id' => 2,
                'supplier_id' => 1,
                'company_id' => 1,
            ],
            [
                'ordered_qty' => '18',
                'returned_qty' => '22',
                'ordered_rate' => '899',
                'returned_rate' => '899',
                'order_amount' => '1618',
                'returned_amount' => '1618',
                'reason' => 'Quality not as expected',
                'purchase_return_id' => 5,
                'order_id' => 5,
                'product_id' => 2,
                'supplier_id' => 1,
                'company_id' => 1,
            ],
            [
                'ordered_qty' => '22',
                'returned_qty' => '28',
                'ordered_rate' => '999',
                'returned_rate' => '999',
                'order_amount' => '2198',
                'returned_amount' => '2198',
                'reason' => 'Damaged during transit',
                'purchase_return_id' => 1,
                'order_id' => 1,
                'product_id' => 3,
                'supplier_id' => 1,
                'company_id' => 4,
            ],
            [
                'ordered_qty' => '30',
                'returned_qty' => '35',
                'ordered_rate' => '799',
                'returned_rate' => '799',
                'order_amount' => '2397',
                'returned_amount' => '2397',
                'reason' => 'Color fading',
                'purchase_return_id' => 2,
                'order_id' => 2,
                'product_id' => 1,
                'supplier_id' => 1,
                'company_id' => 1,
            ],
            [
                'ordered_qty' => '25',
                'returned_qty' => '30',
                'ordered_rate' => '899',
                'returned_rate' => '899',
                'order_amount' => '2248',
                'returned_amount' => '2248',
                'reason' => 'Wrong size delivered',
                'purchase_return_id' => 3,
                'order_id' => 3,
                'product_id' => 2,
                'supplier_id' => 1,
                'company_id' => 1,
            ],
            [
                'ordered_qty' => '18',
                'returned_qty' => '22',
                'ordered_rate' => '799',
                'returned_rate' => '799',
                'order_amount' => '1438',
                'returned_amount' => '1438',
                'reason' => 'Defective product',
                'purchase_return_id' => 4,
                'order_id' => 4,
                'product_id' => 3,
                'supplier_id' => 1,
                'company_id' => 2,
            ],
            [
                'ordered_qty' => '20',
                'returned_qty' => '25',
                'ordered_rate' => '899',
                'returned_rate' => '899',
                'order_amount' => '1798',
                'returned_amount' => '1798',
                'reason' => 'Size mismatch',
                'purchase_return_id' => 5,
                'order_id' => 5,
                'product_id' => 1,
                'supplier_id' => 1,
                'company_id' => 1,
            ],
        ];
            $now = \Carbon\Carbon::now();
        foreach ($purchasereturnitems as $key => $purchasereturnitem) {
            $purchasereturnitems[$key]['created_at'] = $now;
            $purchasereturnitems[$key]['updated_at'] = $now;
        }

        \App\PurchaseReturnItem::insert($purchasereturnitems);
    }
}