<?php

use Illuminate\Database\Seeder;

class StaffableTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $staffs = [
            [
                'staff_id' => 1,
                'staffable_id' => 1,
                'staffable_type' => 'admin type',
                'is_head' => 'Yes',
                'is_default' => 'Yes',
            ],
        ];
        $now = \Carbon\Carbon::now();
        foreach ($staffs as $key => $user) {
            $staffs[$key]['created_at'] = $now;
            $staffs[$key]['updated_at'] = $now;
        }
        \App\Staff::insert($staffs);
        // Assign staff to company
    }
}
