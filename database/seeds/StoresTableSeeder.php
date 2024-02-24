<?php

use Illuminate\Database\Seeder;

class StoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stores = [
            [
            "code"=>"PR0001",
            'name'=>'Store 1',
            'phone'=>4454565,
            'fax'=>34543645,
            'mobile'=>34556456,
            'email'=>'store1@gmail.com',
            'notes'=>'notes entry for the store is entered correctly',
            'company_id'=>1,
            'is_active'=>1,
            ],
            [
            "code"=>"PR0002",
            'name'=>'Store 2',
            'phone'=>4454565,
            'fax'=>34543645,
            'mobile'=>34556456,
            'email'=>'store2@gmail.com',
            'notes'=>'notes entry for the store is entered correctly',
            'company_id'=>1,
            'is_active'=>1,
            ],
            [
                "code"=>"PR0003",
            'name'=>'Store 3',
            'phone'=>4454565,
            'fax'=>34543645,
            'mobile'=>34556456,
            'email'=>'store4@gmail.com',
            'notes'=>'notes entry for the store is entered correctly',
            'company_id'=>1,
            'is_active'=>1,
            ],
            [
                "code"=>"PR0004",
            'name'=>'Store 4',
            'phone'=>4454565,
            'fax'=>34543645,
            'mobile'=>34556456,
            'email'=>'store5@gmail.com',
            'notes'=>'notes entry for the store is entered correctly',
            'company_id'=>1,
            'is_active'=>1,
            ],
            [
                "code"=>"PR0005",
            'name'=>'Store 5',
            'phone'=>4454565,
            'fax'=>34543645,
            'mobile'=>34556456,
            'email'=>'store6@gmail.com',
            'notes'=>'notes entry for the store is entered correctly',
            'company_id'=>1,
            'is_active'=>1,
            ],
            [
                "code"=>"PR0006",
            'name'=>'Store 6',
            'phone'=>4454565,
            'fax'=>34543645,
            'mobile'=>34556456,
            'email'=>'store7@gmail.com',
            'notes'=>'notes entry for the store is entered correctly',
            'company_id'=>1,
            'is_active'=>1,
            ],
            [
                "code"=>"PR0007",
            'name'=>'Store 7',
            'phone'=>4454565,
            'fax'=>34543645,
            'mobile'=>34556456,
            'email'=>'store8@gmail.com',
            'notes'=>'notes entry for the store is entered correctly',
            'company_id'=>1,
            'is_active'=>1,
            ],
            [
                "code"=>"PR0008",
            'name'=>'Store 8',
            'phone'=>4454565,
            'fax'=>34543645,
            'mobile'=>34556456,
            'email'=>'store9@gmail.com',
            'notes'=>'notes entry for the store is entered correctly',
            'company_id'=>1,
            'is_active'=>1,
            ],
            [
                "code"=>"PR0009",
            'name'=>'Store 9',
            'phone'=>4454565,
            'fax'=>34543645,
            'mobile'=>34556456,
            'email'=>'store10@gmail.com',
            'notes'=>'notes entry for the store is entered correctly',
            'company_id'=>1,
            'is_active'=>1,
            ],
            [
                "code"=>"PR00010",
            'name'=>'Store 10',
            'phone'=>4454565,
            'fax'=>34543645,
            'mobile'=>34556456,
            'email'=>'store11@gmail.com',
            'notes'=>'notes entry for the store is entered correctly',
            'company_id'=>1,
            'is_active'=>1,
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($stores as $key => $add) {
            $stores[$key]['created_at'] = $now;
            $stores[$key]['updated_at'] = $now;
        }

        \App\Store::insert($stores);
    }
}
