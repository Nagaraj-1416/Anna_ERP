<?php

use App\Vehicle;
use Faker\Generator as Faker;

$factory->define(Vehicle::class, function (Faker $faker) {
    return [
        'vehicle_no' => $faker->text(5) . $faker->numberBetween('0000', 9999),
        'engine_no' => $faker->text(10),
        'chassis_no' => $faker->text(10),
        'reg_date' => carbon()->subDays(30),
        'year' => carbon()->subYears($faker->numberBetween(1, 5))->year,
        'color' => $faker->safeColorName,
        'fuel_type' => 'Petrol',
        'type_id' => \App\VehicleType::all()->random()->id ?? 1,
        'make_id' => \App\VehicleMake::all()->random()->id ?? 1,
        'model_id' => \App\VehicleModel::all()->random()->id ?? 1,
        'notes' => 'Fake',
        'is_active' => 'Yes',
        'company_id' => 1
    ];
});
