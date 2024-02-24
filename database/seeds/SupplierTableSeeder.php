<?php

use Illuminate\Database\Seeder;

class SupplierTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $suppliers = [
            [
            'code'=>'S00001',
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
            'notes'=>'notes for registered the supplier',
            'company_id'=>1,
            'is_active'=>1,
            'opening_balance'=>678.89,
            'opening_balance_at'=>\Carbon\Carbon::now(),
            'opening_balance_type'=>'Debit',
            'supplierable_id'=>1,
            'supplierable_type'=>'Supplirable type 1',
            ],
            [
            'code'=>'S00002',
            'salutation'=>'Mr.',
            'first_name'=>'Marry',
            'last_name'=>'John',
            'full_name'=>'Marry John',
            'display_name'=>'Marry John',
            'phone'=>'4576895687',
            'fax'=>'5765675',
            'mobile'=>'4576895687',
            'email'=>'supplier2@gmail.com',
            'website'=>'https://supplier2@gmail.com',
            'type'=>'Internal',
            'notes'=>'notes for registered the supplier',
            'company_id'=>1,
            'is_active'=>1,
            'opening_balance'=>678.89,
            'opening_balance_at'=>\Carbon\Carbon::now(),
            'opening_balance_type'=>'Debit',
            'supplierable_id'=>1,
            'supplierable_type'=>'Supplirable type 2',
            ],
            [
            'code'=>'S00003',
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
            'type'=>'External',
            'notes'=>'notes for registered the supplier',
            'company_id'=>1,
            'is_active'=>1,
            'opening_balance'=>678.89,
            'opening_balance_at'=>\Carbon\Carbon::now(),
            'opening_balance_type'=>'Debit',
            'supplierable_id'=>1,
            'supplierable_type'=>'Supplirable type 3',
            ],
            
        ];

        $now = \Carbon\Carbon::now();
        foreach ($suppliers as $key => $supplier) {
            $suppliers[$key]['created_at'] = $now;
            $suppliers[$key]['updated_at'] = $now;
        }

        \App\Supplier::insert($suppliers);
    }
}
