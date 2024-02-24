<?php

use Illuminate\Database\Seeder;

class ExpensechequesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $expensecheques = [
            [
                'amount' => '2500.00',
                'expense_payment_id' => 1,
                'cheque_in_hand_id' => 1,
                'expense_id' => 1
            ],
            [
                'amount' => '3000.00',
                'expense_payment_id' => 2,
                'cheque_in_hand_id' => 2,
                'expense_id' => 2
            ],
            [
                'amount' => '3500.00',
                'expense_payment_id' => 3,
                'cheque_in_hand_id' => 3,
                'expense_id' => 3
            ],
            [
                'amount' => '4000.00',
                'expense_payment_id' => 4,
                'cheque_in_hand_id' => 4,
                'expense_id' => 4
            ],
            [
                'amount' => '4500.00',
                'expense_payment_id' => 5,
                'cheque_in_hand_id' => 1,
                'expense_id' => 1
            ],
            [
                'amount' => '5000.00',
                'expense_payment_id' => 6,
                'cheque_in_hand_id' => 2,
                'expense_id' => 2
            ],
            [
                'amount' => '5500.00',
                'expense_payment_id' => 7,
                'cheque_in_hand_id' => 2,
                'expense_id' => 2
            ],
            [
                'amount' => '6000.00',
                'expense_payment_id' => 8,
                'cheque_in_hand_id' => 3,
                'expense_id' => 3
            ],
            [
                'amount' => '6500.00',
                'expense_payment_id' => 9,
                'cheque_in_hand_id' => 4,
                'expense_id' => 4
            ],
            [
                'amount' => '7000.00',
                'expense_payment_id' => 10,
                'cheque_in_hand_id' => 5,
                'expense_id' => 5
            ],
        ];
            $now = \Carbon\Carbon::now();
            foreach ($expensecheques as $key => $expensecheque) {
                $expensecheques[$key]['created_at'] = $now;
                $expensecheques[$key]['updated_at'] = $now;
            }
    
            \App\ExpenseCheque::insert($expensecheques); 
    }
}