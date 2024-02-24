<?php

use Faker\Generator as Faker;

$factory->define(\App\Route::class, function (Faker $faker) {
    return [
        'code' => 'RTE0000001',
        'name' => $faker->address,
        'notes' => 'this is fake',
        'is_active' => 'Yes',
        'start_point' => [
            'lat' => $faker->randomFloat(null, 6.056814, 9.765307),
            'lng' => $faker->randomFloat(null, 80.906234, 79.906234),
        ],
        'end_point' => [
            'lat' => $faker->randomFloat(null, 6.056814, 9.765307),
            'lng' => $faker->randomFloat(null, 80.906234, 79.906234),
        ],
    ];
});
