<?php

use Illuminate\Database\Seeder;

class ExpenseitemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $expenseitems= [
            [
                'expense_id' => 3,
                'category_id' => 1,
                'expense_account' => 1,
                'notes' =>' item1 added',
                'amount' => '1000'
            ],
            [
                'expense_id' => 4,
                'category_id' => 1,
                'expense_account' => 1,
                'notes' =>' item2 added',
                'amount' => '1200'
            ],
            [
                'expense_id' => 5,
                'category_id' => 1,
                'expense_account' => 1,
                'notes' =>' item3 added',
                'amount' => '800'
            ],
            [
                'expense_id' => 2,
                'category_id' => 1,
                'expense_account' => 1,
                'notes' =>' item4 added',
                'amount' => '600'
            ],
            [
                'expense_id' => 3,
                'category_id' => 1,
                'expense_account' => 1,
                'notes' =>' item5 added',
                'amount' => '500'
            ],
            [
                'expense_id' => 4,
                'category_id' => 3,
                'expense_account' => 1,
                'notes' =>' item6 added',
                'amount' => '600'
            ],
            [
                'expense_id' => 5,
                'category_id' => 4,
                'expense_account' => 1,
                'notes' =>' item7 added',
                'amount' => '1200'
            ],
            [
                'expense_id' => 3,
                'category_id' => 1,
                'expense_account' => 1,
                'notes' =>' item8 added',
                'amount' => '450'
            ],
            [
                'expense_id' => 5,
                'category_id' => 1,
                'expense_account' => 1,
                'notes' =>' item9 added',
                'amount' => '650'
            ],
            [
                'expense_id' => 3,
                'category_id' => 2,
                'expense_account' => 1,
                'notes' =>' item10 added',
                'amount' => '750'
            ],
        ];
        $now = \Carbon\Carbon::now();
        foreach ($expenseitems as $key => $expenseitem) {
            $expenseitems[$key]['created_at'] = $now;
            $expenseitems[$key]['updated_at'] = $now;
        }
        \App\ExpenseItem::insert($expenseitems);
    }
}