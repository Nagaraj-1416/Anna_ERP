<?php

use Illuminate\Database\Seeder;

class PurchasereturnsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $purchasereturns = [
            [
                'code' => 'PR001',
                'date' => '2024-02-21',
                'items' => '653.00',
                'total' => '1200.00',
                'notes' => 'PR entry 1',
                'category' => 'Production',
                'supplier_id' => 1,
                'unit_id' => 1,
                'store_id' => 1,
                'shop_id' => 1,
                'prepared_by' => 1,
                'prepared_on' => '2024-02-21',
                'is_approved' => 'Yes',
                'approved_by' => 1,
                'approved_on' => '2024-02-22',
                'company_id' => 1,
            ],
            [
                'code' => 'PR002',
                'date' => '2024-03-15',
                'items' => '800.00',
                'total' => '1500.00',
                'notes' => 'PR entry 2',
                'category' => 'Store',
                'supplier_id' => 1,
                'unit_id' => 1,
                'store_id' => 2,
                'shop_id' => 1,
                'prepared_by' => 1,
                'prepared_on' => '2024-03-15',
                'is_approved' => 'Yes',
                'approved_by' => 1,
                'approved_on' => '2024-03-16',
                'company_id' => 2,
            ],
            [
                'code' => 'PR003',
                'date' => '2024-04-10',
                'items' => '700.00',
                'total' => '1300.00',
                'notes' => 'PR entry 3',
                'category' => 'Production',
                'supplier_id' => 1,
                'unit_id' => 1,
                'store_id' => 1,
                'shop_id' => 2,
                'prepared_by' => 1,
                'prepared_on' => '2024-04-10',
                'is_approved' => 'Yes',
                'approved_by' => 1,
                'approved_on' => '2024-04-11',
                'company_id' => 3,
            ],
            [
                'code' => 'PR004',
                'date' => '2024-05-20',
                'items' => '900.00',
                'total' => '1600.00',
                'notes' => 'PR entry 4',
                'category' => 'Production',
                'supplier_id' => 1,
                'unit_id' => 1,
                'store_id' => 2,
                'shop_id' => 2,
                'prepared_by' => 1,
                'prepared_on' => '2024-05-20',
                'is_approved' => 'Yes',
                'approved_by' => 1,
                'approved_on' => '2024-05-21',
                'company_id' => 4,
            ],
            [
                'code' => 'PR005',
                'date' => '2024-06-15',
                'items' => '1000.00',
                'total' => '1800.00',
                'notes' => 'PR entry 5',
                'category' => 'Production',
                'supplier_id' => 1,
                'unit_id' => 1,
                'store_id' => 8,
                'shop_id' => 1,
                'prepared_by' => 1,
                'prepared_on' => '2024-06-15',
                'is_approved' => 'Yes',
                'approved_by' => 1,
                'approved_on' => '2024-06-16',
                'company_id' => 2,
            ],
        ];
            $now = \Carbon\Carbon::now();
        foreach ($purchasereturns as $key => $add) {
            $purchasereturns[$key]['created_at'] = $now;
            $purchasereturns[$key]['updated_at'] = $now;
        }

        \App\PurchaseReturn::insert($purchasereturns);
    }
}