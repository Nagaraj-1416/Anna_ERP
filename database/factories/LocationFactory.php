<?php

use Faker\Generator as Faker;

$factory->define(\App\Location::class, function (Faker $faker) {
    return [
        'code' => 'RL0000001',
        'name' => $faker->streetName,
        'is_active' => 'Yes'
    ];
});
