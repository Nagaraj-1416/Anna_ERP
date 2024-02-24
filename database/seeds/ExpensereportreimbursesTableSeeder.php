<?php

use Illuminate\Database\Seeder;

class ExpensereportreimbursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $expensereportreimburses=[
            [
                'reimbursed_on'=> '2024-02-21',
                'notes' => 'addexpensereport',
                'amount' => '500',
                'paid_through' => 1,
                'report_id' => 1,
            ],
            [
                'reimbursed_on'=> '2024-02-21',
                'notes' => 'addexpensereport',
                'amount' => '1000',
                'paid_through' => 1,
                'report_id' => 2,
            ],
            [
                'reimbursed_on'=> '2024-02-21',
                'notes' => 'addexpensereport',
                'amount' => '1500',
                'paid_through' => 1,
                'report_id' => 1,
            ],
            [
                'reimbursed_on'=> '2024-02-21',
                'notes' => 'addexpensereport',
                'amount' => '2500',
                'paid_through' => 3,
                'report_id' => 3,
            ],
            [
                'reimbursed_on'=> '2024-02-21',
                'notes' => 'addexpensereport',
                'amount' => '3500',
                'paid_through' => 4,
                'report_id' => 4,
            ],
            [
                'reimbursed_on'=> '2024-02-21',
                'notes' => 'addexpensereport',
                'amount' => '4500',
                'paid_through' => 4,
                'report_id' => 4,
            ],
            [
                'reimbursed_on'=> '2024-02-21',
                'notes' => 'addexpensereport',
                'amount' => '800',
                'paid_through' => 1,
                'report_id' => 4,
            ],
            [
                'reimbursed_on'=> '2024-02-21',
                'notes' => 'addexpensereport',
                'amount' => '950',
                'paid_through' => 2,
                'report_id' => 4,
            ],
            [
                'reimbursed_on'=> '2024-02-21',
                'notes' => 'addexpensereport',
                'amount' => '850',
                'paid_through' => 1,
                'report_id' => 3,
            ],
            [
                'reimbursed_on'=> '2024-02-21',
                'notes' => 'addexpensereport',
                'amount' => '850',
                'paid_through' => 3,
                'report_id' => 4,
            ]
            ];
            $now = \Carbon\Carbon::now();
        foreach ($expensereportreimburses as $key => $expensereportreimburse) {
                 $expensereportreimburses[$key]['created_at'] = $now;
                $expensereportreimburses[$key]['updated_at'] = $now;
                }
        
        \App\ExpenseReportReimburse::insert($expensereportreimburses);
    }
}