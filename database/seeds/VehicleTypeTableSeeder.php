<?php

use Illuminate\Database\Seeder;

class VehicleTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            [
                'name' => 'Car',
                'is_active' => 'Yes',
            ],
            [
                'name' => 'Van',
                'is_active' => 'Yes',
            ],
            [
                'name' => 'Bike',
                'is_active' => 'Yes',
            ],
            [
                'name' => 'Jeep',
                'is_active' => 'Yes',
            ],
            [
                'name' => 'Single Cab',
                'is_active' => 'Yes',
            ],
            [
                'name' => 'Double Cab',
                'is_active' => 'Yes',
            ],
            [
                'name' => 'Truck',
                'is_active' => 'Yes',
            ],
            [
                'name' => 'Bus',
                'is_active' => 'Yes',
            ],
            [
                'name' => 'Lorry',
                'is_active' => 'Yes',
            ],
            [
                'name' => 'Three Wheeler',
                'is_active' => 'Yes',
            ],
            [
                'name' => 'Others',
                'is_active' => 'Yes',
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($types as $key => $type) {
            $types[$key]['created_at'] = $now;
            $types[$key]['updated_at'] = $now;
        }
        \App\VehicleType::insert($types);
    }
}
