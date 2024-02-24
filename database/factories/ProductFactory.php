<?php

use Faker\Generator as Faker;

$factory->define(\App\Product::class, function (Faker $faker) {
    $type = [
        'Raw Material',
        'Finished Good'
    ];
    $measurement = \App\Measurement::all()->random();
    $account = \App\Account::all()->random();
    $typeValue = $type[array_rand($type)];
    if ($typeValue == 'Raw Material') {
        return [
            'code' => 'RM0000001',
            'name' => $faker->firstName,
            'type' => $typeValue,
            'buying_price' => $faker->numberBetween(1000, 9000),
            'expense_account' => $account->id ?? 1,
            'measurement' => $measurement->id ?? 1,
            'min_stock_level' => $faker->numberBetween(100, 900),
            'inventory_account' => $account->id ?? 1,
            'notes' => 'This is Fake',
            'is_active' => 'Yes',
        ];
    }
    return [
        'code' => 'FG0000001',
        'name' => $faker->firstName,
        'type' => $typeValue,
        'wholesale_price' => $faker->numberBetween(1020, 9000),
        'retail_price' => $faker->numberBetween(1020, 9000),
        'distribution_price' => $faker->numberBetween(1020, 9000),
        'income_account' => $account->id ?? 1,
        'measurement' => $measurement->id ?? 1,
        'min_stock_level' => $faker->numberBetween(100, 900),
        'inventory_account' => $account->id ?? 1,
        'notes' => 'This is Fake',
        'is_active' => 'Yes',
    ];
});
