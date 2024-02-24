<?php

use Illuminate\Database\Seeder;

class AllowancehistoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allowanceHistories = [
            [
                'date' => '2024-02-01',
                'amount' => 150.00,
                'notes' => 'Received travel allowance for January.',
                'received_by' => 1,
                'given_by' => 1,
                'allowance_id' => 1,
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-05',
                'amount' => 200.00,
                'notes' => 'Received meal allowance for the week.',
                'received_by' => 1,
                'given_by' => 2,
                'allowance_id' => 2,
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-10',
                'amount' => 250.00,
                'notes' => 'Received housing allowance for February.',
                'received_by' => 1,
                'given_by' => 3,
                'allowance_id' => 3,
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-15',
                'amount' => 300.00,
                'notes' => 'Received transport allowance for the week.',
                'received_by' => 1,
                'given_by' => 4,
                'allowance_id' => 4,
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-20',
                'amount' => 180.00,
                'notes' => 'Received miscellaneous allowance for February.',
                'received_by' => 1,
                'given_by' => 5,
                'allowance_id' => 5,
                'company_id' => 1,
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($allowanceHistories as $key => $allowanceHistory) {
            $allowanceHistories[$key]['created_at'] = $now;
            $allowanceHistories[$key]['updated_at'] = $now;
        }

        \App\AllowanceHistory::insert($allowanceHistories);
    }
}
