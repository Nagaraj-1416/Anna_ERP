<?php

use Illuminate\Database\Seeder;

class AccountCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'code' => 'AC0000001',
                'name' => 'Assets',
                'balance_type' => 'Debit',
                'notes' => 'Assets',
                'is_default' => 'Yes',
                'is_active' => 'Yes'
            ],
            [
                'code' => 'AC0000002',
                'name' => 'Liabilities',
                'balance_type' => 'Credit',
                'notes' => 'Liabilities & Credit Cards',
                'is_default' => 'Yes',
                'is_active' => 'Yes'
            ],
            [
                'code' => 'AC0000003',
                'name' => 'Income',
                'balance_type' => 'Credit',
                'notes' => 'Income',
                'is_default' => 'Yes',
                'is_active' => 'Yes'
            ],
            [
                'code' => 'AC0000004',
                'name' => 'Expenses',
                'balance_type' => 'Debit',
                'notes' => 'Expenses',
                'is_default' => 'Yes',
                'is_active' => 'Yes'
            ],
            [
                'code' => 'AC0000005',
                'name' => 'Equity',
                'balance_type' => 'Credit',
                'notes' => 'Equity',
                'is_default' => 'Yes',
                'is_active' => 'Yes'
            ]
        ];
        $now = \Carbon\Carbon::now();
        foreach ($categories as $key => $category) {
            $categories[$key]['created_at'] = $now;
            $categories[$key]['updated_at'] = $now;
        }
        \App\AccountCategory::insert($categories);
    }
}
