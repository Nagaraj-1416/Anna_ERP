<?php

use App\PurchaseOrder;
use Faker\Generator as Faker;

$factory->define(\App\BusinessType::class, function (Faker $faker) {
    return [
        'code' => 'BT000001',
        'name' => $faker->name,
        'notes' => 'FROM FAKER'
    ];
});
