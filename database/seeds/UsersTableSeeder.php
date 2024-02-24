<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'AnnA',
                'email' => 'admin@annaindustry.com',
                'password' => bcrypt('123456'),
                'is_active' => 'Yes',
                'role_id' => '1'
            ],
            [
                'name' => 'Nagaraj',
                'email' => 'admin1@annaindustry.com',
                'password' => bcrypt('123456'),
                'is_active' => 'Yes',
                'role_id' => '2'
            ],
            [
                'name' => 'John Thomas',
                'email' => 'admin2@annaindustry.com',
                'password' => bcrypt('123456'),
                'is_active' => 'Yes',
                'role_id' => '3'
            ],
            [
                'name' => 'Praveen Arumugam',
                'email' => 'admin3@annaindustry.com',
                'password' => bcrypt('123456'),
                'is_active' => 'Yes',
                'role_id' => '4'
            ],
            [
                'name' => 'Williams',
                'email' => 'admin4@annaindustry.com',
                'password' => bcrypt('123456'),
                'is_active' => 'Yes',
                'role_id' => '5'
            ],
            [
                'name' => 'Peter',
                'email' => 'admin5@annaindustry.com',
                'password' => bcrypt('123456'),
                'is_active' => 'Yes',
                'role_id' => '6'
            ],
            [
                'name' => 'Jake',
                'email' => 'admin6@annaindustry.com',
                'password' => bcrypt('123456'),
                'is_active' => 'Yes',
                'role_id' => '7'
            ],
            [
                'name' => 'Alan',
                'email' => 'admin7@annaindustry.com',
                'password' => bcrypt('123456'),
                'is_active' => 'Yes',
                'role_id' => '8'
            ],
            [
                'name' => 'Tomy',
                'email' => 'admin8@annaindustry.com',
                'password' => bcrypt('123456'),
                'is_active' => 'Yes',
                'role_id' => '9'
            ],
            [
                'name' => 'Kate',
                'email' => 'admin9@annaindustry.com',
                'password' => bcrypt('123456'),
                'is_active' => 'Yes',
                'role_id' => '10'
            ]
        ];
        $now = \Carbon\Carbon::now();
        foreach ($users as $key => $user) {
            $users[$key]['created_at'] = $now;
            $users[$key]['updated_at'] = $now;
        }
        \App\User::insert($users);
    }
}


