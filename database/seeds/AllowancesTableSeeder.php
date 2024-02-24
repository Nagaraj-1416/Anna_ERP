<?php

use Illuminate\Database\Seeder;

class AllowancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allowances = [
            [
                'assigned_date' => '2024-02-01',
                'amount' => 300.00,
                'assigned_by' => 1,
                'is_active' => 'Yes',
                'notes' => 'Travel allowance for February',
                'allowanceable_type' => 'Employee',
                'allowanceable_id' => 1,
                'company_id' => 1,
            ],
            [
                'assigned_date' => '2024-02-05',
                'amount' => 250.00,
                'assigned_by' => 2,
                'is_active' => 'Yes',
                'notes' => 'Meal allowance for the week',
                'allowanceable_type' => 'Employee',
                'allowanceable_id' => 2,
                'company_id' => 1,
            ],
            [
                'assigned_date' => '2024-02-10',
                'amount' => 400.00,
                'assigned_by' => 3,
                'is_active' => 'Yes',
                'notes' => 'Housing allowance for February',
                'allowanceable_type' => 'Employee',
                'allowanceable_id' => 3,
                'company_id' => 1,
            ],
            [
                'assigned_date' => '2024-02-15',
                'amount' => 200.00,
                'assigned_by' => 4,
                'is_active' => 'Yes',
                'notes' => 'Transport allowance for the week',
                'allowanceable_type' => 'Employee',
                'allowanceable_id' => 4,
                'company_id' => 1,
            ],
            [
                'assigned_date' => '2024-02-20',
                'amount' => 350.00,
                'assigned_by' => 5,
                'is_active' => 'Yes',
                'notes' => 'Miscellaneous allowance for February',
                'allowanceable_type' => 'Employee',
                'allowanceable_id' => 5,
                'company_id' => 1,
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($allowances as $key => $allowance) {
            $allowances[$key]['created_at'] = $now;
            $allowances[$key]['updated_at'] = $now;
        }

        \App\Allowance::insert($allowances);
    }
}
