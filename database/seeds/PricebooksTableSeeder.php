<?php

use Illuminate\Database\Seeder;

class PricebooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pricebooks = [
            [
                'code'=>'PB0001',
                'name'=>'Price Book 1',
                'category'=>'Production To Store',
                'type'=>'Selling Price',
                'notes'=> 'notes to enter the price books',
                'is_active'=>	1,
                'prepared_by'=>	1,
                'company_id'=>	1,
                'related_to_id'=>	1,
                'related_to_type'=>	'related type 1',
            ],
            [
                'code'=>'PB0002',
                'name'=>'Price Book 2',
                'category'=>'Production To Store',
                'type'=>'Selling Price',
                'notes'=> 'notes to enter the price books',
                'is_active'=>	1,
                'prepared_by'=>	1,
                'company_id'=>	1,
                'related_to_id'=>	1,
                'related_to_type'=>	'related type 2',
            ],
            [
                'code'=>'PB0003',
                'name'=>'Price Book 3',
                'category'=>'Production To Store',
                'type'=>'Selling Price',
                'notes'=> 'notes to enter the price books',
                'is_active'=>	1,
                'prepared_by'=>	1,
                'company_id'=>	1,
                'related_to_id'=>	1,
                'related_to_type'=>	'related type 3',
            ],
            [
                'code'=>'PB0004',
                'name'=>'Price Book 4',
                'category'=>'Production To Store',
                'type'=>'Selling Price',
                'notes'=> 'notes to enter the price books',
                'is_active'=>	1,
                'prepared_by'=>	1,
                'company_id'=>	1,
                'related_to_id'=>	1,
                'related_to_type'=>	'related type 4',
            ],
            [
                'code'=>'PB0005',
                'name'=>'Price Book 5',
                'category'=>'Production To Store',
                'type'=>'Selling Price',
                'notes'=> 'notes to enter the price books',
                'is_active'=>	1,
                'prepared_by'=>	1,
                'company_id'=>	1,
                'related_to_id'=>	1,
                'related_to_type'=>	'related type 5',
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($pricebooks as $key => $brand) {
            $pricebooks[$key]['created_at'] = $now;
            $pricebooks[$key]['updated_at'] = $now;
        }

        \App\PriceBook::insert($pricebooks);
    }
}
