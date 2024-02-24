<?php

use Illuminate\Database\Seeder;

class CustomercreditsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer_credits = [
            [
                'code' => 'CC0001',
                'date' => '2024-02-01',
                'amount' => '455.5',
                'notes' => 'Payment received for services',
                'status' => 'Open',
                'prepared_by' => 1,
                'customer_id' => 1,
                'business_type_id' => 1,
                'company_id' => 1,
                'invoice_id' => 1,
            ],
            [
                'code' => 'CC0002',
                'date' => '2024-02-02',
                'amount' => '300.0',
                'notes' => 'Payment received for goods',
                'status' => 'Closed',
                'prepared_by' => 2,
                'customer_id' => 2,
                'business_type_id' => 2,
                'company_id' => 2,
                'invoice_id' => 2,
            ],
            [
                'code' => 'CC0003',
                'date' => '2024-02-03',
                'amount' => '600.75',
                'notes' => 'Payment received for services rendered',
                'status' => 'Open',
                'prepared_by' => 3,
                'customer_id' => 3,
                'business_type_id' => 3,
                'company_id' => 3,
                'invoice_id' => 3,
            ],
            [
                'code' => 'CC0004',
                'date' => '2024-02-04',
                'amount' => '250.25',
                'notes' => 'Payment received for goods purchased',
                'status' => 'Open',
                'prepared_by' => 4,
                'customer_id' => 2,
                'business_type_id' => 3,
                'company_id' => 2,
                'invoice_id' => 4,
            ],
            [
                'code' => 'CC0005',
                'date' => '2024-02-05',
                'amount' => '180.00',
                'notes' => 'Payment received for services',
                'status' => 'Canceled',
                'prepared_by' => 5,
                'customer_id' => 3,
                'business_type_id' => 3,
                'company_id' => 3,
                'invoice_id' => 5,
            ],
           
        ];

        $now = \Carbon\Carbon::now();
        foreach ($customer_credits as $key => $customer_credit) {
            $customer_credits[$key]['created_at'] = $now;
            $customer_credits[$key]['updated_at'] = $now;
        }

        \App\CustomerCredit::insert($customer_credits);
    }
}
