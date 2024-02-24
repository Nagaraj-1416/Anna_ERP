<?php

use Illuminate\Database\Seeder;

class VehicleMakeSeeder extends Seeder
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
            ],
            [
                'name' => 'vehicle make 2',
                'is_active' => 'Yes',
            ],
            [
                'name' => 'vehicle make 3',
                'is_active' => 'Yes',
            ],
            [
                'name' => 'vehicle make 4',
                'is_active' => 'Yes',
            ],
            [
                'name' => 'vehicle make 5',
                'is_active' => 'Yes',
            ]
        ];

        $now = \Carbon\Carbon::now();
        foreach ($brands as $key => $brand) {
            $brands[$key]['created_at'] = $now;
            $brands[$key]['updated_at'] = $now;
        }

        \App\VehicleMake::insert($brands);
    }
}
