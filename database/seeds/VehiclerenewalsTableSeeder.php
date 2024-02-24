<?php

use Illuminate\Database\Seeder;

class VehiclerenewalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vehiclerenewals = [
            [
                'vehicle_id' => 1,
                'date' => '2024-02-01',
                'type' => 'Insurance',
            ],
            [
                'vehicle_id' => 2,
                'date' => '2024-02-02',
                'type' => 'Emission',
            ],
            [
                'vehicle_id' => 3,
                'date' => '2024-02-03',
                'type' => 'Fitness',
            ],
            [
                'vehicle_id' => 4,
                'date' => '2024-02-04',
                'type' => 'Tax',
            ],
            [
                'vehicle_id' => 1,
                'date' => '2024-02-05',
                'type' => 'Insurance',
            ],
            [
                'vehicle_id' => 2,
                'date' => '2024-02-06',
                'type' => 'Emission',
            ],
            [
                'vehicle_id' => 3,
                'date' => '2024-02-07',
                'type' => 'Fitness',
            ],
            [
                'vehicle_id' => 4,
                'date' => '2024-02-08',
                'type' => 'Tax',
            ],
            [
                'vehicle_id' => 1,
                'date' => '2024-02-09',
                'type' => 'Insurance',
            ],
            [
                'vehicle_id' => 2,
                'date' => '2024-02-10',
                'type' => 'Emission',
            ],
        ];
            $now = \Carbon\Carbon::now();
        foreach ($vehiclerenewals as $key => $vehiclerenewal) {
            $vehiclerenewals[$key]['created_at'] = $now;
            $vehiclerenewals[$key]['updated_at'] = $now;
        }
        \App\VehicleRenewal::insert($vehiclerenewals);
    }
}
