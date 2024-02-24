<?php

use Illuminate\Database\Seeder;

class ExpensesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $expenses=[
            [
                'expense_no' => 'E0001',
                'expense_date' => '2024-02-21',
                'claim_reimburse' => 'Yes' ,
                'expense_items'=> 'Single',
                'calculate_mileage_using' => 'Distance',
                'notes' => 'null',
                'amount' => '1000',
                'distance' => '20',
                'start_reading' => '057602',
                'end_reading' => '057612',
                'status'=> 'Unreported',
                'type_id'=> 1,
                'expense_account' => 1,
                'paid_through'=> 1,
                'prepared_by'=> 1,
                'approved_by' => 1,
                'supplier_id'=> 1,
                'customer_id' => 1,
                'business_type_id' => 1,
                'staff_id' => 1,
                'company_id' => 1,
                'report_id' => 1,
                'payment_mode' => 'Cash',
                'cheque_no' =>  '524000',
                'cheque_date' => '2024-02-21',
                'account_no' => '69852960001234',
                'deposited_date' => '2024-02-21',
                'bank_id' => 1,
                'gps_lat' => '',
                'gps_long' => '',
                'liter' => '3',
                'odometer' => '16598',
                'sales_expense_id' => 1,
            ],
            [
                'expense_no' => 'E0002',
                'expense_date' => '2024-02-20',
                'claim_reimburse' => 'Yes' ,
                'expense_items'=> 'Single',
                'calculate_mileage_using' => 'Distance',
                'notes' => 'null',
                'amount' => '600',
                'distance' => '20',
                'start_reading' => '160628',
                'end_reading' => '160648',
                'status'=> 'Unreported',
                'type_id'=> 1,
                'expense_account' => 1,
                'paid_through'=> 1,
                'prepared_by'=> 1,
                'approved_by' => 1,
                'supplier_id'=> 1,
                'customer_id' => 1,
                'business_type_id' => 1,
                'staff_id' => 1,
                'company_id' => 1,
                'report_id' => 1,
                'payment_mode' => 'Cash',
                'cheque_no' =>  '632000',
                'cheque_date' => '2024-02-21',
                'account_no' => '69852960003069',
                'deposited_date' => '2024-02-20',
                'bank_id' => 1,
                'gps_lat' => '',
                'gps_long' => '',
                'liter' => '3',
                'odometer' => '160648',
                'sales_expense_id' => 1,
            ],
            [
                'expense_no' => 'E0003',
                'expense_date' => '2024-02-20',
                'claim_reimburse' => 'Yes' ,
                'expense_items'=> 'Single',
                'calculate_mileage_using' => 'Distance',
                'notes' => 'null',
                'amount' => '1000',
                'distance' => '15',
                'start_reading' => '29608',
                'end_reading' => '29623',
                'status'=> 'Unreported',
                'type_id'=> 1,
                'expense_account' => 1,
                'paid_through'=> 1,
                'prepared_by'=> 1,
                'approved_by' => 1,
                'supplier_id'=> 1,
                'customer_id' => 1,
                'business_type_id' => 1,
                'staff_id' => 1,
                'company_id' => 1,
                'report_id' => 2,
                'payment_mode' => 'Cash',
                'cheque_no' =>  '453000',
                'cheque_date' => '2024-02-21',
                'account_no' => '69852960008056',
                'deposited_date' => '2024-02-20',
                'bank_id' => 1,
                'gps_lat' => '',
                'gps_long' => '',
                'liter' => '3',
                'odometer' => '29623',
                'sales_expense_id' => 2,
            ],
            [
                'expense_no' => 'E0004',
                'expense_date' => '2024-02-20',
                'claim_reimburse' => 'Yes' ,
                'expense_items'=> 'Single',
                'calculate_mileage_using' => 'Distance',
                'notes' => 'null',
                'amount' => '1200',
                'distance' => '20',
                'start_reading' => '235957',
                'end_reading' => '235977',
                'status'=> 'Unreported',
                'type_id'=> 2,
                'expense_account' => 1,
                'paid_through'=> 1,
                'prepared_by'=> 1,
                'approved_by' => 1,
                'supplier_id'=> 1,
                'customer_id' => 1,
                'business_type_id' => 1,
                'staff_id' => 1,
                'company_id' => 1,
                'report_id' => 3,
                'payment_mode' => 'Cash',
                'cheque_no' =>  '569000',
                'cheque_date' => '2024-02-21',
                'account_no' => '69852960002375',
                'deposited_date' => '2024-02-20',
                'bank_id' => 1,
                'gps_lat' => '',
                'gps_long' => '',
                'liter' => '3',
                'odometer' => '235977',
                'sales_expense_id' => 2,
            ],
            [
                'expense_no' => 'E0005',
                'expense_date' => '2024-02-20',
                'claim_reimburse' => 'Yes' ,
                'expense_items'=> 'Multiple',
                'calculate_mileage_using' => 'Distance',
                'notes' => 'null',
                'amount' => '600',
                'distance' => '10',
                'start_reading' => '057602',
                'end_reading' => '057612',
                'status'=> 'Unreported',
                'type_id'=> 2,
                'expense_account' => 1,
                'paid_through'=> 1,
                'prepared_by'=> 1,
                'approved_by' => 1,
                'supplier_id'=> 1,
                'customer_id' => 1,
                'business_type_id' => 1,
                'staff_id' => 1,
                'company_id' => 1,
                'report_id' => 1,
                'payment_mode' => 'Cash',
                'cheque_no' =>  '524000',
                'cheque_date' => '2024-02-21',
                'account_no' => '69852960001234',
                'deposited_date' => '2024-02-20',
                'bank_id' => 1,
                'gps_lat' => '',
                'gps_long' => '',
                'liter' => '3',
                'odometer' => '057612',
                'sales_expense_id' => 4,
            ]
            
            ];
            $now = \Carbon\Carbon::now();
        foreach ($expenses as $key => $expense) {
            $expenses[$key]['created_at'] = $now;
            $expenses[$key]['updated_at'] = $now;
        }
        \App\Expense::insert($expenses);
    }
}