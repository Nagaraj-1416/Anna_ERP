<?php

use Illuminate\Database\Seeder;

class BusinesstypeableTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $businesstypeable =[
            [
                'code' => '00001',
                'business_type_id' => 1,
                'businesstypeable_id' => null,
                'businesstypeable_type' => 'type 1'
            ],
            [
                'code' => '00001',
                'business_type_id' => 1,
                'businesstypeable_id' => 1,
                'businesstypeable_type' => 'type 2'
            ],
            [
                'code' => '00001',
                'business_type_id' => 2,
                'businesstypeable_id' => 1,
                'businesstypeable_type' => 'type 3'
            ],
            [
                'code' => '00001',
                'business_type_id' => 3,
                'businesstypeable_id' => null,
                'businesstypeable_type' => 'type 1'
            ],
           
        ];

        $now = \Carbon\Carbon::now();
        foreach ($businesstypeable as $key => $add) {
            $businesstypeable[$key]['created_at'] = $now;
            $businesstypeable[$key]['updated_at'] = $now;
        }

        // \App\Bill::insert($businesstypeable);
    }
}
