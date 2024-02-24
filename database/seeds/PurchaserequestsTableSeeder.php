<?php

use Illuminate\Database\Seeder;

class PurchaserequestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $purchaserequests = [
            [
                'request_no' => 'RQ0001',
                'request_date' => \Carbon\Carbon::now(),
                'request_type' => 'Auto',
                'request_mode' => 'Internal',
                'request_for' => 'Store',
                'notes' => 'PQ Entry 1',
                'status' => 'Drafted',
                'prepared_by' => 1,
                'production_unit_id' => 1,
                'store_id' => 1,
                'shop_id' => 1,
                'supplier_id' => 1,
                'company_id' => 1,
            ],
            [
                'request_no' => 'RQ0002',
                'request_date' => \Carbon\Carbon::now(),
                'request_type' => 'Manual',
                'request_mode' => 'External',
                'request_for' => 'Shop',
                'notes' => 'PQ Entry 2',
                'status' => 'Drafted',
                'prepared_by' => 1,
                'production_unit_id' => 2,
                'store_id' => 2,
                'shop_id' => 2,
                'supplier_id' => 1,
                'company_id' => 1,
            ],
            [
                'request_no' => 'RQ0003',
                'request_date' => \Carbon\Carbon::now(),
                'request_type' => 'Auto',
                'request_mode' => 'Internal',
                'request_for' => 'Store',
                'notes' => 'PQ Entry 3',
                'status' => 'Drafted',
                'prepared_by' => 1,
                'production_unit_id' => 3,
                'store_id' => 3,
                'shop_id' => 3,
                'supplier_id' => 1,
                'company_id' => 1,
            ],
            [
                'request_no' => 'RQ0004',
                'request_date' => \Carbon\Carbon::now(),
                'request_type' => 'Manual',
                'request_mode' => 'External',
                'request_for' => 'Shop',
                'notes' => 'PQ Entry 4',
                'status' => 'Drafted',
                'prepared_by' => 1,
                'production_unit_id' => 4,
                'store_id' => 4,
                'shop_id' => 4,
                'supplier_id' => 1,
                'company_id' => 1,
            ],
            [
                'request_no' => 'RQ0005',
                'request_date' => \Carbon\Carbon::now(),
                'request_type' => 'Auto',
                'request_mode' => 'Internal',
                'request_for' => 'Store',
                'notes' => 'PQ Entry 5',
                'status' => 'Drafted',
                'prepared_by' => 1,
                'production_unit_id' => 5,
                'store_id' => 5,
                'shop_id' => 5,
                'supplier_id' => 1,
                'company_id' => 1,
            ],
            [
                'request_no' => 'RQ0006',
                'request_date' => \Carbon\Carbon::now(),
                'request_type' => 'Manual',
                'request_mode' => 'External',
                'request_for' => 'Shop',
                'notes' => 'PQ Entry 6',
                'status' => 'Drafted',
                'prepared_by' => 1,
                'production_unit_id' => 1,
                'store_id' => 1,
                'shop_id' => 1,
                'supplier_id' => 1,
                'company_id' => 1,
            ],
            [
                'request_no' => 'RQ0007',
                'request_date' => \Carbon\Carbon::now(),
                'request_type' => 'Auto',
                'request_mode' => 'Internal',
                'request_for' => 'Store',
                'notes' => 'PQ Entry 7',
                'status' => 'Drafted',
                'prepared_by' => 1,
                'production_unit_id' => 2,
                'store_id' => 2,
                'shop_id' => 2,
                'supplier_id' => 1,
                'company_id' => 1,
            ],
            [
                'request_no' => 'RQ0008',
                'request_date' => \Carbon\Carbon::now(),
                'request_type' => 'Manual',
                'request_mode' => 'External',
                'request_for' => 'Shop',
                'notes' => 'PQ Entry 8',
                'status' => 'Drafted',
                'prepared_by' => 1,
                'production_unit_id' => 3,
                'store_id' => 3,
                'shop_id' => 3,
                'supplier_id' => 1,
                'company_id' => 1,
            ],
            [
                'request_no' => 'RQ0009',
                'request_date' => \Carbon\Carbon::now(),
                'request_type' => 'Auto',
                'request_mode' => 'Internal',
                'request_for' => 'Store',
                'notes' => 'PQ Entry 9',
                'status' => 'Drafted',
                'prepared_by' => 1,
                'production_unit_id' => 4,
                'store_id' => 4,
                'shop_id' => 4,
                'supplier_id' => 1,
                'company_id' => 1,
            ],
            [
                'request_no' => 'RQ0010',
                'request_date' => \Carbon\Carbon::now(),
                'request_type' => 'Manual',
                'request_mode' => 'External',
                'request_for' => 'Shop',
                'notes' => 'PQ Entry 10',
                'status' => 'Drafted',
                'prepared_by' => 1,
                'production_unit_id' => 5,
                'store_id' => 5,
                'shop_id' => 5,
                'supplier_id' => 1,
                'company_id' => 1,
            ],
        ];
        $now = \Carbon\Carbon::now();
        foreach ($purchaserequests as $key => $add) {
            $purchaserequests[$key]['created_at'] = $now;
            $purchaserequests[$key]['updated_at'] = $now;
        }

        \App\PurchaseRequest::insert($purchaserequests);
    }
}