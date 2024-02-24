<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'System Administrator',
                'description' => 'System Administrator',
                'is_deletable' => 'No',
                'access_level' => '500',
            ],
            [
                'name' => 'Administrator',
                'description' => 'Administrator',
                'is_deletable' => 'No',
                'access_level' => '490',
            ],
            [
                'name' => 'Managing Director',
                'description' => 'Managing Director',
                'is_deletable' => 'No',
                'access_level' => '480',
            ],
            [
                'name' => 'Branch Administrator',
                'description' => 'Branch Administrator',
                'is_deletable' => 'No',
                'access_level' => '470',
            ],
            [
                'name' => 'Department Head',
                'description' => 'Department Head',
                'is_deletable' => 'No',
                'access_level' => '460',
            ],
            [
                'name' => 'Account Manager',
                'description' => 'Account Manager',
                'is_deletable' => 'No',
                'access_level' => '450',
            ],
            [
                'name' => 'Accountant',
                'description' => 'Accountant',
                'is_deletable' => 'No',
                'access_level' => '440',
            ],
            [
                'name' => 'Account Assistant',
                'description' => 'Account Assistant',
                'is_deletable' => 'No',
                'access_level' => '430',
            ],
            [
                'name' => 'Administrative Officer',
                'description' => 'Administrative Officer',
                'is_deletable' => 'No',
                'access_level' => '420',
            ],
            [
                'name' => 'Administrative Assistant',
                'description' => 'Administrative Assistant',
                'is_deletable' => 'No',
                'access_level' => '410',
            ],
            [
                'name' => 'Business Development Manager',
                'description' => 'Business Development Manager',
                'is_deletable' => 'No',
                'access_level' => '400',
            ],
            [
                'name' => 'Store Manager',
                'description' => 'Store Manager',
                'is_deletable' => 'No',
                'access_level' => '390',
            ],
            [
                'name' => 'Store Keeper',
                'description' => 'Store Keeper',
                'is_deletable' => 'No',
                'access_level' => '380',
            ],
            [
                'name' => 'Cashier',
                'description' => 'Cashier',
                'is_deletable' => 'No',
                'access_level' => '370',
            ],
            [
                'name' => 'Front Office Manager',
                'description' => 'Front Office Manager',
                'is_deletable' => 'No',
                'access_level' => '360',
            ],
            [
                'name' => 'Front Office Executive',
                'description' => 'Front Office Executive',
                'is_deletable' => 'No',
                'access_level' => '350',
            ],
            [
                'name' => 'Sales Rep',
                'description' => 'Sales Rep',
                'is_deletable' => 'No',
                'access_level' => '200',
            ]
        ];
        $now = \Carbon\Carbon::now();
        foreach ($roles as $key => $role) {
            $roles[$key]['created_at'] = $now;
            $roles[$key]['updated_at'] = $now;
        }
        \App\Role::insert($roles);
    }
}
