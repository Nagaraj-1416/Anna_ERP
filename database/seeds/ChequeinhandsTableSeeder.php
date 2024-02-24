<?php

use Illuminate\Database\Seeder;

class ChequeinhandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chequeinhands = [
            [
                'registered_date'=>	\Carbon\Carbon::now(),
                'type'=>	'Auto',
                'amount'=>	345.989,
                'cheque_type'=>	'Own',
                'cheque_date'=>	\Carbon\Carbon::now(),
                'cheque_no'=>	'789562xxxx32324',
                'bank_id'=>	1,
                'chequeable_id'=>	1,
                'chequeable_type'=>	'cheque type 1',
                'customer_id'=>	1,
                'daily_sale_id'=>	1,
                'sales_handover_id'=>	3,
                'notes'=>	'Cheque entry one',
                'status'=>	'Deposited',
                'credited_to'=>	1,
                'deposited_to'=>	1,
                'prepared_by'=>	1,
                'business_type_id'=>	1,
                'company_id'=>	1,
                'is_transferred'=>	'Yes',
                'transferred_from'=>	1,
                'transferred_to'=>	1,
                'settled'=>	'Yes',
            ],
            [
                'registered_date'=>	\Carbon\Carbon::now(),
                'type'=>	'Manual',
                'amount'=>	235.756,
                'cheque_type'=>	'Third Party',
                'cheque_date'=>	\Carbon\Carbon::now(),
                'cheque_no'=>	'789562xxxx06712',
                'bank_id'=>	2,
                'chequeable_id'=>	1,
                'chequeable_type'=>	'cheque type 2',
                'customer_id'=>	1,
                'daily_sale_id'=>	2,
                'sales_handover_id'=>	3,
                'notes'=>	'Cheque entry two',
                'status'=>	'Deposited',
                'credited_to'=>	1,
                'deposited_to'=>	1,
                'prepared_by'=>	1,
                'business_type_id'=>	1,
                'company_id'=>	1,
                'is_transferred'=>	'Yes',
                'transferred_from'=>	1,
                'transferred_to'=>	1,
                'settled'=>	'Yes',
            ],
            [
                'registered_date'=>	\Carbon\Carbon::now(),
                'type'=>	'Auto',
                'amount'=>	645.235,
                'cheque_type'=>	'Own',
                'cheque_date'=>	\Carbon\Carbon::now(),
                'cheque_no'=>	'789562xxxx03679',
                'bank_id'=>	2,
                'chequeable_id'=>	1,
                'chequeable_type'=>	'cheque type 3',
                'customer_id'=>	1,
                'daily_sale_id'=>	2,
                'sales_handover_id'=>	4,
                'notes'=>	'Cheque entry three',
                'status'=>	'Deposited',
                'credited_to'=>	1,
                'deposited_to'=>	1,
                'prepared_by'=>	1,
                'business_type_id'=>	1,
                'company_id'=>	1,
                'is_transferred'=>	'Yes',
                'transferred_from'=>	1,
                'transferred_to'=>	1,
                'settled'=>	'Yes',
            ],
            [
                'registered_date'=>	\Carbon\Carbon::now(),
                'type'=>	'Auto',
                'amount'=>	729.564,
                'cheque_type'=>	'Own',
                'cheque_date'=>	\Carbon\Carbon::now(),
                'cheque_no'=>	'789562xxxx02123',
                'bank_id'=>	3,
                'chequeable_id'=>	1,
                'chequeable_type'=>	'cheque type 4',
                'customer_id'=>	1,
                'daily_sale_id'=>	1,
                'sales_handover_id'=>	2,
                'notes'=>	'Cheque entry four',
                'status'=>	'Deposited',
                'credited_to'=>	1,
                'deposited_to'=>	1,
                'prepared_by'=>	1,
                'business_type_id'=>	1,
                'company_id'=>	1,
                'is_transferred'=>	'Yes',
                'transferred_from'=>	1,
                'transferred_to'=>	1,
                'settled'=>	'Yes',
            ],
            [
                'registered_date'=>	\Carbon\Carbon::now(),
                'type'=>	'Auto',
                'amount'=>	123.456,
                'cheque_type'=>	'Own',
                'cheque_date'=>	\Carbon\Carbon::now(),
                'cheque_no'=>	'89562xxxx01234',
                'bank_id'=>	4,
                'chequeable_id'=>	1,
                'chequeable_type'=>	'chequetype5',
                'customer_id'=>	1,
                'daily_sale_id'=>	3,
                'sales_handover_id'=>	1,
                'notes'=>	'Cheque entry five',
                'status'=>	'Deposited',
                'credited_to'=>	1,
                'deposited_to'=>	1,
                'prepared_by'=>	1,
                'business_type_id'=>	1,
                'company_id'=>	1,
                'is_transferred'=>	'Yes',
                'transferred_from'=>	1,
                'transferred_to'=>	1,
                'settled'=>	'Yes',
            ]
        ];

        $now = \Carbon\Carbon::now();
        foreach ($chequeinhands as $key => $add) {
            $chequeinhands[$key]['created_at'] = $now;
            $chequeinhands[$key]['updated_at'] = $now;
        }

        \App\ChequeInHand::insert($chequeinhands);
    }
}