<?php

use Illuminate\Database\Seeder;

class TransferitemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transferitems = [
            [
                'date' => '2024-02-23',
                'transfer_id' => 4,
                'amount' => '5000.00',
                'cheque_no' => 'CHQ001',
                'cheque_date' => '2024-02-24',
                'cheque_type' => 'Own',
                'bank_id' => 1,
                'status' => 'Pending',
            ],
            [
                'date' => '2024-02-24',
                'transfer_id' => 3,
                'amount' => '3000.00',
                'cheque_no' => 'CHQ002',
                'cheque_date' => '2024-02-25',
                'cheque_type' => 'Third Party',
                'bank_id' => 2,
                'status' => 'Received',
            ],
            [
                'date' => '2024-02-25',
                'transfer_id' => 2,
                'amount' => '2500.00',
                'cheque_no' => 'CHQ003',
                'cheque_date' => '2024-02-26',
                'cheque_type' => 'Own',
                'bank_id' => 3,
                'status' => 'Declined',
            ],
            [
                'date' => '2024-02-26',
                'transfer_id' => 1,
                'amount' => '4000.00',
                'cheque_no' => 'CHQ004',
                'cheque_date' => '2024-02-27',
                'cheque_type' => 'Third Party',
                'bank_id' => 4,
                'status' => 'Declined',
            ],
            [
                'date' => '2024-02-27',
                'transfer_id' => 5,
                'amount' => '7000.00',
                'cheque_no' => 'CHQ005',
                'cheque_date' => '2024-02-28',
                'cheque_type' => 'Own',
                'bank_id' => 5,
                'status' => 'Pending',
            ],
            [
                'date' => '2024-02-28',
                'transfer_id' => 4,
                'amount' => '6000.00',
                'cheque_no' => 'CHQ006',
                'cheque_date' => '2024-02-29',
                'cheque_type' => 'Third Party',
                'bank_id' => 6,
                'status' => 'Received',
            ],
            [
                'date' => '2024-02-29',
                'transfer_id' => 3,
                'amount' => '8000.00',
                'cheque_no' => 'CHQ007',
                'cheque_date' => '2024-03-01',
                'cheque_type' => 'Own',
                'bank_id' => 7,
                'status' => 'Pending',
            ],
            [
                'date' => '2024-03-01',
                'transfer_id' => 2,
                'amount' => '3500.00',
                'cheque_no' => 'CHQ008',
                'cheque_date' => '2024-03-02',
                'cheque_type' => 'Third Party',
                'bank_id' => 8,
                'status' => 'Received',
            ],
            [
                'date' => '2024-03-02',
                'transfer_id' => 1,
                'amount' => '5500.00',
                'cheque_no' => 'CHQ009',
                'cheque_date' => '2024-03-03',
                'cheque_type' => 'Own',
                'bank_id' => 9,
                'status' => 'Pending',
            ],
            [
                'date' => '2024-03-03',
                'transfer_id' => 4,
                'amount' => '4500.00',
                'cheque_no' => 'CHQ010',
                'cheque_date' => '2024-03-04',
                'cheque_type' => 'Third Party',
                'bank_id' => 10,
                'status' => 'Declined',
            ],
        ];
            $now = \Carbon\Carbon::now();
            foreach ($transferitems as $key => $transferitem) {
                $transferitems[$key]['created_at'] = $now;
                $transferitems[$key]['updated_at'] = $now;
            }
            \App\TransferItem::insert($transferitems);
    }
}