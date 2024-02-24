<?php

use Illuminate\Database\Seeder;

class SalescommissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $salescommissions = [
            [
                'date' => '2024-02-20',
                'year' => '2024',
                'month' => '02',
                'credit_sales' => 566.456,
                'cheque_received' => '789.456',
                'cheque_collection_dr' => '965.456',
                'cheque_returned' => '123.456',
                'sales_returned' => '650.00',
                'sales_target' => '1000',
                'special_target' => '250',
                'total_sales' => '550',
                'cash_collection' => '105000.00',
                'cheque_collection_cr' => '12500.00',
                'cheque_realized' => '10000.00',
                'customer_visited_count' => '150',
                'customer_visited_rate' => '12',
                'customer_visited' => '25',
                'product_sold_count' => '25',
                'product_sold_rate' => '5',
                'product_sold' => '25',
                'debit_balance' => '60000.00',
                'credit_balance' => '25000.00',
                'status' => 'Approved',
                'notes' => 'First Sales commissions',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-22',
                'approved_by' => 1,
                'approved_on' => '2024-02-22',
                'staff_id' => 1,
                'rep_id' => 1,
                'company_id' => 2,
            ],
            [
                'date' => '2024-03-15',
                'year' => '2024',
                'month' => '03',
                'credit_sales' => 700.345,
                'cheque_received' => '900.123',
                'cheque_collection_dr' => '1000.567',
                'cheque_returned' => '200.678',
                'sales_returned' => '800.00',
                'sales_target' => '1200',
                'special_target' => '300',
                'total_sales' => '700',
                'cash_collection' => '110000.00',
                'cheque_collection_cr' => '15000.00',
                'cheque_realized' => '12000.00',
                'customer_visited_count' => '180',
                'customer_visited_rate' => '15',
                'customer_visited' => '30',
                'product_sold_count' => '30',
                'product_sold_rate' => '6',
                'product_sold' => '30',
                'debit_balance' => '70000.00',
                'credit_balance' => '30000.00',
                'status' => 'Approved',
                'notes' => 'Second Sales commissions',
                'prepared_by' => 1,
                'prepared_on' => '2024-03-16',
                'approved_by' => 1,
                'approved_on' => '2024-03-17',
                'staff_id' => 1,
                'rep_id' => 2,
                'company_id' => 3,
            ],
            [
                'date' => '2024-04-10',
                'year' => '2024',
                'month' => '04',
                'credit_sales' => 800.678,
                'cheque_received' => '1000.789',
                'cheque_collection_dr' => '1100.234',
                'cheque_returned' => '300.890',
                'sales_returned' => '900.00',
                'sales_target' => '1500',
                'special_target' => '400',
                'total_sales' => '850',
                'cash_collection' => '115000.00',
                'cheque_collection_cr' => '17500.00',
                'cheque_realized' => '15000.00',
                'customer_visited_count' => '200',
                'customer_visited_rate' => '20',
                'customer_visited' => '35',
                'product_sold_count' => '35',
                'product_sold_rate' => '7',
                'product_sold' => '35',
                'debit_balance' => '80000.00',
                'credit_balance' => '35000.00',
                'status' => 'Approved',
                'notes' => 'Third Sales commissions',
                'prepared_by' => 1,
                'prepared_on' => '2024-04-11',
                'approved_by' => 1,
                'approved_on' => '2024-04-12',
                'staff_id' => 1,
                'rep_id' => 3,
                'company_id' => 4,
            ],
            [
                'date' => '2024-05-20',
                'year' => '2024',
                'month' => '05',
                'credit_sales' => 900.890,
                'cheque_received' => '1200.567',
                'cheque_collection_dr' => '1300.789',
                'cheque_returned' => '400.123',
                'sales_returned' => '1000.00',
                'sales_target' => '1800',
                'special_target' => '450',
                'total_sales' => '900',
                'cash_collection' => '120000.00',
                'cheque_collection_cr' => '20000.00',
                'cheque_realized' => '18000.00',
                'customer_visited_count' => '220',
                'customer_visited_rate' => '22',
                'customer_visited' => '40',
                'product_sold_count' => '40',
                'product_sold_rate' => '8',
                'product_sold' => '40',
                'debit_balance' => '90000.00',
                'credit_balance' => '40000.00',
                'status' => 'Approved',
                'notes' => 'Fourth Sales commissions',
                'prepared_by' => 1,
                'prepared_on' => '2024-05-21',
                'approved_by' => 1,
                'approved_on' => '2024-05-22',
                'staff_id' => 1,
                'rep_id' => 4,
                'company_id' => 3,
            ],
            [
                'date' => '2024-06-15',
                'year' => '2024',
                'month' => '06',
                'credit_sales' => 1000.123,
                'cheque_received' => '1500.678',
                'cheque_collection_dr' => '1700.345',
                'cheque_returned' => '500.234',
                'sales_returned' => '1200.00',
                'sales_target' => '2000',
                'special_target' => '500',
                'total_sales' => '1000',
                'cash_collection' => '130000.00',
                'cheque_collection_cr' => '25000.00',
                'cheque_realized' => '1250.00',
                'customer_visited_count' => '220',
                'customer_visited_rate' => '22',
                'customer_visited' => '40',
                'product_sold_count' => '40',
                'product_sold_rate' => '8',
                'product_sold' => '40',
                'debit_balance' => '90000.00',
                'credit_balance' => '40000.00',
                'status' => 'Approved',
                'notes' => 'Fourth Sales commissions',
                'prepared_by' => 1,
                'prepared_on' => '2024-05-21',
                'approved_by' => 1,
                'approved_on' => '2024-05-22',
                'staff_id' => 1,
                'rep_id' => 4,
                'company_id' => 2,
            ],
            ];
            $now = \Carbon\Carbon::now();
        foreach ($salescommissions as $key => $salescommission) {
            $salescommissions[$key]['created_at'] = $now;
            $salescommissions[$key]['updated_at'] = $now;
        }

        \App\SalesCommission::insert($salescommissions);
    }
}