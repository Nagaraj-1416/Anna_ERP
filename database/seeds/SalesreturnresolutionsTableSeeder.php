<?php

use Illuminate\Database\Seeder;

class SalesreturnresolutionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $salesreturnresolutions=[
            [
                'resolution' => 'Credit',
                'amount' => '1000.00',
                'sales_return_id' => 1,
                'order_id' => 1,
            ],
            [
                'resolution' => 'Replace',
                'amount' => '2500.00',
                'sales_return_id' => 1,
                'order_id' => 1,
            ],
            [
                'resolution' => 'Refund',
                'amount' => '3500.00',
                'sales_return_id' => 4,
                'order_id' => 1,
            ],
            [
                'resolution' => 'Replace',
                'amount' => '4500.00',
                'sales_return_id' => 3,
                'order_id' => 1,
            ],
            [
                'resolution' => 'Credit',
                'amount' => '6500.00',
                'sales_return_id' => 2,
                'order_id' => 2,
            ],
            [
                'resolution' => 'Replace',
                'amount' => '5256.00',
                'sales_return_id' => 2,
                'order_id' => 2,
            ],
            [
                'resolution' => 'Credit',
                'amount' => '7895.00',
                'sales_return_id' => 2,
                'order_id' => 2,
            ],
            [
                'resolution' => 'Refund',
                'amount' => '1400.00',
                'sales_return_id' => 2,
                'order_id' => 2,
            ],[
                'resolution' => 'Credit',
                'amount' => '2600.00',
                'sales_return_id' => 2,
                'order_id' => 2,
            ],

        ];
        $now = \Carbon\Carbon::now();
        foreach ($salesreturnresolutions as $key => $salesreturnresolution) {
            $salesreturnresolutions[$key]['created_at'] = $now;
            $salesreturnresolutions[$key]['updated_at'] = $now;
        }
        \App\SalesReturnResolution::insert($salesreturnresolutions);
    }
}