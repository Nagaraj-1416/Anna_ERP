<?php

use Illuminate\Database\Seeder;

class OpeningbalancereferencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $openingbalancereferences = [
            [
                'date' => '2024-02-22',
                'amount' => '666.456',
                'reference_type' => 'Account',
                'account_id' => 1,
                'reference_no' => 'OP0001',
                'customer_id' => 1,
                'invoice_no' => 'INV0001',
                'invoice_date' => '2024-02-22',
                'invoice_amount' => '25000.00',
                'invoice_due' => null,
                'invoice_due_age' => null,
                'supplier_id' => 1,
                'bill_no' => 'B0001',
                'bill_date' => '2024-02-22',
                'bill_amount' => '23000.00',
                'bill_due' => null,
                'bill_due_age' => null,
                'updated_by' => 1,
                'order_id' => 1
            ],
            
            [
                'date' => '2024-02-23',
                'amount' => '500.00',
                'reference_type' => 'Customer',
                'account_id' => 2,
                'reference_no' => 'OP0002',
                'customer_id' => 1,
                'invoice_no' => 'INV0002',
                'invoice_date' => '2024-02-23',
                'invoice_amount' => '15000.00',
                'invoice_due' => '2024-03-15',
                'invoice_due_age' => '23',
                'supplier_id' => null,
                'bill_no' => null,
                'bill_date' => null,
                'bill_amount' => null,
                'bill_due' => null,
                'bill_due_age' => null,
                'updated_by' => 1,
                'order_id' => 2
            ],
        
            [
                'date' => '2024-02-24',
                'amount' => '800.00',
                'reference_type' => 'Supplier',
                'account_id' => 3,
                'reference_no' => 'OP0003',
                'customer_id' => 1,
                'invoice_no' => 'INV0003',
                'invoice_date' => '2024-02-24',
                'invoice_amount' => '13000.00',
                'invoice_due' => null,
                'invoice_due_age' => null,
                'supplier_id' => 3,
                'bill_no' => 'B0003',
                'bill_date' => '2024-02-24',
                'bill_amount' => '18000.00',
                'bill_due' => '2024-03-10',
                'bill_due_age' => '14',
                'updated_by' => 1,
                'order_id' => 3
            ],

                [
                    'date' => '2024-02-25',
                    'amount' => '1200.00',
                    'reference_type' => 'Account',
                    'account_id' => 4,
                    'reference_no' => 'OP0004',
                    'customer_id' => 1,
                    'invoice_no' => 'INV0004',
                    'invoice_date' => '2024-02-25',
                    'invoice_amount' => '18000.00',
                    'invoice_due' => '2024-03-05',
                    'invoice_due_age' => '7',
                    'supplier_id' => 1,
                    'bill_no' => 'B0004',
                    'bill_date' => '2024-02-25',
                    'bill_amount' => '18000.00',
                    'bill_due' => null,
                    'bill_due_age' => null,
                    'updated_by' => 1,
                    'order_id' => 4
                ],
                [
                    'date' => '2024-02-26',
                    'amount' => '900.00',
                    'reference_type' => 'Customer',
                    'account_id' => 5,
                    'reference_no' => 'OP0005',
                    'customer_id' => 1,
                    'invoice_no' => 'INV0005',
                    'invoice_date' => '2024-02-26',
                    'invoice_amount' => '22000.00',
                    'invoice_due' => '2024-03-08',
                    'invoice_due_age' => '10',
                    'supplier_id' => 1,
                    'bill_no' => 'B0005',
                    'bill_date' => '2024-02-26',
                    'bill_amount' => '6500.00',
                    'bill_due' => null,
                    'bill_due_age' => null,
                    'updated_by' => 1,
                    'order_id' => 5
                ],
                [
                    'date' => '2024-02-27',
                    'amount' => '1500.00',
                    'reference_type' => 'Supplier',
                    'account_id' => 6,
                    'reference_no' => 'OP0006',
                    'customer_id' => 1,
                    'invoice_no' => 'INV006',
                    'invoice_date' => '2024-02-22',
                    'invoice_amount' => '2000',
                    'invoice_due' => null,
                    'invoice_due_age' => null,
                    'supplier_id' => 2,
                    'bill_no' => 'B0004',
                    'bill_date' => '2024-02-27',
                    'bill_amount' => '25000.00',
                    'bill_due' => '2024-03-12',
                    'bill_due_age' => '15',
                    'updated_by' => 1,
                    'order_id' => 2
                ],
                [
                    'date' => '2024-02-28',
                    'amount' => '1800.00',
                    'reference_type' => 'Account',
                    'account_id' => 7,
                    'reference_no' => 'OP0007',
                    'customer_id' => 1,
                    'invoice_no' => 'INV0007',
                    'invoice_date' => '2024-02-28',
                    'invoice_amount' => '20000.00',
                    'invoice_due' => '2024-03-15',
                    'invoice_due_age' => '15',
                    'supplier_id' => 1,
                    'bill_no' => 'B0007',
                    'bill_date' => '2024-02-28',
                    'bill_amount' => '1250.00',
                    'bill_due' => null,
                    'bill_due_age' => null,
                    'updated_by' => 1,
                    'order_id' => 2
                ],
                [
                    'date' => '2024-03-01',
                    'amount' => '950.00',
                    'reference_type' => 'Customer',
                    'account_id' => 8,
                    'reference_no' => 'OP0008',
                    'customer_id' => 1,
                    'invoice_no' => 'INV0006',
                    'invoice_date' => '2024-03-01',
                    'invoice_amount' => '28000.00',
                    'invoice_due' => '2024-03-20',
                    'invoice_due_age' => '19',
                    'supplier_id' => 1,
                    'bill_no' => 'B0008',
                    'bill_date' => '2024-02-23',
                    'bill_amount' => '12000.00',
                    'bill_due' => null,
                    'bill_due_age' => null,
                    'updated_by' => 1,
                    'order_id' => 2
                ],
                [
                    'date' => '2024-03-02',
                    'amount' => '2000.00',
                    'reference_type' => 'Supplier',
                    'account_id' => 9,
                    'reference_no' => 'OP0009',
                    'customer_id' => 1,
                    'invoice_no' => 'INV0009',
                    'invoice_date' => '2024-02-22',
                    'invoice_amount' => '5600.00',
                    'invoice_due' => null,
                    'invoice_due_age' => null,
                    'supplier_id' => 1,
                    'bill_no' => 'B0005',
                    'bill_date' => '2024-03-02',
                    'bill_amount' => '30000.00',
                    'bill_due' => '2024-03-18',
                    'bill_due_age' => '16',
                    'updated_by' => 1,
                    'order_id' => 2
                ],
                [
                    'date' => '2024-03-03',
                    'amount' => '1250.00',
                    'reference_type' => 'Account',
                    'account_id' => 10,
                    'reference_no' => 'OP0010',
                    'customer_id' => 1,
                    'invoice_no' => 'INV0007',
                    'invoice_date' => '2024-03-03',
                    'invoice_amount' => '22000.00',
                    'invoice_due' => '2024-03-25',
                    'invoice_due_age' => '22',
                    'supplier_id' => 1,
                    'bill_no' => 'B0010',
                    'bill_date' => '2024-02-11',
                    'bill_amount' => '1000.00',
                    'bill_due' => null,
                    'bill_due_age' => null,
                    'updated_by' => 1,
                    'order_id' => 1
                ],
            
        ];
            $now = \Carbon\Carbon::now();
        foreach ($openingbalancereferences as $key => $openingbalancereference) {
            $openingbalancereferences[$key]['created_at'] = $now;
            $openingbalancereferences[$key]['updated_at'] = $now;
        }
        \App\OpeningBalanceReference::insert($openingbalancereferences);
    }
}