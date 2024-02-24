<?php

use Illuminate\Database\Seeder;

class MachinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $machines = [
            [
                'code'=>'M0001',
                'name'=>'Machine 1',
                'purchase_price'=> 4355.78,
                'model'=> 'machine model 1',
                'brand'=> 'machine brand 1',
                'manufacturer_country'=> 'India',
                'manufacturer_year'=> '2017',
                'warranty_date'=> null,
                'is_active'=>'Yes',
                'company_id'=>1,
                'supplier_id'=>1,
                'specifications'=>'specifications for the machine entry',
            ],
            [
                'code'=>'M0002',
                'name'=>'Machine 2',
                'purchase_price'=> 4355.78,
                'model'=> 'machine model 2',
                'brand'=> 'machine brand 2',
                'manufacturer_country'=> 'India',
                'manufacturer_year'=> '2017',
                'warranty_date'=> null,
                'is_active'=>'Yes',
                'company_id'=>1,
                'supplier_id'=>1,
                'specifications'=>'specifications for the machine entry',
            ],
            [
                'code'=>'M0003',
                'name'=>'Machine 3',
                'purchase_price'=> 4355.78,
                'model'=> 'machine model 3',
                'brand'=> 'machine brand 3',
                'manufacturer_country'=> 'India',
                'manufacturer_year'=> '2017',
                'warranty_date'=> null,
                'is_active'=>'Yes',
                'company_id'=>1,
                'supplier_id'=>1,
                'specifications'=>'specifications for the machine entry',
            ],
            [
                'code'=>'M0004',
                'name'=>'Machine 4',
                'purchase_price'=> 4355.78,
                'model'=> 'machine model 4',
                'brand'=> 'machine brand 4',
                'manufacturer_country'=> 'India',
                'manufacturer_year'=> '2017',
                'warranty_date'=> null,
                'is_active'=>'Yes',
                'company_id'=>1,
                'supplier_id'=>1,
                'specifications'=>'specifications for the machine entry',
            ],
            [
                'code'=>'M00005',
                'name'=>'Machine 5',
                'purchase_price'=> 4355.78,
                'model'=> 'machine model 5',
                'brand'=> 'machine brand 5',
                'manufacturer_country'=> 'India',
                'manufacturer_year'=> '2017',
                'warranty_date'=> null,
                'is_active'=>'Yes',
                'company_id'=>1,
                'supplier_id'=>1,
                'specifications'=>'specifications for the machine entry',
            ],
            
        ];

        $now = \Carbon\Carbon::now();
        foreach ($machines as $key => $machine) {
            $machines[$key]['created_at'] = $now;
            $machines[$key]['updated_at'] = $now;
        }

        \App\Machine::insert($machines);
    }
}
