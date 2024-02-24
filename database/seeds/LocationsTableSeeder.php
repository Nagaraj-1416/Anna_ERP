<?php

use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        {
            $locations = [
                [
                    'code'=>'L00001',
                    'name'=>'Location 1',
                    'notes'=>'notes for the entry of the locations',
                    'route_id'=>1,
                    'is_active'=>1,
                ],
                [
                    'code'=>'L00002',
                    'name'=>'Location 2',
                    'notes'=>'notes for the entry of the locations',
                    'route_id'=>2,
                    'is_active'=>1,
                ],
                [
                    'code'=>'L00003',
                    'name'=>'Location 3',
                    'notes'=>'notes for the entry of the locations',
                    'route_id'=>3,
                    'is_active'=>1,
                ],
                [
                    'code'=>'L00001',
                    'name'=>'Location 1',
                    'notes'=>'notes for the entry of the locations',
                    'route_id'=>1,
                    'is_active'=>1,
                ],
                [
                    'code'=>'L00004',
                    'name'=>'Location 4',
                    'notes'=>'notes for the entry of the locations',
                    'route_id'=>4,
                    'is_active'=>1,
                ],
                [
                    'code'=>'L00005',
                    'name'=>'Location 5',
                    'notes'=>'notes for the entry of the locations',
                    'route_id'=>5,
                    'is_active'=>1,
                ],
            ];
    
            $now = \Carbon\Carbon::now();
            foreach ($locations as $key => $add) {
                $locations[$key]['created_at'] = $now;
                $locations[$key]['updated_at'] = $now;
            }
    
            \App\Location::insert($locations);
        }
        
    }
}
