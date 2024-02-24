<?php

use Illuminate\Database\Seeder;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            [
                "code"=>"PR0001",
                'name' => 'Medical',
                'phone' => '04534534345',
                'fax' => '04534534344',
                'mobile' => '0453453434',
                'email' => 'medical@gmail.com',
                'notes' => 'entry for the medical department',
                'company_id' => 1,
                'is_active' => 'Yes',
            ],
            [
                "code"=>"PR0001",
                'name' => 'Food',
                'phone' => '04534534345',
                'fax' => '04534534344',
                'mobile' => '0453453434',
                'email' => 'food@gmail.com',
                'notes' => 'entry for the food department',
                'company_id' => 1,
                'is_active' => 'Yes',
            ],
            [
                "code"=>"PR0001",
                'name' => 'Maintanence',
                'phone' => '04534534345',
                'fax' => '04534534344',
                'mobile' => '0453453434',
                'email' => 'maintanence@gmail.com',
                'notes' => 'entry for the maintanence department',
                'company_id' => 1,
                'is_active' => 'Yes',
            ],
            [
                "code"=>"PR0001",
                'name' => 'Water Deparment',
                'phone' => '04534534345',
                'fax' => '04534534344',
                'mobile' => '0453453434',
                'email' => 'waterdepartment@gmail.com',
                'notes' => 'entry for the water department department',
                'company_id' => 1,
                'is_active' => 'Yes',
            ],
            [
                "code"=>"PR0001",
                'name' => 'Cleaning',
                'phone' => '04534534345',
                'fax' => '04534534344',
                'mobile' => '0453453434',
                'email' => 'cleaning@gmail.com',
                'notes' => 'entry for the cleaning department',
                'company_id' => 1,
                'is_active' => 'Yes',
            ],
            [
                "code"=>"PR0001",
                'name' => 'Store',
                'phone' => '04534534345',
                'fax' => '04534534344',
                'mobile' => '0453453434',
                'email' => 'store@gmail.com',
                'notes' => 'entry for the store department',
                'company_id' => 1,
                'is_active' => 'Yes',
            ],
            [
                "code"=>"PR0001",
                'name' => 'Electrical',
                'phone' => '04534534345',
                'fax' => '04534534344',
                'mobile' => '0453453434',
                'email' => 'electrical@gmail.com',
                'notes' => 'entry for the electrical department',
                'company_id' => 1,
                'is_active' => 'Yes',
            ],
            [
                "code"=>"PR0001",
                'name' => 'Computer Technology',
                'phone' => '04534534345',
                'fax' => '04534534344',
                'mobile' => '0453453434',
                'email' => 'computertechnology@gmail.com',
                'notes' => 'entry for the computertechnology department',
                'company_id' => 1,
                'is_active' => 'Yes',
            ],
            [
                "code"=>"PR0001",
                'name' => 'HR',
                'phone' => '04534534345',
                'fax' => '04534534344',
                'mobile' => '0453453434',
                'email' => 'hr@gmail.com',
                'notes' => 'entry for the hr department',
                'company_id' => 1,
                'is_active' => 'Yes',
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($departments as $key => $department) {
            $departments[$key]['created_at'] = $now;
            $departments[$key]['updated_at'] = $now;
        }

        \App\Department::insert($departments);
    }
}
