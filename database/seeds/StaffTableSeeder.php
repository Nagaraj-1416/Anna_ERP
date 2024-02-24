<?php

use Illuminate\Database\Seeder;

class StaffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $staffs = [
            [
                'code' => 'STF0000001',
                'salutation' => 'Mr.',
                'first_name' => 'System',
                'last_name' => 'Admin',
                'full_name' => 'System Admin',
                'short_name' => 'Admin',
                'gender' => 'Male',
                'dob' => '1990-01-01',
                'email' => 'admin@annaindustry.com',
                'mobile' => '00000000000',
                'joined_date' => '2000-01-01',
                // 'designation' => 'System Admin',
                'is_active' => 'Yes',
                'user_id' => '1'
            ],
            [
                'code' => 'STF0000002',
                'salutation' => 'Ms.',
                'first_name' => 'Jessica',
                'last_name' => 'Smith',
                'full_name' => 'Jessica Smith',
                'short_name' => 'Jess',
                'gender' => 'Female',
                'dob' => '1985-05-15',
                'email' => 'jessica.smith@example.com',
                'mobile' => '1234567890',
                'joined_date' => '2010-07-20',
                'is_active' => 'Yes',
                'user_id' => 2,
            ],
            [
                'code' => 'STF0000003',
                'salutation' => 'Mr.',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'full_name' => 'John Doe',
                'short_name' => 'John',
                'gender' => 'Male',
                'dob' => '1980-09-28',
                'email' => 'john.doe@example.com',
                'mobile' => '9876543210',
                'joined_date' => '2005-03-10',
                'is_active' => 'Yes',
                'user_id' => 3,
            ],
            [
                'code' => 'STF0000004',
                'salutation' => 'Ms.',
                'first_name' => 'Emily',
                'last_name' => 'Johnson',
                'full_name' => 'Emily Johnson',
                'short_name' => 'Emily',
                'gender' => 'Female',
                'dob' => '1992-12-03',
                'email' => 'emily.johnson@example.com',
                'mobile' => '1112223333',
                'joined_date' => '2015-06-25',
                'is_active' => 'Yes',
                'user_id' => 4,
            ],
            [
                'code' => 'STF0000005',
                'salutation' => 'Mr.',
                'first_name' => 'Michael',
                'last_name' => 'Williams',
                'full_name' => 'Michael Williams',
                'short_name' => 'Mike',
                'gender' => 'Male',
                'dob' => '1987-08-17',
                'email' => 'michael.williams@example.com',
                'mobile' => '4445556666',
                'joined_date' => '2008-11-30',
                'is_active' => 'Yes',
                'user_id' => 5,
            ],
        ];
        $now = \Carbon\Carbon::now();
        foreach ($staffs as $key => $user) {
            $staffs[$key]['created_at'] = $now;
            $staffs[$key]['updated_at'] = $now;
        }
        \App\Staff::insert($staffs);
        // Assign staff to company
        \App\Company::find(1)->staff()->attach([1]);
    }
}
