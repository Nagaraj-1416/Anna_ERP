<?php

use Illuminate\Database\Seeder;

class ProductionunitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $production_units = [
            [
            'code'=>'PU00001',
            'name'=>'PR Unit 1',
            'phone'=>'3453464565',
            'fax'=>'35656756',
            'mobile'=>'435434453',
            'email'=>'pr1@gmail.com',
            'notes'=>'notes for the entry of the production unit',
            'company_id'=>1,
            'is_active'=>1,
            ],
            [
            'code'=>'PU00002',
            'name'=>'PR Unit 2',
            'phone'=>'3453464565',
            'fax'=>'35656756',
            'mobile'=>'435434453',
            'email'=>'pr2@gmail.com',
            'notes'=>'notes for the entry of the production unit',
            'company_id'=>1,
            'is_active'=>1,
            ],
            [
            'code'=>'PU00003',
            'name'=>'PR Unit 3',
            'phone'=>'3453464565',
            'fax'=>'35656756',
            'mobile'=>'435434453',
            'email'=>'pr3@gmail.com',
            'notes'=>'notes for the entry of the production unit',
            'company_id'=>1,
            'is_active'=>1,
            ],
            [
            'code'=>'PU00004',
            'name'=>'PR Unit 1',
            'phone'=>'3453464565',
            'fax'=>'35656756',
            'mobile'=>'435434453',
            'email'=>'pr4@gmail.com',
            'notes'=>'notes for the entry of the production unit',
            'company_id'=>1,
            'is_active'=>1,
            ],
            [
            'code'=>'PU00005',
            'name'=>'PR Unit 5',
            'phone'=>'3453464565',
            'fax'=>'35656756',
            'mobile'=>'435434453',
            'email'=>'pr5@gmail.com',
            'notes'=>'notes for the entry of the production unit',
            'company_id'=>1,
            'is_active'=>1,
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($production_units as $key => $add) {
            $production_units[$key]['created_at'] = $now;
            $production_units[$key]['updated_at'] = $now;
        }

        \App\ProductionUnit::insert($production_units);
    }
}
