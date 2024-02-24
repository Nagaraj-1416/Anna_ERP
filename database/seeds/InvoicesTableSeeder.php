<?php

use Illuminate\Database\Seeder;

class InvoicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $invoices = [
            [
                'invoice_no' => 'IN0001',
                'ref' => 'REF123',
                'invoice_date' => '2024-02-01',
                'due_date' => '2024-02-15',
                'invoice_type' => 'Invoice',
                'amount' => 150.00,
                'prepared_by' => 1,
                'approval_status' => 'Approved',
                'approved_by' => 2,
                'status' => 'Draft',
                'notes' => 'Payment for services rendered',
                'sales_order_id' => 1,
                'customer_id' => 1,
                'business_type_id' => 1,
                'company_id' => 1,
                'sales_location_id' => 1,
                'uuid' => 'uuid123456',
                'daily_sale_id' => 1,
            ],
            [
                'invoice_no' => 'IN0002',
                'ref' => 'REF456',
                'invoice_date' => '2024-02-02',
                'due_date' => '2024-02-16',
                'invoice_type' => 'Invoice',
                'amount' => 200.00,
                'prepared_by' => 2,
                'approval_status' => 'Pending',
                'approved_by' => 1,
                'status' => 'Draft',
                'notes' => 'Payment for goods purchased',
                'sales_order_id' => 2,
                'customer_id' => 2,
                'business_type_id' => 2,
                'company_id' => 2,
                'sales_location_id' => 2,
                'uuid' => 'uuid234567',
                'daily_sale_id' => 2,
            ],
            [
                'invoice_no' => 'IN0003',
                'ref' => 'REF789',
                'invoice_date' => '2024-02-03',
                'due_date' => '2024-02-17',
                'invoice_type' => 'Invoice',
                'amount' => 300.00,
                'prepared_by' => 3,
                'approval_status' => 'Approved',
                'approved_by' => 3,
                'status' => 'Overdue',
                'notes' => 'Payment for services rendered',
                'sales_order_id' => 3,
                'customer_id' => 3,
                'business_type_id' => 3,
                'company_id' => 3,
                'sales_location_id' => 3,
                'uuid' => 'uuid345678',
                'daily_sale_id' => 3,
            ],
            [
                'invoice_no' => 'IN0004',
                'ref' => 'REF101',
                'invoice_date' => '2024-02-04',
                'due_date' => '2024-02-18',
                'invoice_type' => 'Invoice',
                'amount' => 250.00,
                'prepared_by' => 4,
                'approval_status' => 'Pending',
                'approved_by' => 1,
                'status' => 'Draft',
                'notes' => 'Payment for goods purchased',
                'sales_order_id' => 3,
                'customer_id' => 2,
                'business_type_id' => 2,
                'company_id' => 4,
                'sales_location_id' => 4,
                'uuid' => 'uuid456789',
                'daily_sale_id' => 4,
            ],
            [
                'invoice_no' => 'IN0005',
                'ref' => 'REF202',
                'invoice_date' => '2024-02-05',
                'due_date' => '2024-02-19',
                'invoice_type' => 'Invoice',
                'amount' => 180.00,
                'prepared_by' => 5,
                'approval_status' => 'Approved',
                'approved_by' => 5,
                'status' => 'Draft',
                'notes' => 'Payment for services rendered',
                'sales_order_id' => 3,
                'customer_id' => 3,
                'business_type_id' => 3,
                'company_id' => 2,
                'sales_location_id' => 5,
                'uuid' => 'uuid567890',
                'daily_sale_id' => 5,
            ],
           
        ];

        $now = \Carbon\Carbon::now();
        foreach ($invoices as $key => $invoice) {
            $invoices[$key]['created_at'] = $now;
            $invoices[$key]['updated_at'] = $now;
        }

        \App\Invoice::insert($invoices);
    }
}
