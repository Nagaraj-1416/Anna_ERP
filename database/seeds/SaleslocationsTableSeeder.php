<?php

use Illuminate\Database\Seeder;

class SaleslocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sales_locations = [
            [
            'code'=>'SL00001',
            'name'=>'Sales Location 1',
            'phone'=>332435,
            'fax'=>5765675,
            'mobile'=>3223454,
            'email'=>'saleslocation1@gmail.com',
            'notes'=>'notes for registered the sales location',
            'company_id'=>1,
            'is_active'=>1,
            'is_selling_price'=>'Yes',
            'type'=>'Shop',
            'vehicle_id'=>1,
            ],
            [
            'code'=>'SL00002',
            'name'=>'Sales Location 2',
            'phone'=>332435,
            'fax'=>5765675,
            'mobile'=>3223454,
            'email'=>'saleslocation1@gmail.com',
            'notes'=>'notes for registered the sales location',
            'company_id'=>1,
            'is_active'=>1,
            'is_selling_price'=>'Yes',
            'type'=>'Shop',
            'vehicle_id'=>1,
            ],
            [
            'code'=>'SL00003',
            'name'=>'Sales Location 3',
            'phone'=>332435,
            'fax'=>5765675,
            'mobile'=>3223454,
            'email'=>'saleslocation1@gmail.com',
            'notes'=>'notes for registered the sales location',
            'company_id'=>1,
            'is_active'=>1,
            'is_selling_price'=>'Yes',
            'type'=>'Shop',
            'vehicle_id'=>1,
            ],
            [
            'code'=>'SL00004',
            'name'=>'Sales Location 4',
            'phone'=>332435,
            'fax'=>5765675,
            'mobile'=>3223454,
            'email'=>'saleslocation1@gmail.com',
            'notes'=>'notes for registered the sales location',
            'company_id'=>1,
            'is_active'=>1,
            'is_selling_price'=>'Yes',
            'type'=>'Shop',
            'vehicle_id'=>1,
            ],
            [
            'code'=>'SL00005',
            'name'=>'Sales Location 5',
            'phone'=>332435,
            'fax'=>5765675,
            'mobile'=>3223454,
            'email'=>'saleslocation1@gmail.com',
            'notes'=>'notes for registered the sales location',
            'company_id'=>1,
            'is_active'=>1,
            'is_selling_price'=>'Yes',
            'type'=>'Shop',
            'vehicle_id'=>1,
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($sales_locations as $key => $add) {
            $sales_locations[$key]['created_at'] = $now;
            $sales_locations[$key]['updated_at'] = $now;
        }

        \App\SalesLocation::insert($sales_locations);
    }
}
