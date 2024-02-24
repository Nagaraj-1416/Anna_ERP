<?php

use Faker\Generator as Faker;

$factory->define(\App\Store::class, function (Faker $faker) {
    return [
        'code' => 'S0000001',
        'name' => $faker->name,
        'phone' => $faker->numberBetween(0700000000, 9999999999),
        'fax' => $faker->numberBetween(0700000000, 9999999999),
        'mobile' => $faker->numberBetween(0700000000, 9999999999),
        'email' => $faker->safeEmail,
        'notes' => 'this is fake',
        'company_id' => 1,
        'is_active' => 'Yes'
    ];
});
