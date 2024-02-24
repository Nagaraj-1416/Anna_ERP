<?php

use Illuminate\Database\Seeder;

class AccountGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = [
            [
                'name' => 'Current Assets',
                'description' => 'Current assets account categories',
                'parent_id' => null,
                'category_id' => '1'
            ],
            [
                'name' => 'Account Receivable',
                'description' => 'Accounts receivable represents the money owed to a business by customers who have purchased goods or services on credit.',
                'parent_id' => 1,
                'category_id' => '1'
            ],
            [
                'name' => 'Customers',
                'description' => 'Customers refer to individuals or businesses that purchase goods or services from another party.',
                'parent_id' => 2,
                'category_id' => '1'
            ],
            [
                'name' => 'Reps',
                'description' => 'Reps, short for representatives, are individuals or entities responsible for selling products or services on behalf of a company.',
                'parent_id' => 3,
                'category_id' => '1'
            ],
            [
                'name' => 'Routes',
                'description' => 'Routes denote predefined paths or itineraries followed by delivery or sales personnel to reach customers or destinations.',
                'parent_id' => 4,
                'category_id' => '1'
            ],
            [
                'name' => 'Locations',
                'description' => 'Locations represent specific places or areas where business activities or transactions occur.',
                'parent_id' => 5,
                'category_id' => '1'
            ],
            [
                'name' => 'Current Liability',
                'description' => 'Current liabilities account categories',
                'parent_id' => null,
                'category_id' => '2'
            ],
            [
                'name' => 'Account Payable',
                'description' => 'Accounts payable refers to the money owed by a company to its suppliers or vendors for goods or services received on credit.',
                'parent_id' => 7,
                'category_id' => '2'
            ],
            [
                'name' => 'Suppliers',
                'description' => 'Suppliers are individuals or companies that provide goods or services to another entity under agreed-upon terms and conditions.',
                'parent_id' => 8,
                'category_id' => '2'
            ],
            [
                'name' => 'Inventory',
                'description' => 'Inventory represents the goods or materials held by a business for the purpose of resale or use in its operations.',
                'parent_id' => 1,
                'category_id' => '1'
            ]
        ];
        $now = \Carbon\Carbon::now();
        foreach ($groups as $key => $group) {
            $groups[$key]['created_at'] = $now;
            $groups[$key]['updated_at'] = $now;
        }
        \App\AccountGroup::insert($groups);
    }
}