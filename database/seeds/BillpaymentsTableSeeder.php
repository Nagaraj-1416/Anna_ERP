<?php

use Illuminate\Database\Seeder;

class BillpaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bills_payments = [
            // Array 1 (Original data)
            [
                'payment' => 45.35,
                'payment_date' => \Carbon\Carbon::now(),
                'payment_type' => 'Advanced',
                'payment_mode' => 'Cash',
                'cheque_type' => 'Own',
                'cheque_no' => '2353443',
                'cheque_date' => \Carbon\Carbon::now(),
                'account_no' => '432343454564',
                'deposited_date' => \Carbon\Carbon::now(),
                'card_holder_name' => 'JohnThomas',
                'card_no' => 1342543,
                'expiry_date' => \Carbon\Carbon::now(),
                'bank_id' => 1,
                'status' => 'Paid',
                'notes' => 'advance payments for JohnThomas',
                'prepared_by' => 1,
                'paid_through' => 1,
                'payment_from' => 'Direct',
                'credit_id' => 2,
                'bill_id' => 4,
                'purchase_order_id' => 1,
                'supplier_id' => 1,
                'business_type_id' => 1,
                'company_id' => 1,
            ],
            // Array 2 (Original data)
            [
                'payment' => 75.20,
                'payment_date' => \Carbon\Carbon::now()->subDays(3),
                'payment_type' => 'Partial Payment',
                'payment_mode' => 'Direct Deposit',
                'cheque_type' => 'Third Party',
                'cheque_no' => '8765432',
                'cheque_date' => \Carbon\Carbon::now()->subDays(3),
                'account_no' => '1234567890',
                'deposited_date' => \Carbon\Carbon::now()->subDays(2),
                'card_holder_name' => 'Jane Doe',
                'card_no' => 987654321,
                'expiry_date' => \Carbon\Carbon::now()->addMonths(6),
                'bank_id' => 2,
                'status' => 'Refunded',
                'notes' => 'regular payment for Jane Doe',
                'prepared_by' => 1,
                'paid_through' => 1,
                'payment_from' => 'Credit',
                'credit_id' => 1,
                'bill_id' => 5,
                'purchase_order_id' => 2,
                'supplier_id' => 1,
                'business_type_id' => 2,
                'company_id' => 2,
            ],
            // Array 3 (Original data)
            [
                'payment' => 120.00,
                'payment_date' => \Carbon\Carbon::now()->subDays(5),
                'payment_type' => 'Advanced',
                'payment_mode' => 'Credit Card',
                'cheque_type' => 'Third Party',
                'cheque_no' => '543210',
                'cheque_date' => \Carbon\Carbon::now()->subDays(5),
                'account_no' => '9988776655',
                'deposited_date' => \Carbon\Carbon::now()->subDays(4),
                'card_holder_name' => 'Alice Smith',
                'card_no' => 123456789,
                'expiry_date' => \Carbon\Carbon::now()->addMonths(8),
                'bank_id' => 3,
                'status' => 'Paid',
                'notes' => 'advance payments for Alice Smith',
                'prepared_by' => 1,
                'paid_through' => 1,
                'payment_from' => 'Direct',
                'credit_id' => 2,
                'bill_id' => 4,
                'purchase_order_id' => 3,
                'supplier_id' => 1,
                'business_type_id' => 3,
                'company_id' => 2,
            ],
            // Array 4
            [
                'payment' => 99.99,
                'payment_date' => \Carbon\Carbon::now()->subDays(10),
                'payment_type' => 'Final Payment',
                'payment_mode' => 'Cheque',
                'cheque_type' => 'Own',
                'cheque_no' => '777777',
                'cheque_date' => \Carbon\Carbon::now()->subDays(10),
                'account_no' => '111122223333',
                'deposited_date' => \Carbon\Carbon::now()->subDays(9),
                'card_holder_name' => 'Michael Johnson',
                'card_no' => 5555666677778888,
                'expiry_date' => \Carbon\Carbon::now()->addMonths(3),
                'bank_id' => 4,
                'status' => 'Canceled',
                'notes' => 'regular payment for Michael Johnson',
                'prepared_by' => 1,
                'paid_through' => 1,
                'payment_from' => 'Direct',
                'credit_id' => 1,
                'bill_id' => 4,
                'purchase_order_id' => 4,
                'supplier_id' => 1,
                'business_type_id' => 2,
                'company_id' => 4,
            ],
          
            [
                'payment' => 55.55,
                'payment_date' => \Carbon\Carbon::now()->subDays(7),
                'payment_type' => 'Advanced',
                'payment_mode' => 'Cash',
                'cheque_type' => 'Own',
                'cheque_no' => '987654',
                'cheque_date' => \Carbon\Carbon::now()->subDays(7),
                'account_no' => '9876543210',
                'deposited_date' => \Carbon\Carbon::now()->subDays(6),
                'card_holder_name' => 'Emily White',
                'card_no' => 9999888877776666,
                'expiry_date' => \Carbon\Carbon::now()->addMonths(5),
                'bank_id' => 5,
                'status' => 'Paid',
                'notes' => 'advance payment for Emily White',
                'prepared_by' => 1,
                'paid_through' => 1,
                'payment_from' => 'Credit',
                'credit_id' => 1,
                'bill_id' => 4,
                'purchase_order_id' => 5,
                'supplier_id' => 1,
                'business_type_id' => 3,
                'company_id' => 2,
            ],
        ];
        $now = \Carbon\Carbon::now();
        foreach ($bills_payments as $key => $add) {
            $bills_payments[$key]['created_at'] = $now;
            $bills_payments[$key]['updated_at'] = $now;
        }

        \App\BillPayment::insert($bills_payments);
    }
}