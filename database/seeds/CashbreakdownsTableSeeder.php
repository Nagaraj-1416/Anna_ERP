<?php

use Illuminate\Database\Seeder;

class CashbreakdownsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cash_breakdown = [
            [
                'date'=>	\Carbon\Carbon::now(),
                'rupee_type'=>	'Indian',
                'count'=>	34,
                'sales_handover_id'=>	1,
                'prepared_by'=>	1,
            ],
            [
                'date'=>	\Carbon\Carbon::now(),
                'rupee_type'=>	'American',
                'count'=>	26,
                'sales_handover_id'=>	2,
                'prepared_by'=>	1,
            ],
            [
                'date'=>	\Carbon\Carbon::now(),
                'rupee_type'=>	'Canada',
                'count'=>	12,
                'sales_handover_id'=>	3,
                'prepared_by'=>	1,
            ],
            [
                'date'=>	\Carbon\Carbon::now(),
                'rupee_type'=>	'indian',
                'count'=>	20,
                'sales_handover_id'=>	1,
                'prepared_by'=>	1,
            ],
            [
                'date'=>	\Carbon\Carbon::now(),
                'rupee_type'=>	'American',
                'count'=>	14,
                'sales_handover_id'=>	3,
                'prepared_by'=>	1,
            ],[
                'date'=>	\Carbon\Carbon::now(),
                'rupee_type'=>	'indian',
                'count'=>	9,
                'sales_handover_id'=> 4,
                'prepared_by'=>	1,
            ],
            [
                'date'=>	\Carbon\Carbon::now(),
                'rupee_type'=>	'Canada',
                'count'=>	4,
                'sales_handover_id'=>	5,
                'prepared_by'=>	1,
            ]
        ];

        $now = \Carbon\Carbon::now();
        foreach ($cash_breakdown as $key => $add) {
            $cash_breakdown[$key]['created_at'] = $now;
            $cash_breakdown[$key]['updated_at'] = $now;
        }

        \App\CashBreakdown::insert($cash_breakdown);
    }
}