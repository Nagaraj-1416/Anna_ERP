<?php

use Illuminate\Database\Seeder;

class ExpenseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            [
                'name' => 'Mileage',
                'description' => 'Mileage',
                'is_active' => 'Yes',
                'is_deletable' => 'No',
                'is_mobile_enabled' => 'Yes',
                'account_id' => 28
            ],
            [
                'name' => 'Fuel',
                'description' => 'Fuel',
                'is_active' => 'Yes',
                'is_deletable' => 'No',
                'is_mobile_enabled' => 'Yes',
                'account_id' => 28
            ],
            [
                'name' => 'Allowance',
                'description' => 'Allowance',
                'is_active' => 'Yes',
                'is_deletable' => 'No',
                'is_mobile_enabled' => 'Yes',
                'account_id' => 36
            ],
            [
                'name' => 'General',
                'description' => 'General',
                'is_active' => 'Yes',
                'is_deletable' => 'No',
                'is_mobile_enabled' => 'Yes',
                'account_id' => 36
            ],
            [
                'name' => 'Parking',
                'description' => 'Parking',
                'is_active' => 'Yes',
                'is_deletable' => 'No',
                'is_mobile_enabled' => 'Yes',
                'account_id' => 36
            ],
            [
                'name' => 'Repairs',
                'description' => 'Repairs',
                'is_active' => 'Yes',
                'is_deletable' => 'No',
                'is_mobile_enabled' => 'Yes',
                'account_id' => 36
            ]
        ];
        $now = \Carbon\Carbon::now();
        foreach ($types as $key => $type) {
            $types[$key]['created_at'] = $now;
            $types[$key]['updated_at'] = $now;
        }
        \App\ExpenseType::insert($types);
    }
}
