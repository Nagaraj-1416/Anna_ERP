<?php

use Illuminate\Database\Seeder;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $address = [
            [
                'street_one' => 'street 1',
                'street_two'=> '',
                'city'=> 'Chennai',
                'province'=>'Tamilnadu',
                'postal_code'=>'641006',
                'country_id'=> \App\Country::all()->random()->id ?? 1,
                'addressable_id'=>1,
                'addressable_type' =>'type', 
            ],
            [
                'street_one' => 'Thomas st, Left corner',
                'street_two'=> '',
                'city'=> 'Bengaluru',
                'province'=>'Karnataka',
                'postal_code'=>'641006',
                'country_id'=> \App\Country::all()->random()->id ?? 1,
                'addressable_id'=>1,
                'addressable_type' =>'type', 
            ],
            [
                'street_one' => 'Mukutu vinayagar st.,',
                'street_two'=> '',
                'city'=> 'Jaffna',
                'province'=>'Srilanka',
                'postal_code'=>'641006',
                'country_id'=> \App\Country::all()->random()->id ?? 1,
                'addressable_id'=>1,
                'addressable_type' =>'type', 
            ],
            [
                'street_one' => 'street 4',
                'street_two'=> '',
                'city'=> 'Jaffna',
                'province'=>'Srilanka',
                'postal_code'=>'641006',
                'country_id'=> \App\Country::all()->random()->id ?? 1,
                'addressable_id'=>1,
                'addressable_type' =>'type', 
            ],
            [
                'street_one' => 'street 5',
                'street_two'=> '',
                'city'=> 'Jaffna',
                'province'=>'Srilanka',
                'postal_code'=>'641006',
                'country_id'=> \App\Country::all()->random()->id ?? 1,
                'addressable_id'=>1,
                'addressable_type' =>'type', 
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($address as $key => $add) {
            $address[$key]['created_at'] = $now;
            $address[$key]['updated_at'] = $now;
        }

        \App\Address::insert($address);
    }
}
