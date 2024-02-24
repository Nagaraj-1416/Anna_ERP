<?php

use Illuminate\Database\Seeder;

class RepvehiclehistoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $daily_sales = [
            [
                'vehicle_id' => 1,
                'rep_id' => 1,
                'assigned_date' => '2024-02-01',
                'revoked_date' => '2024-02-01',
                'blocked_date' => null,
                'status' => 'Active',
            ],
            [
                'vehicle_id' => 2,
                'rep_id' => 2,
                'assigned_date' => '2024-02-02',
                'revoked_date' => '2024-02-02',
                'blocked_date' => null,
                'status' => 'Active',
            ],
            [
                'vehicle_id' => 3,
                'rep_id' => 3,
                'assigned_date' => '2024-02-03',
                'revoked_date' => '2024-02-03',
                'blocked_date' => null,
                'status' => 'Revoked',
            ],
            [
                'vehicle_id' => 3,
                'rep_id' => 4,
                'assigned_date' => '2024-02-04',
                'revoked_date' => '2024-02-04',
                'blocked_date' => null,
                'status' => 'Revoked',
            ],
            [
                'vehicle_id' => 2,
                'rep_id' => 5,
                'assigned_date' => '2024-02-05',
                'revoked_date' => '2024-02-05',
                'blocked_date' => null,
                'status' => 'Active',
            ],
        
        ];

        $now = \Carbon\Carbon::now();
        foreach ($daily_sales as $key => $daily_sale) {
            $daily_sales[$key]['created_at'] = $now;
            $daily_sales[$key]['updated_at'] = $now;
        }

        \App\RepVehicleHistory::insert($daily_sales);
    }
}
