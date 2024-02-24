<?php

use Illuminate\Database\Seeder;

class ExpensecategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $expensecategories =[
            [
                'name' =>'Labour',
                'notes' => 'added Labour cost',
                'is_default' => 'yes',
                'is_active' => 'yes' ,
            ],
            [
                'name' =>'Advertising',
                'notes' => 'added Advertising cost',
                'is_default' => 'yes',
                'is_active' => 'yes' ,
            ],
            [
                'name' =>'Employee Benefits',
                'notes' => 'added employee salary',
                'is_default' => 'yes',
                'is_active' => 'yes' ,
            ],
            [
                'name' =>'Bank fees',
                'notes' => 'added bank fees',
                'is_default' => 'yes',
                'is_active' => 'yes' ,
            ],
            [
                'name' =>'Vehicle',
                'notes' => 'added vehicle cost',
                'is_default' => 'yes',
                'is_active' => 'yes' ,
            ],
            [
                'name' =>'Taxes',
                'notes' => 'added taxes',
                'is_default' => 'yes',
                'is_active' => 'yes' ,
            ],
            [
                'name' =>'Membership fees',
                'notes' => 'added membership fees',
                'is_default' => 'yes',
                'is_active' => 'yes' ,
            ],
            [
                'name' =>'Business meals',
                'notes' => 'added business meals',
                'is_default' => 'yes',
                'is_active' => 'yes' ,
            ],
            [
                'name' =>'Equipment',
                'notes' => 'added equipment cost',
                'is_default' => 'yes',
                'is_active' => 'yes' ,
            ],
            [
                'name' =>'Fixed expenses',
                'notes' => 'added fiex expenses cost',
                'is_default' => 'yes',
                'is_active' => 'yes' ,
            ]
            ];
            $now = \Carbon\Carbon::now();
        foreach ($expensecategories as $key => $type) {
            $expensecategories[$key]['created_at'] = $now;
            $expensecategories[$key]['updated_at'] = $now;
        }
        \App\ExpenseCategory::insert($expensecategories);

    }
}