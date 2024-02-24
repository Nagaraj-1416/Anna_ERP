<?php

use Illuminate\Database\Seeder;

class MileageratesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mileagerates=[
            [
                'date' => '2024-02-21',
                'rate' => '150',
                'prepared_by' => 1,
            ],
            [
                'date' => '2024-02-21',
                'rate' => '60',
                'prepared_by' => 1,
            ],
            [
                'date' => '2024-02-21',
                'rate' => '40',
                'prepared_by' => 1,
            ],
            [
                'date' => '2024-02-21',
                'rate' => '30',
                'prepared_by' => 1,
            ],[
                'date' => '2024-02-21',
                'rate' => '50',
                'prepared_by' => 1,
            ],
            [
                'date' => '2024-02-21',
                'rate' => '60',
                'prepared_by' => 1,
            ],
            [
                'date' => '2024-02-21',
                'rate' => '40',
                'prepared_by' => 1,
            ],
            [
                'date' => '2024-02-21',
                'rate' => '80',
                'prepared_by' => 1,
            ],
            [
                'date' => '2024-02-21',
                'rate' => '60',
                'prepared_by' => 1,
            ],
            [
                'date' => '2024-02-21',
                'rate' => '40',
                'prepared_by' => 1,
            ]
            ];
            $now = \Carbon\Carbon::now();
        foreach ($mileagerates as $key => $mileagerate) {
                 $mileagerates[$key]['created_at'] = $now;
                $mileagerates[$key]['updated_at'] = $now;
                }
        
        \App\MileageRate::insert($mileagerates);
    }
}