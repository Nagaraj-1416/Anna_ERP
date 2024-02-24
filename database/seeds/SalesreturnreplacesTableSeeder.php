<?php

use Illuminate\Database\Seeder;

class SalesreturnreplacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $salesreturnreplaces=[
             
                [
                    'qty' => '15',
                    'rate' => '25.00',
                    'amount' => '600.00',
                    'product_id' => 1,
                    'resolution_id' => 1,
                    'sales_return_id' => 1,
                ],
                [
                    'qty' => '12',
                    'rate' => '20.00',
                    'amount' => '240.00',
                    'product_id' => 2,
                    'resolution_id' => 2,
                    'sales_return_id' => 2,
                ],
                [
                    'qty' => '20',
                    'rate' => '30.00',
                    'amount' => '600.00',
                    'product_id' => 3,
                    'resolution_id' => 3,
                    'sales_return_id' => 3,
                ],
                [
                    'qty' => '8',
                    'rate' => '15.00',
                    'amount' => '120.00',
                    'product_id' => 1,
                    'resolution_id' => 1,
                    'sales_return_id' => 1,
                ],
                [
                    'qty' => '10',
                    'rate' => '35.00',
                    'amount' => '350.00',
                    'product_id' => 2,
                    'resolution_id' => 2,
                    'sales_return_id' => 2,
                ],
                [
                    'qty' => '18',
                    'rate' => '28.00',
                    'amount' => '504.00',
                    'product_id' => 1,
                    'resolution_id' => 1,
                    'sales_return_id' => 1,
                ],
                [
                    'qty' => '14',
                    'rate' => '18.00',
                    'amount' => '252.00',
                    'product_id' => 2,
                    'resolution_id' => 2,
                    'sales_return_id' => 2,
                ],
                [
                    'qty' => '22',
                    'rate' => '22.00',
                    'amount' => '484.00',
                    'product_id' => 3,
                    'resolution_id' => 3,
                    'sales_return_id' => 3,
                ],
                [
                    'qty' => '9',
                    'rate' => '40.00',
                    'amount' => '360.00',
                    'product_id' => 1,
                    'resolution_id' => 1,
                    'sales_return_id' => 1,
                ],
                [
                    'qty' => '11',
                    'rate' => '32.00',
                    'amount' => '352.00',
                    'product_id' => 2,
                    'resolution_id' => 2,
                    'sales_return_id' => 2,
                ],
            
            ];
            $now = \Carbon\Carbon::now();
        foreach ($salesreturnreplaces as $key => $salesreturnreplace) {
            $salesreturnreplaces[$key]['created_at'] = $now;
            $salesreturnreplaces[$key]['updated_at'] = $now;
        }
        \App\SalesReturnReplaces::insert($salesreturnreplaces);
    }
}