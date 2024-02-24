<?php

use Illuminate\Database\Seeder;

class ProductpurchaseorderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $purchaseOrders = [
            [
                'purchase_order_id' => 1,
                'product_id' => 1,
                'production_unit_id' => 1,
                'store_id' => 1,
                'shop_id' => 1,
                'quantity' => 456.9,
                'status' => 'Drafted',
                'brand_id' => 1,
            ],
        ];
        $now = \Carbon\Carbon::now();
        foreach ($purchaseOrders as $key => $purchaseOrder) {
            $purchaseOrders[$key]['created_at'] = $now;
            $purchaseOrders[$key]['updated_at'] = $now;
        }

        // \App\ProductPuc::insert($purchaseOrders);
    }
}
