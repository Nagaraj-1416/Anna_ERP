<?php

use Illuminate\Database\Seeder;

class CashiershiftsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cashier_shifts = [
            [
                'amount' => 5676,
                'shift_from' => '2024-02-22 09:30:00',
                'shift_to' => '2024-02-22 16:31:00',
                'shifted_by' => 1,
                'shifted_to' => 1,
            ],
            [
                'amount' => 6000,
                'shift_from' => '2024-02-22 11:30:00',
                'shift_to' => '2024-02-22 17:30:00',
                'shifted_by' => 1,
                'shifted_to' => 1,
            ],
            [
                'amount' => 6200,
                'shift_from' => '2024-02-22 02:00:00',
                'shift_to' => '2024-02-22 06:30:00',
                'shifted_by' => 1,
                'shifted_to' => 1,
            ],
            [
                'amount' => 5400,
                'shift_from' => '2024-02-22 08:30:00',
                'shift_to' => '2024-02-22 14:30:00',
                'shifted_by' => 1,
                'shifted_to' => 1,
            ],
            [
                'amount' => 5800,
                'shift_from' => '2024-02-22 17:30:00',
                'shift_to' => '2024-02-22 21:30:00',
                'shifted_by' => 1,
                'shifted_to' => 1,
            ],
        ];
        

        $now = \Carbon\Carbon::now();
        foreach ($cashier_shifts as $key => $add) {
            $cashier_shifts[$key]['created_at'] = $now;
            $cashier_shifts[$key]['updated_at'] = $now;
        }

        \App\CashierShift::insert($cashier_shifts);
    }
}