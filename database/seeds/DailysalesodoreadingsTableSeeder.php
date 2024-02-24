<?php

use Illuminate\Database\Seeder;

class DailysalesodoreadingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dailysalesodoreading = [
            [
                'starts_at' => 234324.456,
                'ends_at' => 234324.456,
                'vehicle_id' => 1,
                'daily_sale_id' => 1,
                'sales_handover_id' => 1,
            ],
            [
                'starts_at' => 345678.123,
                'ends_at' => 345678.123,
                'vehicle_id' => 2,
                'daily_sale_id' => 2,
                'sales_handover_id' => 2,
            ],
            [
                'starts_at' => 456789.789,
                'ends_at' => 456789.789,
                'vehicle_id' => 3,
                'daily_sale_id' => 3,
                'sales_handover_id' => 3,
            ],
            [
                'starts_at' => 567890.234,
                'ends_at' => 567890.234,
                'vehicle_id' => 4,
                'daily_sale_id' => 4,
                'sales_handover_id' => 4,
            ],
            [
                'starts_at' => 678901.567,
                'ends_at' => 678901.567,
                'vehicle_id' => 4,
                'daily_sale_id' => 4,
                'sales_handover_id' => 5,
            ],
            [
                'starts_at' => 789012.890,
                'ends_at' => 789012.890,
                'vehicle_id' => 3,
                'daily_sale_id' => 3,
                'sales_handover_id' => 6,
            ],
            [
                'starts_at' => 890123.123,
                'ends_at' => 890123.123,
                'vehicle_id' => 2,
                'daily_sale_id' => 2,
                'sales_handover_id' => 1,
            ],
            [
                'starts_at' => 901234.456,
                'ends_at' => 901234.456,
                'vehicle_id' => 1,
                'daily_sale_id' => 1,
                'sales_handover_id' => 2,
            ],
            [
                'starts_at' => 123456.789,
                'ends_at' => 123456.789,
                'vehicle_id' => 2,
                'daily_sale_id' => 2,
                'sales_handover_id' => 4,
            ],
            [
                'starts_at' => 234567.012,
                'ends_at' => 234567.012,
                'vehicle_id' => 1,
                'daily_sale_id' => 1,
                'sales_handover_id' => 4,
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($dailysalesodoreading as $key => $brand) {
            $dailysalesodoreading[$key]['created_at'] = $now;
            $dailysalesodoreading[$key]['updated_at'] = $now;
        }

        \App\DailySalesOdoReading::insert($dailysalesodoreading);
    }
}