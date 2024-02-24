<?php

use Illuminate\Database\Seeder;

class TxTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            [
                'code' => 'ATT000001',
                'name' => 'Expense',
                'short_name' => 'Expense',
                'mode' => 'MoneyOut',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000002',
                'name' => 'Supplier Advance',
                'short_name' => 'Supplier Advance',
                'mode' => 'MoneyOut',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000003',
                'name' => 'Supplier Payment',
                'short_name' => 'Supplier Payment',
                'mode' => 'MoneyOut',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000004',
                'name' => 'Customer Payment Cancel',
                'short_name' => 'Customer Payment Cancel',
                'mode' => 'MoneyOut',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000005',
                'name' => 'Customer Payment Refund',
                'short_name' => 'Customer Payment Refund',
                'mode' => 'MoneyOut',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000006',
                'name' => 'Transfer to Another Account',
                'short_name' => 'Transfer to Another Account',
                'mode' => 'MoneyOut',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000007',
                'name' => 'Sales Return',
                'short_name' => 'Sales Return',
                'mode' => 'MoneyOut',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000008',
                'name' => 'Card Payment',
                'short_name' => 'Card Payment',
                'mode' => 'MoneyOut',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000009',
                'name' => 'Owner Drawings',
                'short_name' => 'Owner Drawings',
                'mode' => 'MoneyOut',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000010',
                'name' => 'Supplier Credit',
                'short_name' => 'Supplier Credit',
                'mode' => 'MoneyOut',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000011',
                'name' => 'Customer Credit Refund',
                'short_name' => 'Customer Credit Refund',
                'mode' => 'MoneyOut',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000012',
                'name' => 'Customer Credit Apply to Invoice',
                'short_name' => 'Customer Credit Apply to Invoice',
                'mode' => 'MoneyOut',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000013',
                'name' => 'Employee Reimbursement',
                'short_name' => 'Employee Reimbursement',
                'mode' => 'MoneyOut',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000014',
                'name' => 'Bill',
                'short_name' => 'Bill',
                'mode' => 'MoneyOut',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000015',
                'name' => 'Invoice Cancel',
                'short_name' => 'Invoice Cancel',
                'mode' => 'MoneyOut',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000016',
                'name' => 'Invoice Refund',
                'short_name' => 'Invoice Refund',
                'mode' => 'MoneyOut',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000017',
                'name' => 'Manual Cheque Issued',
                'short_name' => 'Manual Cheque Issued',
                'mode' => 'MoneyOut',
                'is_default' => 'Yes'
            ],

            /** MoneyIn */

            [
                'code' => 'ATT000018',
                'name' => 'Customer Advance',
                'short_name' => 'Customer Advance',
                'mode' => 'MoneyIn',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000019',
                'name' => 'Customer Payment',
                'short_name' => 'Customer Payment',
                'mode' => 'MoneyIn',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000020',
                'name' => 'Supplier Payment Cancel',
                'short_name' => 'Supplier Payment Cancel',
                'mode' => 'MoneyIn',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000021',
                'name' => 'Supplier Payment Refund',
                'short_name' => 'Supplier Payment Refund',
                'mode' => 'MoneyIn',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000022',
                'name' => 'Transfer from Another Account',
                'short_name' => 'Transfer from Another Account',
                'mode' => 'MoneyIn',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000023',
                'name' => 'Purchase Return',
                'short_name' => 'Purchase Return',
                'mode' => 'MoneyIn',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000024',
                'name' => 'Customer Credit',
                'short_name' => 'Customer Credit',
                'mode' => 'MoneyIn',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000025',
                'name' => 'Supplier Credit Refund',
                'short_name' => 'Supplier Credit Refund',
                'mode' => 'MoneyIn',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000026',
                'name' => 'Supplier Credit Apply to Bill',
                'short_name' => 'Supplier Credit Apply to Bill',
                'mode' => 'MoneyIn',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000027',
                'name' => 'Invoice',
                'short_name' => 'Invoice',
                'mode' => 'MoneyIn',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000028',
                'name' => 'Bill Cancel',
                'short_name' => 'Bill Cancel',
                'mode' => 'MoneyIn',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000029',
                'name' => 'Bill Refund',
                'short_name' => 'Bill Refund',
                'mode' => 'MoneyIn',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000030',
                'name' => 'Establish Petty Cash Fund',
                'short_name' => 'Establish Petty Cash Fund',
                'mode' => 'MoneyIn',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000031',
                'name' => 'Reimbursement Fund',
                'short_name' => 'Reimbursement Fund',
                'mode' => 'MoneyIn',
                'is_default' => 'Yes'
            ],
            [
                'code' => 'ATT000032',
                'name' => 'Manual Income',
                'short_name' => 'Manual Income',
                'mode' => 'MoneyIn',
                'is_default' => 'Yes'
            ]
        ];
        $now = \Carbon\Carbon::now();
        foreach ($types as $key => $rate) {
            $types[$key]['created_at'] = $now;
            $types[$key]['updated_at'] = $now;
        }
        \App\TransactionType::insert($types);
    }
}
