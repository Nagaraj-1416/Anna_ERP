<?php

use Illuminate\Database\Seeder;

class RoutesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $routes = [
            [
                'code'=>	'R00001',
                'name'=>	'Route 1',
                'notes'=>	'notes for the entry of the notes',
                'start_point'=>	'st point 1',
                'end_point'=>	'en point 1',
                'way_points'=>	'way point 1',
                'cl_amount'=>253.67,
                'cl_notify_rate'=>253.67,
                'is_active'=>1,
                'company_id'=>	1,
            ],
            [
                'code'=>	'R00002',
                'name'=>	'Route 2',
                'notes'=>	'notes for the entry of the notes',
                'start_point'=>	'st point 2',
                'end_point'=>	'en point 2',
                'way_points'=>	'way point 2',
                'cl_amount'=>253.67,
                'cl_notify_rate'=>253.67,
                'is_active'=>1,
                'company_id'=>	1,
            ],
            [
                'code'=>	'R00003',
                'name'=>	'Route 3',
                'notes'=>	'notes for the entry of the notes',
                'start_point'=>	'st point 3',
                'end_point'=>	'en point 3',
                'way_points'=>	'way point 3',
                'cl_amount'=>253.67,
                'cl_notify_rate'=>253.67,
                'is_active'=>1,
                'company_id'=>	1,
            ],
            [
                'code'=>	'R00004',
                'name'=>	'Route 4',
                'notes'=>	'notes for the entry of the notes',
                'start_point'=>	'st point 4',
                'end_point'=>	'en point 4',
                'way_points'=>	'way point 4',
                'cl_amount'=>253.67,
                'cl_notify_rate'=>253.67,
                'is_active'=>1,
                'company_id'=>	1,
            ],
            [
                'code'=>	'R00005',
                'name'=>	'Route 5',
                'notes'=>	'notes for the entry of the notes',
                'start_point'=>	'st point 5',
                'end_point'=>	'en point 5',
                'way_points'=>	'way point 5',
                'cl_amount'=>253.67,
                'cl_notify_rate'=>253.67,
                'is_active'=>1,
                'company_id'=>	1,
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($routes as $key => $add) {
            $routes[$key]['created_at'] = $now;
            $routes[$key]['updated_at'] = $now;
        }

        \App\Route::insert($routes);
    }
}
