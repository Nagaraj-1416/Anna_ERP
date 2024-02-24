<?php

use Illuminate\Database\Seeder;

class SalesinquiriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $salesinquiries=[
            [ 
                'code' => 'SO001',
                'inquiry_date' => \Carbon\Carbon::now(), 
                'customer_id' => 1, 
                'prepared_by' => 1,
                'description' => 'firstsalesinquiry',
                'business_type_id' => 1,
                'company_id' => 1, 
                'status' => 'Open',
                'converted_type' => 'ctype1',
                'converted_id' => 1,
            ],
            [ 
                'code' => 'SO002',
                'inquiry_date' => \Carbon\Carbon::now(), 
                'customer_id' => 1, 
                'prepared_by' => 1,
                'description' => 'second salesinquiry',
                'business_type_id' => 1,
                'company_id' => 1, 
                'status' => 'Open',
                'converted_type' => 'ctype2',
                'converted_id' => 1,
            ],
            [ 
                'code' => 'SO003',
                'inquiry_date' => \Carbon\Carbon::now(), 
                'customer_id' => 1, 
                'prepared_by' => 1,
                'description' => 'third salesinquiry',
                'business_type_id' => 1,
                'company_id' => 1, 
                'status' => 'Open',
                'converted_type' => 'ctype3',
                'converted_id' => 1,
            ],
            [ 
                'code' => 'SO003',
                'inquiry_date' => \Carbon\Carbon::now(), 
                'customer_id' => 1, 
                'prepared_by' => 1,
                'description' => 'third salesinquiry',
                'business_type_id' => 1,
                'company_id' => 1, 
                'status' => 'Converted to Estimate',
                'converted_type' => 'ctype3',
                'converted_id' => 1,
            ],
            [ 
                'code' => 'SO004',
                'inquiry_date' => \Carbon\Carbon::now(), 
                'customer_id' => 1, 
                'prepared_by' => 1,
                'description' => 'fourth salesinquiry',
                'business_type_id' => 1,
                'company_id' => 1, 
                'status' => 'Converted to Estimate',
                'converted_type' => 'ctype4',
                'converted_id' => 1,
            ],
            [ 
                'code' => 'SO005',
                'inquiry_date' => \Carbon\Carbon::now(), 
                'customer_id' => 1, 
                'prepared_by' => 1,
                'description' => 'five salesinquiry',
                'business_type_id' => 1,
                'company_id' => 1, 
                'status' => 'Converted to Order',
                'converted_type' => 'ctype5',
                'converted_id' => 1,
            ],
            [ 
                'code' => 'SO006',
                'inquiry_date' => \Carbon\Carbon::now(), 
                'customer_id' => 1, 
                'prepared_by' => 1,
                'description' => 'six salesinquiry',
                'business_type_id' => 1,
                'company_id' => 1, 
                'status' => 'Converted to Order',
                'converted_type' => 'ctype6',
                'converted_id' => 1,
            ],
            [ 
                'code' => 'SO007',
                'inquiry_date' => \Carbon\Carbon::now(), 
                'customer_id' => 1, 
                'prepared_by' => 1,
                'description' => 'seven salesinquiry',
                'business_type_id' => 1,
                'company_id' => 1, 
                'status' => 'Converted to Estimate',
                'converted_type' => 'ctype7',
                'converted_id' => 1,
            ],
            [ 
                'code' => 'SO008',
                'inquiry_date' => \Carbon\Carbon::now(), 
                'customer_id' => 1, 
                'prepared_by' => 1,
                'description' => 'eight salesinquiry',
                'business_type_id' => 1,
                'company_id' => 1, 
                'status' => 'Open',
                'converted_type' => 'ctype8',
                'converted_id' => 1,
            ],
            [ 
                'code' => 'SO009',
                'inquiry_date' => \Carbon\Carbon::now(), 
                'customer_id' => 1, 
                'prepared_by' => 1,
                'description' => 'nineth salesinquiry',
                'business_type_id' => 1,
                'company_id' => 1, 
                'status' => 'Open',
                'converted_type' => 'ctype9',
                'converted_id' => 1,
            ],
            [ 
                'code' => 'SO010',
                'inquiry_date' => \Carbon\Carbon::now(), 
                'customer_id' => 1, 
                'prepared_by' => 1,
                'description' => 'tenth salesinquiry',
                'business_type_id' => 1,
                'company_id' => 1, 
                'status' => 'Open',
                'converted_type' => 'ctype10',
                'converted_id' => 1,
            ]
            ];
            $now = \Carbon\Carbon::now();
            foreach ($salesinquiries as $key => $add) {
                $salesinquiries[$key]['created_at'] = $now;
                $salesinquiries[$key]['updated_at'] = $now;
            }
        
        \App\SalesInquiry::insert($salesinquiries);
    }
}