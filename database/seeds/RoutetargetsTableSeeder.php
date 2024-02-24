<?php

use Illuminate\Database\Seeder;

class RoutetargetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $route_targets = [
            [
                'type' => 'Yearly',
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'target' => '1000',
                'achieved' => '800',
                'route_id' => 1,
                'is_active' => 'Yes',
            ],
            [
                'type' => 'Monthly',
                'start_date' => '2024-02-01',
                'end_date' => '2024-02-29',
                'target' => '200',
                'achieved' => '180',
                'route_id' => 2,
                'is_active' => 'Yes',
            ],
            [
                'type' => 'Weekly',
                'start_date' => '2024-02-01',
                'end_date' => '2024-02-07',
                'target' => '50',
                'achieved' => '45',
                'route_id' => 3,
                'is_active' => 'Yes',
            ],
            [
                'type' => 'Daily',
                'start_date' => '2024-02-01',
                'end_date' => '2024-02-01',
                'target' => '10',
                'achieved' => '8',
                'route_id' => 4,
                'is_active' => 'Yes',
            ],
            [
                'type' => 'Yearly',
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'target' => '1200',
                'achieved' => '950',
                'route_id' => 5,
                'is_active' => 'Yes',
            ],
        
        ];

        $now = \Carbon\Carbon::now();
        foreach ($route_targets as $key => $route_target) {
            $route_targets[$key]['created_at'] = $now;
            $route_targets[$key]['updated_at'] = $now;
        }

        \App\RouteTarget::insert($route_targets);
    }
}
