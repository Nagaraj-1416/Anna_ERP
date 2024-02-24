<?php

use Faker\Generator as Faker;

$factory->define(\App\Supplier::class, function (Faker $faker) {
    $codeNumber = getCodeForModal(new \App\Supplier(), 'SUP');
    $firstName = $faker->firstName;
    $lastName = $faker->lastName;
    $salutation = ['Mr.' => 'Mr.', 'Mrs.' => 'Mrs.', 'Ms.' => 'Ms.', 'Miss.' => 'Miss.', 'Dr.' => 'Dr.'];
    $salutationName = $salutation[array_rand($salutation)];
    return [
        'code' => $codeNumber,
        'is_active' => 'Yes',
        'first_name' => $firstName,
        'full_name' => $salutationName . ' ' . $firstName . ' ' . $lastName,
        'last_name' => $lastName,
        'display_name' => $faker->name,
        'phone' => $faker->numberBetween(0000000000, 9999999999),
        'fax' => $faker->numberBetween(0000000000, 9999999999),
        'mobile' => $faker->numberBetween(0000000000, 9999999999),
        'email' => $faker->email,
        'website' => $faker->url,
        'company_id' => 1,
        'salutation' => $salutationName
    ];
});
