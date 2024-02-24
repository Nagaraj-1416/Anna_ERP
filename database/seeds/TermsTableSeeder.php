<?php

use Illuminate\Database\Seeder;

class TermsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $terms = [
            [
                'code'=>'T0001',
                'description'=>'Description for the terms',
                'is_active'=>'Yes',
            ],
            [
                'code'=>'T0002',
                'description'=>'Description for the terms',
                'is_active'=>'Yes',
            ],
            [
                'code'=>'T0003',
                'description'=>'Description for the terms',
                'is_active'=>'Yes',
            ],
            [
                'code'=>'T0004',
                'description'=>'Description for the terms',
                'is_active'=>'Yes',
            ],
            [
                'code'=>'T0005',
                'description'=>'Description for the terms',
                'is_active'=>'Yes',
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($terms as $key => $term) {
            $terms[$key]['created_at'] = $now;
            $terms[$key]['updated_at'] = $now;
        }

        \App\Term::insert($terms);
    }
}
