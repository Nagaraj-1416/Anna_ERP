<?php

use Illuminate\Database\Seeder;

class PurchaserequestitemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $purchaserequestitems = [
            [
                'purchase_request_id' => 1,
                'product_id' => 1,
                'production_unit_id' => 1,
                'store_id' => 1,
                'shop_id' => 1,
                'supplier_id' => 1,
                'quantity' => 150.00,
                'status' => 'Pending',
            ],
            [
                'purchase_request_id' => 2,
                'product_id' => 2,
                'production_unit_id' => 2,
                'store_id' => 2,
                'shop_id' => 2,
                'supplier_id' => 1,
                'quantity' => 200.00,
                'status' => 'Confirmed',
            ],
            [
                'purchase_request_id' => 3,
                'product_id' => 3,
                'production_unit_id' => 3,
                'store_id' => 3,
                'shop_id' => 3,
                'supplier_id' => 1,
                'quantity' => 250.00,
                'status' => 'Pending',
            ],
            [
                'purchase_request_id' => 4,
                'product_id' => 2,
                'production_unit_id' => 4,
                'store_id' => 4,
                'shop_id' => 4,
                'supplier_id' => 1,
                'quantity' => 300.00,
                'status' => 'Confirmed',
            ],
            [
                'purchase_request_id' => 5,
                'product_id' => 3,
                'production_unit_id' => 5,
                'store_id' => 5,
                'shop_id' => 5,
                'supplier_id' => 1,
                'quantity' => 350.00,
                'status' => 'Pending',
            ],
            [
                'purchase_request_id' => 6,
                'product_id' => 3,
                'production_unit_id' => 2,
                'store_id' => 1,
                'shop_id' => 1,
                'supplier_id' => 1,
                'quantity' => 400.00,
                'status' => 'Confirmed',
            ],
            [
                'purchase_request_id' => 7,
                'product_id' => 2,
                'production_unit_id' => 5,
                'store_id' => 2,
                'shop_id' => 2,
                'supplier_id' => 1,
                'quantity' => 450.00,
                'status' => 'Pending',
            ],
            [
                'purchase_request_id' => 8,
                'product_id' => 2,
                'production_unit_id' => 1,
                'store_id' => 3,
                'shop_id' => 3,
                'supplier_id' => 1,
                'quantity' => 500.00,
                'status' => 'Confirmed',
            ],
            [
                'purchase_request_id' => 9,
                'product_id' => 2,
                'production_unit_id' => 2,
                'store_id' => 4,
                'shop_id' => 4,
                'supplier_id' => 1,
                'quantity' => 550.00,
                'status' => 'Pending',
            ],
            [
                'purchase_request_id' => 10,
                'product_id' => 3,
                'production_unit_id' => 2,
                'store_id' => 5,
                'shop_id' => 5,
                'supplier_id' => 1,
                'quantity' => 600.00,
                'status' => 'Confirmed',
            ],
        ];
            $now = \Carbon\Carbon::now();
        foreach ($purchaserequestitems as $key => $add) {
            $purchaserequestitems[$key]['created_at'] = $now;
            $purchaserequestitems[$key]['updated_at'] = $now;
        }

        \App\PurchaseRequestItem::insert($purchaserequestitems);
    }
}