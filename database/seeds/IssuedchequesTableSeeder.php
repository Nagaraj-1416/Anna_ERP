<?php

use Illuminate\Database\Seeder;

class IssuedchequesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $issuedcheques = [
            [
                'registered_date' => \Carbon\Carbon::now(),
                'type' => 'Auto',
                'amount' => '1500',
                'cheque_date' => \Carbon\Carbon::now(),
                'cheque_no' => '255325',
                'bank_id' => 1,
                'chequeable_id' => 1,
                'chequeable_type' => 'Own',
                'supplier_id' => 1,
                'customer_id' => 1,
                'notes' => 'Issues Cheque 1',
                'status' => 'Deposited',
                'credited_to' => 1,
                'deposited_to' => 2,
                'prepared_by' => 1,
                'company_id' => 1
            ],
            [
                'registered_date' => \Carbon\Carbon::now(),
                'type' => 'Auto',
                'amount' => '2000',
                'cheque_date' => \Carbon\Carbon::now(),
                'cheque_no' => '255326',
                'bank_id' => 2,
                'chequeable_id' => 2,
                'chequeable_type' => 'Own',
                'supplier_id' => 1,
                'customer_id' => 1,
                'notes' => 'Issues Cheque 2',
                'status' => 'Deposited',
                'credited_to' => 2,
                'deposited_to' => 3,
                'prepared_by' => 1,
                'company_id' => 1
            ],
            [
                'registered_date' => \Carbon\Carbon::now(),
                'type' => 'Auto',
                'amount' => '2500',
                'cheque_date' => \Carbon\Carbon::now(),
                'cheque_no' => '255327',
                'bank_id' => 3,
                'chequeable_id' => 3,
                'chequeable_type' => 'Own',
                'supplier_id' => 1,
                'customer_id' => 1,
                'notes' => 'Issues Cheque 3',
                'status' => 'Deposited',
                'credited_to' => 3,
                'deposited_to' => 4,
                'prepared_by' => 1,
                'company_id' => 1
            ],
            [
                'registered_date' => \Carbon\Carbon::now(),
                'type' => 'Auto',
                'amount' => '3000',
                'cheque_date' => \Carbon\Carbon::now(),
                'cheque_no' => '255328',
                'bank_id' => 4,
                'chequeable_id' => 4,
                'chequeable_type' => 'Own',
                'supplier_id' => 1,
                'customer_id' => 1,
                'notes' => 'Issues Cheque 4',
                'status' => 'Deposited',
                'credited_to' => 4,
                'deposited_to' => 5,
                'prepared_by' => 1,
                'company_id' => 1
            ],
            [
                'registered_date' => \Carbon\Carbon::now(),
                'type' => 'Auto',
                'amount' => '3500',
                'cheque_date' => \Carbon\Carbon::now(),
                'cheque_no' => '255329',
                'bank_id' => 5,
                'chequeable_id' => 5,
                'chequeable_type' => 'Own',
                'supplier_id' => 1,
                'customer_id' => 1,
                'notes' => 'Issues Cheque 5',
                'status' => 'Deposited',
                'credited_to' => 5,
                'deposited_to' => 6,
                'prepared_by' => 1,
                'company_id' => 2
            ],
        ];
            $now = \Carbon\Carbon::now();
        foreach ($issuedcheques as $key => $issuedcheque) {
            $issuedcheques[$key]['created_at'] = $now;
            $issuedcheques[$key]['updated_at'] = $now;
        }

        \App\IssuedCheque::insert($issuedcheques);
    }
}