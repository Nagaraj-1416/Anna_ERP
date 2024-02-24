<?php

use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customers = [
            [
                'code'=>'C00001',
                'salutation'=>'Mr.',
                'first_name'=>'Kate',
                'last_name'=>'Williams',
                'full_name'=>'Kate Williams',
                'display_name'=>'Kate Williams',
                'phone'=>'4576895687',
                'fax'=>'5765675',
                'mobile'=>'4576895687',
                'email'=>'supplier1@gmail.com',
                'website'=>'https://supplier1@gmail.com',
                'type'=>'Internal',
                'gps_lat'=>'45 deg W',
                'gps_long'=>'45 deg N',
                'cl_amount'=>345.656,
                'cl_notify_rate'=>23423.54,
                'notes'=>'notes for the entry of customer details',
                'is_active'=>1,
                'route_id'=>1,
                'location_id'=>1,
                'company_id'=>1,
                'opening_balance'=>345.45,
                'opening_balance_at'=>\Carbon\Carbon::now(),
                'opening_balance_type'=>'Debit',
            ],
            [
                'code'=>'C00002',
                'salutation'=>'Mr.',
                'first_name'=>'Peter',
                'last_name'=>'Parker',
                'full_name'=>'Peter Parker',
                'display_name'=>'Peter Parker',
                'phone'=>'4576895687',
                'fax'=>'5765675',
                'mobile'=>'4576895687',
                'email'=>'supplier3@gmail.com',
                'website'=>'https://supplier3@gmail.com',
                'type'=>'Internal',
                'gps_lat'=>'45 deg W',
                'gps_long'=>'45 deg N',
                'cl_amount'=>345.656,
                'cl_notify_rate'=>23423.54,
                'notes'=>'notes for the entry of customer details',
                'is_active'=>1,
                'route_id'=>1,
                'location_id'=>1,
                'company_id'=>1,
                'opening_balance'=>345.45,
                'opening_balance_at'=>\Carbon\Carbon::now(),
                'opening_balance_type'=>'Debit',
            ],
            [
                'code'=>'C00003',
                'salutation'=>'Mr.',
                'first_name'=>'Kate',
                'last_name'=>'Williams',
                'full_name'=>'Kate Williams',
                'display_name'=>'Kate Williams',
                'phone'=>'4576895687',
                'fax'=>'5765675',
                'mobile'=>'4576895687',
                'email'=>'supplier1@gmail.com',
                'website'=>'https://supplier1@gmail.com',
                'type'=>'Internal',
                'gps_lat'=>'45 deg W',
                'gps_long'=>'45 deg N',
                'cl_amount'=>345.656,
                'cl_notify_rate'=>23423.54,
                'notes'=>'notes for the entry of customer details',
                'is_active'=>1,
                'route_id'=>1,
                'location_id'=>1,
                'company_id'=>1,
                'opening_balance'=>345.45,
                'opening_balance_at'=>\Carbon\Carbon::now(),
                'opening_balance_type'=>'Debit',
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($customers as $key => $add) {
            $customers[$key]['created_at'] = $now;
            $customers[$key]['updated_at'] = $now;
        }

        \App\Customer::insert($customers);
    }
}
