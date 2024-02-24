<?php

use Illuminate\Database\Seeder;

class ExpensereportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $expense_reports=[
            [
                'report_no' =>'R0001',
                'title' =>'Accounts',
                'report_from' => '2024-02-21', 
                'report_to' => '2024-02-22', 
                'notes' =>'Accounts report', 
                'amount' =>  '1000', 
                'status' => 'Approved', 
                'prepared_by' => 1,
                'approved_by' => 1, 
                'company_id'=> 1, 
                'business_type_id' => 2, 
                'submitted_on'=> '2024-02-21', 
                'submitted_by' => 1, 
            ],
            [
                'report_no' =>'R0002',
                'title' =>'Suppliers',
                'report_from' => '2024-02-22', 
                'report_to' => '2024-02-23', 
                'notes' =>'Suppliers report', 
                'amount' =>  '2000', 
                'status' => 'Approved', 
                'prepared_by' => 1,
                'approved_by' => 1, 
                'company_id'=> 1, 
                'business_type_id' => 2, 
                'submitted_on'=> '2024-02-21', 
                'submitted_by' => 1, 
            ],
            [
                'report_no' =>'R0003',
                'title' =>'Companies',
                'report_from' => '2024-02-21', 
                'report_to' => '2024-02-22', 
                'notes' =>'Companies report', 
                'amount' =>  '3000', 
                'status' => 'Approved', 
                'prepared_by' => 1,
                'approved_by' => 1, 
                'company_id'=> 1, 
                'business_type_id' => 2, 
                'submitted_on'=> '2024-02-21', 
                'submitted_by' => 1, 
            ],[
                'report_no' =>'R0004',
                'title' =>'Staff',
                'report_from' => '2024-02-23', 
                'report_to' => '2024-02-24', 
                'notes' =>'Staff report', 
                'amount' =>  '4000', 
                'status' => 'Approved', 
                'prepared_by' => 1,
                'approved_by' => 1, 
                'company_id'=> 1, 
                'business_type_id' => 2, 
                'submitted_on'=> '2024-02-23', 
                'submitted_by' => 1, 
            ],
            [
                'report_no' =>'R0005',
                'title' =>'users',
                'report_from' => '2024-02-22', 
                'report_to' => '2024-02-24', 
                'notes' =>'users report', 
                'amount' =>  '10000', 
                'status' => 'Submitted', 
                'prepared_by' => 1,
                'approved_by' => 1, 
                'company_id'=> 1, 
                'business_type_id' => 1, 
                'submitted_on'=> '2024-02-22', 
                'submitted_by' => 1, 
            ],
            [
                'report_no' =>'R0006',
                'title' =>'Customers',
                'report_from' => '2024-02-21', 
                'report_to' => '2024-02-22', 
                'notes' =>'Customers report', 
                'amount' =>  '5000', 
                'status' => 'Rejected', 
                'prepared_by' => 1,
                'approved_by' => 1, 
                'company_id'=> 1, 
                'business_type_id' => 2, 
                'submitted_on'=> '2024-02-21', 
                'submitted_by' => 1, 
            ]
            
            ];
            $now = \Carbon\Carbon::now();
        foreach ($expense_reports as $key => $expense_report) {
            $expense_reports[$key]['created_at'] = $now;
            $expense_reports[$key]['updated_at'] = $now;
        }

        \App\ExpenseReport::insert($expense_reports);
    }
}