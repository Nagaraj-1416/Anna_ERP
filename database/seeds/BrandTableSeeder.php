<?php

use Illuminate\Database\Seeder;

class BrandTableSeeder extends Seeder
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
                'name' => 'Wrangler',
                'description' => 'Wrangler jeans',
                'is_active' => 'Yes',
                'is_deletable' => 'No',
            ],
            [
                'name' => 'Levi Strauss',
                'description' => 'Levi Strauss & Co.',
                'is_active' => 'Yes',
                'is_deletable' => 'No',
            ],
            [
                'name' => 'Calvin Klein',
                'description' => 'Calvin Klein Jeans',
                'is_active' => 'Yes',
                'is_deletable' => 'No',
            ],
            [
                'name' => 'Guess',
                'description' => 'Guess denim',
                'is_active' => 'Yes',
                'is_deletable' => 'No',
            ],
            [
                'name' => 'Diesel',
                'description' => 'Diesel jeans',
                'is_active' => 'Yes',
                'is_deletable' => 'No',
            ],
            [
                'name' => 'Tommy Hilfiger',
                'description' => 'Tommy Hilfiger Denim',
                'is_active' => 'Yes',
                'is_deletable' => 'No',
            ],
            [
                'name' => 'Gap',
                'description' => 'Gap denim',
                'is_active' => 'Yes',
                'is_deletable' => 'No',
            ],
            [
                'name' => 'H&M',
                'description' => 'H&M jeans',
                'is_active' => 'Yes',
                'is_deletable' => 'No',
            ],
            [
                'name' => 'Zara',
                'description' => 'Zara denim',
                'is_active' => 'Yes',
                'is_deletable' => 'No',
            ],
            [
                'name' => 'Uniqlo',
                'description' => 'Uniqlo jeans',
                'is_active' => 'Yes',
                'is_deletable' => 'No',
            ],
        ];
        

        $now = \Carbon\Carbon::now();
        foreach ($brands as $key => $brand) {
            $brands[$key]['created_at'] = $now;
            $brands[$key]['updated_at'] = $now;
        }

        \App\Brand::insert($brands);
    }
}