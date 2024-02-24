<?php

use Illuminate\Database\Seeder;

class StockhistoriesTableSeeder extends Seeder
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
                'stock_id' => 1,
                'quantity' => '89',
                'transaction' => "In",
                'trans_date' => "2024-02-15",
                'trans_description' => 'Sales to customer XYZ',
                'production_unit_id' => 1,
                'sales_location_id' => 1,
                'rate' => 1090.90,
                'type' => 'Opening',
                'store_id' => 1,
            ],
            [
                'stock_id' => 2,
                'quantity' => '50',
                'transaction' => "Out",
                'trans_date' => "2024-02-16",
                'trans_description' => 'Sales to customer XYZ',
                'production_unit_id' => 2,
                'sales_location_id' => 2,
                'rate' => 1299.99,
                'type' => 'Sale',
                'store_id' => 1,
            ],
            [
                'stock_id' => 3,
                'quantity' => '200',
                'transaction' => "In",
                'trans_date' => "2024-02-17",
                'trans_description' => 'Received new shipment',
                'production_unit_id' => 1,
                'sales_location_id' => 1,
                'rate' => 1200.00,
                'type' => 'Purchase',
                'store_id' => 2,
            ],
            [
                'stock_id' => 4,
                'quantity' => '',
                'transaction' => "In",
                'trans_date' => "2024-02-18",
                'trans_description' => '',
                'production_unit_id' => 2,
                'sales_location_id' => 2,
                'rate' => 1350.50,
                'type' => 'Taken',
                'store_id' => 2,
            ],
            [
                'stock_id' => 5,
                'quantity' => '75',
                'transaction' => "Out",
                'trans_date' => "2024-02-19",
                'trans_description' => 'Distribution to branch offices',
                'production_unit_id' => 1,
                'sales_location_id' => 1,
                'rate' => 1150.75,
                'type' => 'Sale',
                'store_id' => 3,
            ],
            [
                'stock_id' => 6,
                'quantity' => '',
                'transaction' => "In",
                'trans_date' => "2024-02-20",
                'trans_description' => 'Refill from central warehouse',
                'production_unit_id' => 1,
                'sales_location_id' => 1,
                'rate' => 1225.00,
                'type' => 'Opening',
                'store_id' => 3,
            ],
            [
                'stock_id' => 7,
                'quantity' => '150',
                'transaction' => "Out",
                'trans_date' => "2024-02-21",
                'trans_description' => 'Sales during weekend promotion',
                'production_unit_id' => 2,
                'sales_location_id' => 2,
                'rate' => 1400.00,
                'type' => 'Sale',
                'store_id' => 4,
            ],
            [
                'stock_id' => 8,
                'quantity' => '',
                'transaction' => "In",
                'trans_date' => "2024-02-22",
                'trans_description' => '',
                'production_unit_id' => 1,
                'sales_location_id' => 1,
                'rate' => 1300.00,
                'type' => 'Opening',
                'store_id' => 4,
            ],
            [
                'stock_id' => 9,
                'quantity' => '120',
                'transaction' => "Out",
                'trans_date' => "2024-02-23",
                'trans_description' => 'Sales to corporate client',
                'production_unit_id' => 1,
                'sales_location_id' => 1,
                'rate' => 1275.50,
                'type' => 'Sale',
                'store_id' => 5,
            ],
            [
                'stock_id' => 10,
                'quantity' => '250',
                'transaction' => "In",
                'trans_date' => "2024-02-24",
                'trans_description' => 'Additional inventory for upcoming event',
                'production_unit_id' => 2,
                'sales_location_id' => 2,
                'rate' => 1450.00,
                'type' => 'Taken',
                'store_id' => 5,
            ],
          
        ];

        $now = \Carbon\Carbon::now();
        foreach ($stocks as $key => $stock) {
            $stocks[$key]['created_at'] = $now;
            $stocks[$key]['updated_at'] = $now;
        }

        \App\StockHistory::insert($stocks);
    }
}
