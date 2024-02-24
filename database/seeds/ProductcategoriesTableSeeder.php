<?php

use Illuminate\Database\Seeder;

class ProductcategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = [
            [
                'name' => 'Sample category 1',
            ],
            [
                'name' => 'Sample category 2',
            ],
            [
                'name' => 'Sample category 3',
            ],
            [
                'name' => 'Sample category 4',
            ],
            [
                'name' => 'Sample category 5',
            ]
        ];

        $now = \Carbon\Carbon::now();
        foreach ($brands as $key => $brand) {
            $brands[$key]['created_at'] = $now;
            $brands[$key]['updated_at'] = $now;
        }

        \App\ProductCategory::insert($brands);
    }
}
