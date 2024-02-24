<?php

use Illuminate\Database\Seeder;

class ApirequestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $apirequests=[
            [
                'user_id' => 1,
                'url' =>'/setting/department/data',
                'method' => 'post',
                'data' => 'departments data',
            ],
            [
                'user_id' => 1,
                'url' =>'/transaction',
                'method' => 'get',
                'data' => 'get transaction data',
            ],
            [
                'user_id' => 1,
                'url' =>'/transaction/6',
                'method' => 'patch',
                'data' => '{
                    "_token": "YPefAlnhzadOVVLO2gKHf6VzRZymgwwVoCu8hlgb",
                    "type": "Deposit",
                    "company_id": 1,
                    "date": "2024-02-15",
                    "tx_type_id": 3,
                    "account_id": [1,2],
                    "debit": ["2000","0"],
                    "credit": ["2000","0"],
                    "customer_id": 1,
                    "supplier_id": 1,
                    "prepared_by": 1,
                    "manual_narration": "money given",
                    "notes": "xcvxcv"
                }',
            ],
            [
                'user_id' => 1,
                'url' =>'/transaction/6/edit',
                'method' => 'get',
                'data' => 'get transaction data',
            ],
            [
                'user_id' => 1,
                'url' =>'/transaction/8',
                'method' => 'get',
                'data' => 'get transaction data',
            ],
            [
                'user_id' => 1,
                'url' =>'/transaction/8/export',
                'method' => 'get',
                'data' => 'get export transaction data',
            ],
            [
                'user_id' => 1,
                'url' =>'/transaction/8/print',
                'method' => 'get',
                'data' => '',
            ],
            ];
            $now = \Carbon\Carbon::now();
            foreach ($apirequests as $key => $apirequest) {
                $apirequests[$key]['created_at'] = $now;
                $apirequests[$key]['updated_at'] = $now;
            }
            \App\ApiRequest::insert($apirequests);    
    }
}