<?php

use Illuminate\Database\Seeder;

class MeasurementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $measurements = [
            [
                'code' => 'L',
                'name' => 'Liters',
            ],
            [
                'code' => 'ML',
                'name' => 'Milliliters',
            ],
            [
                'code' => 'Unit',
                'name' => 'Units',
            ],
            [
                'code' => 'M',
                'name' => 'Meters',
            ],
            [
                'code' => 'Nos',
                'name' => 'Numbers',
            ],
            [
                'code' => 'Qty',
                'name' => 'Quantities',
            ],
            [
                'code' => 'Gm',
                'name' => 'Gram',
            ],
            [
                'code' => 'Mg',
                'name' => 'Milligram',
            ],
            [
                'code' => 'Cg',
                'name' => 'Centigram',
            ],
            [
                'code' => 'Kg',
                'name' => 'Kilogram',
            ],
            [
                'code' => 'T',
                'name' => 'Ton',
            ]
        ];
        $now = \Carbon\Carbon::now();
        foreach ($measurements as $key => $measurement) {
            $measurements[$key]['created_at'] = $now;
            $measurements[$key]['updated_at'] = $now;
        }
        \App\Measurement::insert($measurements);
    }
}
