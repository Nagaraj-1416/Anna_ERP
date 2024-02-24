<?php

use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = [
            [
                'code' => 'COM0000001',
                'name' => 'AnnA Industry',
                'display_name' => 'AnnA Industry',
                'phone' => '0218814225',
                'fax' => '0218814220',
                'mobile' => '0778814225',
                'email' => 'info@annaindustry.com',
                'website' => 'https://annaindustry.com',
                'business_location' => 'Jaffna, Sri Lanka',
                'base_currency' => 'LKR',
                'fy_starts_month' => '4',
                'fy_starts_from' => 'Start',
                'timezone' => 'Asia/Colombo',
                'date_time_format' => 'F j, Y, g:i A',
                'business_starts_at' => '07:00:00',
                'business_end_at' => '17:00:00',
                'is_active' => 'Yes',
            ],
            [
                'code' => 'COM0000002',
                'name' => 'AnnA Farm',
                'display_name' => 'AnnA Industry',
                'phone' => '0218814225',
                'fax' => '0218814220',
                'mobile' => '0778814225',
                'email' => 'info@annafarm.com',
                'website' => 'https://annafarm.com',
                'business_location' => 'Jaffna, Sri Lanka',
                'base_currency' => 'LKR',
                'fy_starts_month' => '4',
                'fy_starts_from' => 'Start',
                'timezone' => 'Asia/Colombo',
                'date_time_format' => 'F j, Y, g:i A',
                'business_starts_at' => '07:00:00',
                'business_end_at' => '17:00:00',
                'is_active' => 'Yes',
            ],
            [
                'code' => 'COM0000003',
                'name' => 'AnnA International',
                'display_name' => 'AnnA Industry',
                'phone' => '0218814225',
                'fax' => '0218814220',
                'mobile' => '0778814225',
                'email' => 'info@annainternational.com',
                'website' => 'https://annainternational.com',
                'business_location' => 'Jaffna, Sri Lanka',
                'base_currency' => 'LKR',
                'fy_starts_month' => '4',
                'fy_starts_from' => 'Start',
                'timezone' => 'Asia/Colombo',
                'date_time_format' => 'F j, Y, g:i A',
                'business_starts_at' => '07:00:00',
                'business_end_at' => '17:00:00',
                'is_active' => 'Yes',
            ],
            [
                'code' => 'COM0000004',
                'name' => 'AnnA NTK',
                'display_name' => 'AnnA Industry',
                'phone' => '0218814225',
                'fax' => '0218814220',
                'mobile' => '0778814225',
                'email' => 'info@annantk.com',
                'website' => 'https://annantk.com',
                'business_location' => 'Jaffna, Sri Lanka',
                'base_currency' => 'LKR',
                'fy_starts_month' => '4',
                'fy_starts_from' => 'Start',
                'timezone' => 'Asia/Colombo',
                'date_time_format' => 'F j, Y, g:i A',
                'business_starts_at' => '07:00:00',
                'business_end_at' => '17:00:00',
                'is_active' => 'Yes',
            ]
        ];
        $now = \Carbon\Carbon::now();
        foreach ($companies as $key => $company) {
            $companies[$key]['created_at'] = $now;
            $companies[$key]['updated_at'] = $now;
        }
        \App\Company::insert($companies);
    }
}
