<?php

use Illuminate\Database\Seeder;

class StocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stocks = [
            [
                'type' => 'Auto',
                'category' => 'Main',
                'product_id' => 1,
                'store_id' => 1,
                'vehicle_id' => 1,
                'available_stock' => '50',
                'min_stock_level' => '10',
                'notes' => 'Regular stock replenishment required',
                'company_id' => 1,
            ],
            [
                'type' => 'Manual',
                'category' => 'Vehicle',
                'product_id' => 2,
                'store_id' => 1,
                'vehicle_id' => 2,
                'available_stock' => '100',
                'min_stock_level' => '20',
                'notes' => 'High-demand product',
                'company_id' => 1,
            ],
            [
                'type' => 'Auto',
                'category' => 'Main',
                'product_id' => 3,
                'store_id' => 2,
                'vehicle_id' => 3,
                'available_stock' => '75',
                'min_stock_level' => '15',
                'notes' => 'New product line',
                'company_id' => 1,
            ],
            [
                'type' => 'Manual',
                'category' => 'Vehicle',
                'product_id' => 1,
                'store_id' => 2,
                'vehicle_id' => 4,
                'available_stock' => '30',
                'min_stock_level' => '5',
                'notes' => 'Limited edition item',
                'company_id' => 1,
            ],
            [
                'type' => 'Auto',
                'category' => 'Main',
                'product_id' => 2,
                'store_id' => 3,
                'vehicle_id' => 1,
                'available_stock' => '90',
                'min_stock_level' => '30',
                'notes' => 'Seasonal promotion',
                'company_id' => 1,
            ],
            [
                'type' => 'Manual',
                'category' => 'Vehicle',
                'product_id' => 3,
                'store_id' => 3,
                'vehicle_id' => 2,
                'available_stock' => '25',
                'min_stock_level' => '10',
                'notes' => 'Fast-selling item',
                'company_id' => 1,
            ],
            [
                'type' => 'Auto',
                'category' => 'Main',
                'product_id' => 1,
                'store_id' => 4,
                'vehicle_id' => 3,
                'available_stock' => '80',
                'min_stock_level' => '20',
                'notes' => 'Restock soon',
                'company_id' => 1,
            ],
            [
                'type' => 'Manual',
                'category' => 'Vehicle',
                'product_id' => 1,
                'store_id' => 4,
                'vehicle_id' => 4,
                'available_stock' => '40',
                'min_stock_level' => '15',
                'notes' => 'Popular product',
                'company_id' => 1,
            ],
            [
                'type' => 'Auto',
                'category' => 'Main',
                'product_id' => 2,
                'store_id' => 5,
                'vehicle_id' => 4,
                'available_stock' => '60',
                'min_stock_level' => '25',
                'notes' => 'Limited time offer',
                'company_id' => 1,
            ],
            [
                'type' => 'Manual',
                'category' => 'Vehicle',
                'product_id' => 3,
                'store_id' => 5,
                'vehicle_id' => 3,
                'available_stock' => '70',
                'min_stock_level' => '20',
                'notes' => 'Special promotion',
                'company_id' => 1,
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($stocks as $key => $stock) {
            $stocks[$key]['created_at'] = $now;
            $stocks[$key]['updated_at'] = $now;
        }

        \App\Stock::insert($stocks);
    }
}
