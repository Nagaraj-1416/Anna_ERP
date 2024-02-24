<?php

use Illuminate\Database\Seeder;

class BusinessTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $businesstypes = [
            [
                'code' => '00001',
                'name' => 'Commercial',
                'notes' => 'notes entry for the commercial entries',
                'is_active' => 'Yes'
            ],
            [
                'code' => '00002',
                'name' => 'Export',
                'notes' => 'notes entry for the commercial entries',
                'is_active' => 'Yes'
            ],
            [
                'code' => '00003',
                'name' => 'Import',
                'notes' => 'notes entry for the commercial entries',
                'is_active' => 'Yes'
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($businesstypes as $key => $businesstype) {
            $businesstypes[$key]['created_at'] = $now;
            $businesstypes[$key]['updated_at'] = $now;
        }

        \App\BusinessType::insert($businesstypes);
    }
}
