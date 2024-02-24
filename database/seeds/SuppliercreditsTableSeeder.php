<?php

use Illuminate\Database\Seeder;

class SuppliercreditsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supplier_credits = [
            [
                'code' => 'SC0001',
                'date' => '2024-02-01',
                'amount' => '150.00',
                'notes' => 'Payment for supplies',
                'status' => 'Open',
                'prepared_by' => 1,
                'supplier_id' => 1,
                'business_type_id' => 1,
                'company_id' => 1,
                'bill_id' => 1,
            ],
            [
                'code' => 'SC0002',
                'date' => '2024-02-02',
                'amount' => '200.00',
                'notes' => 'Payment for services',
                'status' => 'Canceled',
                'prepared_by' => 2,
                'supplier_id' => 2,
                'business_type_id' => 2,
                'company_id' => 2,
                'bill_id' => 2,
            ],
            [
                'code' => 'SC0003',
                'date' => '2024-02-03',
                'amount' => '300.00',
                'notes' => 'Payment for equipment',
                'status' => 'Open',
                'prepared_by' => 3,
                'supplier_id' => 3,
                'business_type_id' => 3,
                'company_id' => 3,
                'bill_id' => 3,
            ],
            [
                'code' => 'SC0004',
                'date' => '2024-02-04',
                'amount' => '250.00',
                'notes' => 'Payment for repairs',
                'status' => 'Closed',
                'prepared_by' => 4,
                'supplier_id' => 3,
                'business_type_id' => 2,
                'company_id' => 4,
                'bill_id' => 4,
            ],
            [
                'code' => 'SC0005',
                'date' => '2024-02-05',
                'amount' => '180.00',
                'notes' => 'Payment for maintenance',
                'status' => 'Open',
                'prepared_by' => 5,
                'supplier_id' => 2,
                'business_type_id' => 3,
                'company_id' => 4,
                'bill_id' => 2,
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($supplier_credits as $key => $add) {
            $supplier_credits[$key]['created_at'] = $now;
            $supplier_credits[$key]['updated_at'] = $now;
        }

        \App\SupplierCredit::insert($supplier_credits);
    }
}
