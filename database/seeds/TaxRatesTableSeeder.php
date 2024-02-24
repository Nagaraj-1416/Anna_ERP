<?php

use Illuminate\Database\Seeder;

class TaxRatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rates = [
            [
                'name' => 'VAT',
                'rate' => '10',
                'notes' => 'Value Added Tax',
            ],
            [
                'name' => 'NBT',
                'rate' => '4.5',
                'notes' => 'Nation building tax',
            ],
            [
                'name' => 'Sales Tax',
                'rate' => '12',
                'notes' => 'Sales Tax',
            ],
            [
                'name' => 'UT',
                'rate' => '6',
                'notes' => 'Utility Tax',
            ],[
                'name' => 'Service tax',
                'rate' => '6',
                'notes' => 'Service tax',
            ]
        ];
        $now = \Carbon\Carbon::now();
        foreach ($rates as $key => $rate) {
            $rates[$key]['created_at'] = $now;
            $rates[$key]['updated_at'] = $now;
        }
        \App\TaxRate::insert($rates);
    }
}