<?php

use Illuminate\Database\Seeder;

class EstimatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $estimates = [
                [
                    'estimate_no'=> 'ES0001',
                    'estimate_date'=> '2024-02-21',
                    'expiry_date'=> '2024-02-23',
                    'terms'=> 'Terms & conditions',
                    'notes'=>'Created estimates1',
                    'sub_total' => '14990',
                    'discount' => '10',
                    'discount_rate' => '150',
                    'discount_type' => 'Percentage',
                    'adjustment' => '1',
                    'total'=> '13492',
                    'status' => 'Accepted',
                    'rep_id' => 1,
                    'prepared_by' =>1,
                    'customer_id' => 1,
                    'business_type_id' =>1,
                    'company_id' =>1,
                    'converted_type' =>"dsfsd",
                    'converted_id' =>1
                ],
                [
                    'estimate_no'=> 'ES0002',
                    'estimate_date'=> '2024-02-21',
                    'expiry_date'=> '2024-02-23',
                    'terms'=> 'Terms & conditions',
                    'notes'=>'Created estimates2',
                    'sub_total' => '13990',
                    'discount' => '10',
                    'discount_rate' => '150',
                    'discount_type' => 'Percentage',
                    'adjustment' => '1',
                    'total'=> '12492',
                    'status' => 'Accepted',
                    'rep_id' => 2,
                    'prepared_by' =>1,
                    'customer_id' => 1,
                    'business_type_id' =>1,
                    'company_id' =>1,
                    'converted_type' =>"dsfsd",
                    'converted_id' =>1
                ],
                [
                    'estimate_no'=> 'ES0003',
                    'estimate_date'=> '2024-02-21',
                    'expiry_date'=> '2024-02-23',
                    'terms'=> 'Terms & conditions',
                    'notes'=>'Created estimates2',
                    'sub_total' => '3990',
                    'discount' => '10',
                    'discount_rate' => '50',
                    'discount_type' => 'Percentage',
                    'adjustment' => '1',
                    'total'=> '2492',
                    'status' => 'Accepted',
                    'rep_id' => 1,
                    'prepared_by' =>1,
                    'customer_id' => 1,
                    'business_type_id' =>1,
                    'company_id' =>1,
                    'converted_type' =>"dsfsd",
                    'converted_id' =>1
                ],
                [
                    'estimate_no'=> 'ES0004',
                    'estimate_date'=> '2024-02-21',
                    'expiry_date'=> '2024-02-23',
                    'terms'=> 'Terms & conditions',
                    'notes'=>'Created estimates2',
                    'sub_total' => '13990',
                    'discount' => '10',
                    'discount_rate' => '150',
                    'discount_type' => 'Percentage',
                    'adjustment' => '1',
                    'total'=> '12492',
                    'status' => 'Accepted',
                    'rep_id' => 2,
                    'prepared_by' =>1,
                    'customer_id' => 1,
                    'business_type_id' =>1,
                    'company_id' =>1,
                    'converted_type' =>"dsfsd",
                    'converted_id' =>1
                ],
                [
                    'estimate_no'=> 'ES0005',
                    'estimate_date'=> '2024-02-21',
                    'expiry_date'=> '2024-02-23',
                    'terms'=> 'Terms & conditions',
                    'notes'=>'Created estimates2',
                    'sub_total' => '14990',
                    'discount' => '10',
                    'discount_rate' => '150',
                    'discount_type' => 'Percentage',
                    'adjustment' => '1',
                    'total'=> '13492',
                    'status' => 'Accepted',
                    'rep_id' => 2,
                    'prepared_by' =>1,
                    'customer_id' => 1,
                    'business_type_id' =>1,
                    'company_id' =>1,
                    'converted_type' =>"dsfsd",
                    'converted_id' =>1
                ],
                ];
        $now = \Carbon\Carbon::now();
        foreach ($estimates as $key => $brand) {
                 $estimates[$key]['created_at'] = $now;
                $estimates[$key]['updated_at'] = $now;
                }
        
        \App\Estimate::insert($estimates);
        
    }
}