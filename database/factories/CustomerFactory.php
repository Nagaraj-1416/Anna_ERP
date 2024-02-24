<?php

use Faker\Generator as Faker;

$factory->define(\App\Customer::class, function (Faker $faker) {
    $salutation = ['Mr.' => 'Mr.', 'Mrs.' => 'Mrs.', 'Ms.' => 'Ms.', 'Miss.' => 'Miss.', 'Dr.' => 'Dr.'];
    $salutationName = $salutation[array_rand($salutation)];
    $route = \App\Route::all()->pluck('id')->toArray();
    $routeId = $route[array_rand($route)];
    $location = \App\Route::find($routeId)->locations()->get()->pluck('id')->toArray();
    $locationId = $location[array_rand($location)];
    $firstName = $faker->firstName;
    $lastName = $faker->lastName;
    return [
        'code' => 'CUS0000001',
        'salutation' => $salutationName,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'full_name' => $salutationName . ' ' . $firstName . ' ' . $lastName,
        'display_name' => $faker->name,
        'phone' => $faker->numberBetween(0000000000, 9999999999),
        'fax' => $faker->numberBetween(0000000000, 9999999999),
        'mobile' => $faker->numberBetween(0000000000, 9999999999),
        'email' => $faker->email,
        'website' => $faker->url,
        'company_id' => 1,
        'is_active' => 'Yes',
        'route_id' => $routeId,
        'location_id' => $locationId,
        'gps_lat' => $faker->randomFloat(null, 6.056814, 9.765307),
        'gps_long' => $faker->randomFloat(null, 80.906234, 79.906234),
        'type' => 'External'
    ];
});
