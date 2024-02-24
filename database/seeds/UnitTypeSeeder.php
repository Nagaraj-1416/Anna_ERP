<?php

use App\VehicleMake;
use App\VehicleModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class UnitTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unitTypes = [
            [
                'code' => 'UT0000001',
                'name' => 'Box',
                'is_active' => 'Yes',
            ],
            [
                'code' => 'UT0000002',
                'name' => 'Carton',
                'is_active' => 'Yes',
            ],
            [
                'code' => 'UT0000003',
                'name' => 'Dozen',
                'is_active' => 'Yes',
            ],
            [
                'code' => 'UT0000004',
                'name' => 'Each',
                'is_active' => 'Yes',
            ],
            [
                'code' => 'UT0000005',
                'name' => 'Pieces',
                'is_active' => 'Yes',
            ]
        ];
        $now = \Carbon\Carbon::now();
        foreach ($unitTypes as $key => $unitType) {
            $unitTypes[$key]['created_at'] = $now;
            $unitTypes[$key]['updated_at'] = $now;
        }
        \App\UnitType::insert($unitTypes);
    }
}
