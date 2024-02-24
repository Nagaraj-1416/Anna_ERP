<?php

use Illuminate\Database\Seeder;

class ContactpersonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contactpersons = [
            [  	
            'salutation'=>	'Mr.',
            'first_name'=>	'Kate',
            'last_name'=>	'Williams',
            'full_name'=>	'Kate Williams',
            'phone'=>	'4576895687',
            'mobile'=>	'4576895687',
            'email'=>	'supplier1@gmail.com',
            'designation'=>	' System Administrator',
            'department'=>	'food',
            'contact_personable_id'=>	1,
            'contact_personable_type'=>	'SampleType 1',
            'is_active'=>	'Yes',
            ],
            [  	
                'salutation' => 'Mr.',
                'first_name' => 'John',
                'last_name' => 'Smith',
                'full_name' => 'John Smith',
                'phone' => '1234567890',
                'mobile' => '1234567890',
                'email' => 'john.smith@example.com',
                'designation' => 'Developer',
                'department' => 'IT',
                'contact_personable_id' => 1,
                'contact_personable_type' => 'SampleType 1',
                'is_active' => 'Yes',
            ],
            [  	
                'salutation' => 'Ms.',
                'first_name' => 'Sarah',
                'last_name' => 'Jones',
                'full_name' => 'Sarah Jones',
                'phone' => '7894561230',
                'mobile' => '7894561230',
                'email' => 'sarah.jones@example.com',
                'designation' => 'Coordinator',
                'department' => 'Marketing',
                'contact_personable_id' => 1,
                'contact_personable_type' => 'SampleType 1',
                'is_active' => 'Yes',
            ],
            [  	
                'salutation' => 'Mr.',
                'first_name' => 'David',
                'last_name' => 'Miller',
                'full_name' => 'David Miller',
                'phone' => '3216549870',
                'mobile' => '3216549870',
                'email' => 'david.miller@example.com',
                'designation' => 'Engineer',
                'department' => 'Engineering',
                'contact_personable_id' => 1,
                'contact_personable_type' => 'SampleType 1',
                'is_active' => 'Yes',
            ],
            [  	
                'salutation' => 'Ms.',
                'first_name' => 'Jessica',
                'last_name' => 'Davis',
                'full_name' => 'Jessica Davis',
                'phone' => '6549873210',
                'mobile' => '6549873210',
                'email' => 'jessica.davis@example.com',
                'designation' => 'Consultant',
                'department' => 'Consultant',
                'contact_personable_id' => 1,
                'contact_personable_type' => 'SampleType 1',
                'is_active' => 'Yes',
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($contactpersons as $key => $brand) {
            $contactpersons[$key]['created_at'] = $now;
            $contactpersons[$key]['updated_at'] = $now;
        }

        \App\ContactPerson::insert($contactpersons);
    }
}
