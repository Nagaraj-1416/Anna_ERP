<?php

use App\Staff;
use Faker\Generator as Faker;

$factory->define(Staff::class, function (Faker $faker) {
    $salutation = ['Mr.' => 'Mr.', 'Mrs.' => 'Mrs.', 'Ms.' => 'Ms.', 'Miss.' => 'Miss.', 'Dr.' => 'Dr.'];
    $gender = ['Male', 'Female'];
    $salutationName = $salutation[array_rand($salutation)];
    $genderName = $gender[array_rand($gender)];
    $firstName = $faker->firstName;
    $lastName = $faker->lastName;
    return [
        'code' => 'S0000001',
        'salutation' => $salutationName,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'full_name' => $salutationName . ' ' . $firstName . ' ' . $lastName,
        'short_name' => $faker->name,
        'gender' => $genderName,
        'dob' => carbon()->subYears(20),
        'email' => $faker->safeEmail,
        'phone' => $faker->numberBetween(0000000000, 9999999999),
        'mobile' => $faker->numberBetween(0000000000, 9999999999),
        'joined_date' => carbon()->subYears(1),
        'designation' => 'Sales',
        'bank_name' => 'Commercial Bank',
        'branch' => 'Jaffna',
        'account_name' => $salutationName . ' ' . $firstName . ' ' . $lastName,
        'account_no' => $faker->numberBetween(80180000000, 80180999999),
        'epf_no' => $faker->numberBetween(00000, 80180),
        'etf_no' => $faker->numberBetween(00000, 80180),
        'pay_rate' => 'Monthly',
        'notes' => 'From Faker',
        'is_active' => 'Yes',
        'is_sales_rep' => 'Yes'
    ];
});
