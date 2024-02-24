<?php

use Illuminate\Database\Seeder;

class SalesexpensesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $salesexpenses=[
            [
                'code' =>'SE0001',
                'expense_date' => '2024-02-21',
                'expense_time' =>'17:46:00',
                'calculate_mileage_using' => 'Distance',
                'notes' => 'null',
                'amount' => '600',
                'gps_lat' => '',
                'gps_long' => '',
                'liter' => '3',
                'odometer' => '160648',
                'distance' => '20',
                'start_reading' => '160628',
                'end_reading' => '160648',
                'status' => 'submitted',
                'prepared_by' => 1,
                'approved_by' => 1,
                'staff_id' => 1,
                'company_id' => 1,
                'daily_sale_id' => 1,
                'sales_handover_id' =>1,
                'type_id' => 1,
            ],
            [
                'code' =>'SE0002',
                'expense_date' => '2024-02-21',
                'expense_time' =>'17:48:00',
                'calculate_mileage_using' => 'Distance',
                'notes' => 'null',
                'amount' => '1000',
                'gps_lat' => '',
                'gps_long' => '',
                'liter' => '5',
                'odometer' => '29623',
                'distance' => '15',
                'start_reading' => '29608',
                'end_reading' => '29623',
                'status' => 'submitted',
                'prepared_by' => 1,
                'approved_by' => 1,
                'staff_id' => 1,
                'company_id' => 1,
                'daily_sale_id' => 1,
                'sales_handover_id' =>1,
                'type_id' => 1,
            ],
            [
                'code' =>'SE0003',
                'expense_date' => '2024-02-21',
                'expense_time' =>'17:54:00',
                'calculate_mileage_using' => 'Distance',
                'notes' => 'null',
                'amount' => '400',
                'gps_lat' => '',
                'gps_long' => '',
                'liter' => '2',
                'odometer' => '160648',
                'distance' => '20',
                'start_reading' => '160628',
                'end_reading' => '160648',
                'status' => 'submitted',
                'prepared_by' => 1,
                'approved_by' => 1,
                'staff_id' => 1,
                'company_id' => 1,
                'daily_sale_id' => 1,
                'sales_handover_id' =>1,
                'type_id' => 1,
            ],
            [
                'code' =>'SE0004',
                'expense_date' => '2024-02-21',
                'expense_time' =>'17:56:00',
                'calculate_mileage_using' => 'Distance',
                'notes' => 'null',
                'amount' => '1200',
                'gps_lat' => '',
                'gps_long' => '',
                'liter' => '6',
                'odometer' => '235977',
                'distance' => '20',
                'start_reading' => '235957',
                'end_reading' => '235977',
                'status' => 'submitted',
                'prepared_by' => 1,
                'approved_by' => 1,
                'staff_id' => 1,
                'company_id' => 1,
                'daily_sale_id' => 1,
                'sales_handover_id' =>1,
                'type_id' => 1,
            ],
            [
                'code' =>'SE0005',
                'expense_date' => '2024-02-21',
                'expense_time' =>'17:42:00',
                'calculate_mileage_using' => 'Distance',
                'notes' => 'null',
                'amount' => '600',
                'gps_lat' => '',
                'gps_long' => '',
                'liter' => '3',
                'odometer' => '057612',
                'distance' => '10',
                'start_reading' => '057602',
                'end_reading' => '057612',
                'status' => 'submitted',
                'prepared_by' => 1,
                'approved_by' => 1,
                'staff_id' => 1,
                'company_id' => 1,
                'daily_sale_id' => 1,
                'sales_handover_id' =>1,
                'type_id' => 1,
            ],

            ];
            $now = \Carbon\Carbon::now();
        foreach ($salesexpenses as $key => $salesexpense) {
            $salesexpenses[$key]['created_at'] = $now;
            $salesexpenses[$key]['updated_at'] = $now;
        }
        \App\sales_expense::insert($salesexpenses);

    }
}