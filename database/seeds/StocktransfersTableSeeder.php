<?php

use Illuminate\Database\Seeder;

class StocktransfersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stocktransfers = [
            [
                'date' => '2024-02-21',
                'transfer_by' => 1,
                'vehicle_id' => 1,
                'transfer_from' => 1,
                'transfer_to' => 2,
                'company_id' => 1,
                'status' => 'Received',
                'notes' => 'Stock Entry 1',
            ],
            [
                'date' => '2024-02-22',
                'transfer_by' => 1,
                'vehicle_id' => 2,
                'transfer_from' => 2,
                'transfer_to' => 3,
                'company_id' => 1,
                'status' => 'Pending',
                'notes' => 'Stock Entry 2',
            ],
            [
                'date' => '2024-02-23',
                'transfer_by' => 1,
                'vehicle_id' => 3,
                'transfer_from' => 3,
                'transfer_to' => 4,
                'company_id' => 1,
                'status' => 'Drafted',
                'notes' => 'Stock Entry 3',
            ],
            [
                'date' => '2024-02-24',
                'transfer_by' => 1,
                'vehicle_id' => 4,
                'transfer_from' => 4,
                'transfer_to' => 5,
                'company_id' => 1,
                'status' => 'Declined',
                'notes' => 'Stock Entry 4',
            ],
            [
                'date' => '2024-02-25',
                'transfer_by' => 1,
                'vehicle_id' => 3,
                'transfer_from' => 5,
                'transfer_to' => 6,
                'company_id' => 1,
                'status' => 'Received',
                'notes' => 'Stock Entry 5',
            ],
            [
                'date' => '2024-02-26',
                'transfer_by' => 1,
                'vehicle_id' => 1,
                'transfer_from' => 4,
                'transfer_to' => 7,
                'company_id' => 1,
                'status' => 'Pending',
                'notes' => 'Stock Entry 6',
            ],
            [
                'date' => '2024-02-27',
                'transfer_by' => 1,
                'vehicle_id' => 4,
                'transfer_from' => 7,
                'transfer_to' => 8,
                'company_id' => 1,
                'status' => 'Drafted',
                'notes' => 'Stock Entry 7',
            ],
            [
                'date' => '2024-02-28',
                'transfer_by' => 1,
                'vehicle_id' => 3,
                'transfer_from' => 8,
                'transfer_to' => 9,
                'company_id' => 1,
                'status' => 'Declined',
                'notes' => 'Stock Entry 8',
            ],
            [
                'date' => '2024-02-29',
                'transfer_by' => 1,
                'vehicle_id' => 1,
                'transfer_from' => 9,
                'transfer_to' => 10,
                'company_id' => 1,
                'status' => 'Received',
                'notes' => 'Stock Entry 9',
            ],
            [
                'date' => '2024-03-01',
                'transfer_by' => 1,
                'vehicle_id' => 2,
                'transfer_from' => 10,
                'transfer_to' => 1,
                'company_id' => 1,
                'status' => 'Pending',
                'notes' => 'Stock Entry 10',
            ],
        ];
        
            $now = \Carbon\Carbon::now();
            foreach ($stocktransfers as $key => $stocktransfer) {
                $stocktransfers[$key]['created_at'] = $now;
                $stocktransfers[$key]['updated_at'] = $now;
            }
            \App\StockTransfer::insert($stocktransfers);
    }
}