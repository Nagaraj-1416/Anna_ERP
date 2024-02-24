<?php

use Illuminate\Database\Seeder;

class DesignationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $designations = [
            [
                'name' => 'Account Manager'
            ],
            [
                'name' => 'Accountant'
            ],
            [
                'name' => 'Account Assistant'
            ],
            [
                'name' => 'Administrative Officer'
            ],
            [
                'name' => 'Administrative Assistant'
            ],
            [
                'name' => 'Assistant'
            ],
            [
                'name' => 'Branch Manager'
            ],
            [
                'name' => 'Business Development Manager'
            ],
            [
                'name' => 'Chashier'
            ],
            [
                'name' => 'Cleaner'
            ],
            [
                'name' => 'Director'
            ],
            [
                'name' => 'Driver'
            ],
            [
                'name' => 'Front Office Manager'
            ],
            [
                'name' => 'Front Office Executive'
            ],
            [
                'name' => 'Helper'
            ],
            [
                'name' => 'Manager'
            ],
            [
                'name' => 'Managing Director'
            ],
            [
                'name' => 'Rep'
            ],
            [
                'name' => 'Shop Manager'
            ],
            [
                'name' => 'Store Keeper'
            ],
            [
                'name' => 'Store Manager'
            ]
        ];
        $now = \Carbon\Carbon::now();
        foreach ($designations as $key => $designation) {
            $designations[$key]['created_at'] = $now;
            $designations[$key]['updated_at'] = $now;
        }
        \App\Designation::insert($designations);
    }
}
