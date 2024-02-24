<?php

use Illuminate\Database\Seeder;

class PuchaseOrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $purchaseOrders = [
            [
                'po_no' => 'INV-001',
                'order_date' => '2024-02-01',
                'delivery_date' => '2024-02-10',
                'po_type' => 'Auto',
                'po_mode' => 'Internal',
                'po_for' => 'PUnit',
                'notes' => 'Urgent delivery required',
                'status' => 'Drafted',
                'grn_created' => 'Yes',
                'grn_received' => 'Yes',
                'prepared_by' => 1,
                'production_unit_id' => 1,
                'store_id' => 1,
                'shop_id' => 1,
                'supplier_id' => 1,
                'company_id' => 1,
            ],
            [
                'po_no' => 'INV-002',
                'order_date' => '2024-02-02',
                'delivery_date' => '2024-02-11',
                'po_type' => 'Manual',
                'po_mode' => 'External',
                'po_for' => 'Store',
                'notes' => 'Special instructions: Fragile items',
                'status' => 'Delivered',
                'grn_created' => 'No',
                'grn_received' => 'No',
                'prepared_by' => 1,
                'production_unit_id' => 2,
                'store_id' => 2,
                'shop_id' => 2,
                'supplier_id' => 2,
                'company_id' => 2,
            ],
            [
                'po_no' => 'INV-003',
                'order_date' => '2024-02-03',
                'delivery_date' => '2024-02-12',
                'po_type' => 'Auto',
                'po_mode' => 'Internal',
                'po_for' => 'Shop',
                'notes' => '',
                'status' => 'Pending',
                'grn_created' => 'Yes',
                'grn_received' => 'Yes',
                'prepared_by' => 1,
                'production_unit_id' => 3,
                'store_id' => 3,
                'shop_id' => 3,
                'supplier_id' => 3,
                'company_id' => 3,
            ],
            [
                'po_no' => 'INV-004',
                'order_date' => '2024-02-04',
                'delivery_date' => '2024-02-13',
                'po_type' => 'Manual',
                'po_mode' => 'External',
                'po_for' => 'PUnit',
                'notes' => 'Gift wrapping required',
                'status' => 'Sent',
                'grn_created' => 'Yes',
                'grn_received' => 'No',
                'prepared_by' => 1,
                'production_unit_id' => 4,
                'store_id' => 4,
                'shop_id' => 4,
                'supplier_id' => 3,
                'company_id' => 4,
            ],
            [
                'po_no' => 'INV-005',
                'order_date' => '2024-02-05',
                'delivery_date' => '2024-02-14',
                'po_type' => 'Auto',
                'po_mode' => 'Internal',
                'po_for' => 'Store',
                'notes' => '',
                'status' => 'Drafted',
                'grn_created' => 'No',
                'grn_received' => 'No',
                'prepared_by' => 1,
                'production_unit_id' => 5,
                'store_id' => 5,
                'shop_id' => 5,
                'supplier_id' => 3,
                'company_id' => 4,
            ],
        ];
        $now = \Carbon\Carbon::now();
        foreach ($purchaseOrders as $key => $purchaseOrder) {
            $purchaseOrders[$key]['created_at'] = $now;
            $purchaseOrders[$key]['updated_at'] = $now;
        }

        \App\PurchaseOrder::insert($purchaseOrders);
    }
}
