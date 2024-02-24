<?php

use Illuminate\Database\Seeder;

class VehiclesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [       
            'vehicle_no'=>'003459034',
            'engine_no'=>"234435096",
            'chassis_no'=>"458768789",
            'reg_date'=>\Carbon\Carbon::now(),
            'year'=>'2024',
            'color'=>'Red',
            'fuel_type'=>'Diesel',
            'image'=>'Car Image 1',
            'type_of_body'=>'Metal',
            'seating_capacity'=>'7',
            'weight'=>'67 ton',
            'gross'=>'gross 1',
            'length'=>'4',
            'width'=>'9',
            'height'=>'4',
            'wheel_front'=>'2',
            'type_id'=>1,
            'make_id'=>1,
            'model_id'=>1,
            'notes'=>'notes to give the description',
            'category'=>'General',
            'is_active'=>'Yes',
            'company_id'=>1,
            ],
            [       
            'vehicle_no'=>'003459034',
            'engine_no'=>"234435096",
            'chassis_no'=>"458768789",
            'reg_date'=>\Carbon\Carbon::now(),
            'year'=>'2024',
            'color'=>'Red',
            'fuel_type'=>'Diesel',
            'image'=>'Car Image 1',
            'type_of_body'=>'Metal',
            'seating_capacity'=>'7',
            'weight'=>'67 ton',
            'gross'=>'gross 1',
            'length'=>'4',
            'width'=>'9',
            'height'=>'4',
            'wheel_front'=>'2',
            'type_id'=>1,
            'make_id'=>1,
            'model_id'=>1,
            'notes'=>'notes to give the description',
            'category'=>'General',
            'is_active'=>'Yes',
            'company_id'=>1,
            ],
            [       
            'vehicle_no'=>'567567567',
            'engine_no'=>"2344242335096",
            'chassis_no'=>"67867834534",
            'reg_date'=>\Carbon\Carbon::now(),
            'year'=>'2024',
            'color'=>'Red',
            'fuel_type'=>'Petrol',
            'image'=>'Bike Image 1',
            'type_of_body'=>'Metal',
            'seating_capacity'=>'7',
            'weight'=>'67 ton',
            'gross'=>'gross 1',
            'length'=>'4',
            'width'=>'9',
            'height'=>'4',
            'wheel_front'=>'2',
            'type_id'=>1,
            'make_id'=>1,
            'model_id'=>1,
            'notes'=>'notes to give the description',
            'category'=>'General',
            'is_active'=>'Yes',
            'company_id'=>1,
            ],
            [       
            'vehicle_no'=>'34534645645',
            'engine_no'=>"4575676578678",
            'chassis_no'=>"0989089089",
            'reg_date'=>\Carbon\Carbon::now(),
            'year'=>'2024',
            'color'=>'Red',
            'fuel_type'=>'Petrol',
            'image'=>'Bike Image 1',
            'type_of_body'=>'Metal',
            'seating_capacity'=>'7',
            'weight'=>'67 ton',
            'gross'=>'gross 1',
            'length'=>'4',
            'width'=>'9',
            'height'=>'4',
            'wheel_front'=>'2',
            'type_id'=>1,
            'make_id'=>1,
            'model_id'=>1,
            'notes'=>'notes to give the description',
            'category'=>'General',
            'is_active'=>'Yes',
            'company_id'=>1,
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($products as $key => $product) {
            $products[$key]['created_at'] = $now;
            $products[$key]['updated_at'] = $now;
        }

        \App\Vehicle::insert($products);
    }
}
