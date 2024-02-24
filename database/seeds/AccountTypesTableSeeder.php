<?php

use Illuminate\Database\Seeder;

class AccountTypesTableSeeder extends Seeder
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
                'code' => 'AT0000001',
                'name' => 'Cash',
                'short_name' => 'Cash',
                'notes' => 'Cash Accounts.',
                'is_default' => 'Yes',
                'is_active' => 'Yes',
                'account_category_id' => '1',
            ],
            [
                'code' => 'AT0000002',
                'name' => 'Bank',
                'short_name' => 'Bank',
                'notes' => 'Bank Accounts.',
                'is_default' => 'Yes',
                'is_active' => 'Yes',
                'account_category_id' => '1',
            ],
            [
                'code' => 'AT0000003',
                'name' => 'Current Asset',
                'short_name' => 'Current Asset',
                'notes' => 'Cash and other assets that are expected to be converted to cash within a year.',
                'is_default' => 'Yes',
                'is_active' => 'Yes',
                'account_category_id' => '1',
            ],
            [
                'code' => 'AT0000004',
                'name' => 'Fixed Asset',
                'short_name' => 'Fixed Asset',
                'notes' => 'Fixed asset is a long-term tangible piece of property that a firm owns and uses in its operations to generate income.',
                'is_default' => 'Yes',
                'is_active' => 'Yes',
                'account_category_id' => '1',
            ],
            [
                'code' => 'AT0000005',
                'name' => 'Inventory',
                'short_name' => 'Inventory',
                'notes' => 'Inventory accounting is the body of accounting that deals with valuing and accounting for changes in inventoried assets.',
                'is_default' => 'Yes',
                'is_active' => 'Yes',
                'account_category_id' => '1',
            ],
            [
                'code' => 'AT0000006',
                'name' => 'Non-Current Asset',
                'short_name' => 'Non-Current Asset',
                'notes' => 'A non-current asset is an asset that is not likely to turn to unrestricted cash within one year of the balance sheet date.',
                'is_default' => 'Yes',
                'is_active' => 'Yes',
                'account_category_id' => '1',
            ],
            [
                'code' => 'AT0000007',
                'name' => 'Prepayment',
                'short_name' => 'Prepayment',
                'notes' => 'A prepayment is recorded as a debit to the prepaid expenses account and a credit to the cash account. ',
                'is_default' => 'Yes',
                'is_active' => 'Yes',
                'account_category_id' => '1',
            ],
            [
                'code' => 'AT0000008',
                'name' => 'Current Liability',
                'short_name' => 'Current Liability',
                'notes' => 'Current liabilities are a company\'s debts or obligations that are due within one year or within a normal operating cycle.',
                'is_default' => 'Yes',
                'is_active' => 'Yes',
                'account_category_id' => '2',
            ],
            [
                'code' => 'AT0000009',
                'name' => 'Liability',
                'short_name' => 'Liability',
                'notes' => 'Liabilities are obligations of the company; they are amounts owed to creditors for a past transaction and they usually have the word "payable" in their account title.',
                'is_default' => 'Yes',
                'is_active' => 'Yes',
                'account_category_id' => '2',
            ],
            [
                'code' => 'AT0000010',
                'name' => 'Non-current Liability',
                'short_name' => 'Non-current Liability',
                'notes' => 'Non-current liabilities are long-term liabilities, which are financial obligations of a company that will come due in a year or longer.',
                'is_default' => 'Yes',
                'is_active' => 'Yes',
                'account_category_id' => '2',
            ],
            [
                'code' => 'AT0000011',
                'name' => 'Income',
                'short_name' => 'Income',
                'notes' => 'A financial statement of a business showing the details of revenues, costs, expenses, losses, and profits for a given period.',
                'is_default' => 'Yes',
                'is_active' => 'Yes',
                'account_category_id' => '3',
            ],
            [
                'code' => 'AT0000012',
                'name' => 'Other Income',
                'short_name' => 'OI',
                'notes' => 'Other income is income that does not come from a company\'s main business, such as interest.',
                'is_default' => 'Yes',
                'is_active' => 'Yes',
                'account_category_id' => '3',
            ],
            [
                'code' => 'AT0000013',
                'name' => 'Sales',
                'short_name' => 'Sales',
                'notes' => 'In accounting, sales refers to the revenues earned when a company sells its goods, products, merchandise, etc.',
                'is_default' => 'Yes',
                'is_active' => 'Yes',
                'account_category_id' => '3',
            ],
            [
                'code' => 'AT0000014',
                'name' => 'Depreciation',
                'short_name' => 'Depreciation',
                'notes' => 'The depreciation account is an asset account with a credit balance; this means that it appears on the balance sheet as a reduction from the gross amount of fixed assets reported.',
                'is_default' => 'Yes',
                'is_active' => 'Yes',
                'account_category_id' => '4',
            ],
            [
                'code' => 'AT0000015',
                'name' => 'Direct Costs',
                'short_name' => 'Direct Costs',
                'notes' => 'A direct cost is a price that can be completely attributed to the production of specific goods or services.',
                'is_default' => 'Yes',
                'is_active' => 'Yes',
                'account_category_id' => '4',
            ],
            [
                'code' => 'AT0000016',
                'name' => 'Expense',
                'short_name' => 'Expense',
                'notes' => 'An expense account is the right to reimbursement of money spent by employees for work-related purposes.',
                'is_default' => 'Yes',
                'is_active' => 'Yes',
                'account_category_id' => '4',
            ],
            [
                'code' => 'AT0000017',
                'name' => 'Overhead',
                'short_name' => 'Overhead',
                'notes' => 'This account which records labor and non-labor expenses that cannot be directly associated with a specific cost area, job, or task.',
                'is_default' => 'Yes',
                'is_active' => 'Yes',
                'account_category_id' => '4',
            ],
            [
                'code' => 'AT0000018',
                'name' => 'Equity',
                'short_name' => 'Equity',
                'notes' => 'Equity is defined as the owner\'s interest in the company assets.',
                'is_default' => 'Yes',
                'is_active' => 'Yes',
                'account_category_id' => '5',
            ]
        ];
        $now = \Carbon\Carbon::now();
        foreach ($types as $key => $type) {
            $types[$key]['created_at'] = $now;
            $types[$key]['updated_at'] = $now;
        }
        \App\AccountType::insert($types);
    }
}
