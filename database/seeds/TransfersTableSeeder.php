<?php

use Illuminate\Database\Seeder;

class TransfersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transfers=[
            [
                'type' => 'Cash',
                'date' => '2024-02-22',
                'amount' => '1000.00',
                'transfer_by' => 1,
                'sender' => 1,
                'receiver' =>1,
                'credited_to' => 1,
                'debited_to' => 1,
                'status' => 'Pending',
                'received_by' => 1,
                'received_on' => '2024-02-21',
                'received_amount' => '2000.00',
                'transaction_id' => 1,
                'notes' => '',
                'transfer_mode' => 'ByHand',
                'handed_over_date' => '2024-02-22',
                'handed_over_time' => '05:30:00',
                'handed_order_to' => 1,
                'deposited_date' => '2024-02-21',
                'deposited_time' => '07:30:00',
                'deposited_to' => 1,
                'deposited_receipt' =>'success',
                'receipt_uploaded_on' =>'2024-02-22',
                'receipt_uploaded_by' => 1,
            ],
                [
                    'type' => 'Cash',
                    'date' => '2024-02-22',
                    'amount' => '1000.00',
                    'transfer_by' => 1,
                    'sender' => 1,
                    'receiver' =>1,
                    'credited_to' => 1,
                    'debited_to' => 1,
                    'status' => 'Drafted',
                    'received_by' => 1,
                    'received_on' => '2024-02-21',
                    'received_amount' => '3000.00',
                    'transaction_id' => 2,
                    'notes' => '',
                    'transfer_mode' => 'ByHand',
                    'handed_over_date' => '2024-02-22',
                    'handed_over_time' => '05:30:00',
                    'handed_order_to' => 1,
                    'deposited_date' => '2024-02-21',
                    'deposited_time' => '07:30:00',
                    'deposited_to' => 1,
                    'deposited_receipt' =>'failed',
                    'receipt_uploaded_on' =>'2024-02-22',
                    'receipt_uploaded_by' => 1,
                ],
                [
                    'type' => 'Cheque',
                    'date' => '2024-02-22',
                    'amount' => '5000.00',
                    'transfer_by' => 1,
                    'sender' => 1,
                    'receiver' =>1,
                    'credited_to' => 1,
                    'debited_to' => 1,
                    'status' => 'Received',
                    'received_by' => 1,
                    'received_on' => '2024-02-21',
                    'received_amount' => '3000.00',
                    'transaction_id' => 3,
                    'notes' => 'Issued cheque for rent payment',
                    'transfer_mode' => 'ByHand',
                    'handed_over_date' => '2024-02-22',
                    'handed_over_time' => '05:30:00',
                    'handed_order_to' => 1,
                    'deposited_date' => '2024-02-21',
                    'deposited_time' => '07:30:00',
                    'deposited_to' => 1,
                    'deposited_receipt' =>'failed',
                    'receipt_uploaded_on' =>'2024-02-22',
                    'receipt_uploaded_by' => 1,
                ],
                [
                    'type' => 'Cash',
                    'date' => '2024-02-22',
                    'amount' => '5000.00',
                    'transfer_by' => 1,
                    'sender' => 1,
                    'receiver' =>1,
                    'credited_to' => 1,
                    'debited_to' => 1,
                    'status' => 'Pending',
                    'received_by' => 1,
                    'received_on' => '2024-02-22',
                    'received_amount' => '6500.00',
                    'transaction_id' => 3,
                    'notes' => 'Bank transfer for supplier payment',
                    'transfer_mode' => 'DepositedToBank',
                    'handed_over_date' => '2024-02-22',
                    'handed_over_time' => '05:30:00',
                    'handed_order_to' => 1,
                    'deposited_date' => '2024-02-21',
                    'deposited_time' => '07:30:00',
                    'deposited_to' => 1,
                    'deposited_receipt' =>'failed',
                    'receipt_uploaded_on' =>'2024-02-22',
                    'receipt_uploaded_by' => 1,
                ],
                [
                    'type' => 'Cash',
                    'date' => '2024-02-23',
                    'amount' => '5000.00',
                    'transfer_by' => 1,
                    'sender' => 1,
                    'receiver' =>1,
                    'credited_to' => 1,
                    'debited_to' => 1,
                    'status' => 'Drafted',
                    'received_by' => 1,
                    'received_on' => '2024-02-23',
                    'received_amount' => '8500.00',
                    'transaction_id' => 4,
                    'notes' => 'Bank transfer for supplier payment',
                    'transfer_mode' => 'DepositedToBank',
                    'handed_over_date' => '2024-02-22',
                    'handed_over_time' => '05:30:00',
                    'handed_order_to' => 1,
                    'deposited_date' => '2024-02-21',
                    'deposited_time' => '07:30:00',
                    'deposited_to' => 1,
                    'deposited_receipt' =>'failed',
                    'receipt_uploaded_on' =>'2024-02-22',
                    'receipt_uploaded_by' => 1,
                ]
                
            ];
            $now = \Carbon\Carbon::now();
        foreach ($transfers as $key => $transfer) {
            $transfers[$key]['created_at'] = $now;
            $transfers[$key]['updated_at'] = $now;
        }
        \App\Transfer::insert($transfers);
    }
}