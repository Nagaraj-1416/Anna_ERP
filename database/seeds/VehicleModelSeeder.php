<?php

use Illuminate\Database\Seeder;

class VehicleModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = [
            [
                'name' => 'sample vehicle 1 make',
                'is_active' => 'Yes',
                'make_id' => 1,
            ],
            [
                'name' => 'sample vehicle 2 make',
                'is_active' => 'Yes',
                'make_id' => 2,
            ],
            [
                'name' => 'sample vehicle 3 make',
                'is_active' => 'Yes',
                'make_id' => 3,
            ],
            [
                'name' => 'sample vehicle 4 make',
                'is_active' => 'Yes',
                'make_id' => 4,
            ],
            [
                'name' => 'sample vehicle 5 make',
                'is_active' => 'Yes',
                'make_id' => 5,
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($brands as $key => $brand) {
            $brands[$key]['created_at'] = $now;
            $brands[$key]['updated_at'] = $now;
        }

        \App\VehicleModel::insert($brands);
    }
}
