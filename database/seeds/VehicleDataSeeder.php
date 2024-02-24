<?php

use App\VehicleMake;
use App\VehicleModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class VehicleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vehicles = [
            [
                'vehicle_no'=>	4355454,
                'engine_no'	=>5643445,
                'chassis_no'=>	4634545345,
                'reg_date'=>	\Carbon\Carbon::now(),
                'year'=>	2024,
                'color'=>	'red',
                'fuel_type'=>	'diesel',
                'image'=>	'',
                'type_of_body'=>''	,
                'seating_capacity'=>5,
                'weight'=>14554,
                'gross'=>'',
                'tyre_size_front'=>	'',
                'tyre_size_rear'=>	'',
                'length'=>	'',
                'width'	=>'',
                'height'=>	'',
                'wheel_front'	=>'',
                'wheel_rear'=>	'',
                'type_id'=>	1,
                'make_id'=>	1,
                'model_id'=>1,
                'notes'=>'sdfsfgfvb',
                'category'=>'General',
                'is_active'=>	1,
                'company_id'=>	1, 
            ]
        ];

        $now = \Carbon\Carbon::now();
        foreach ($vehicles as $key => $add) {
            $vehicles[$key]['created_at'] = $now;
            $vehicles[$key]['updated_at'] = $now;
        }

        \App\Vehicle::insert($vehicles);
    }
}
