<?php

use Faker\Generator as Faker;
['Draft','Sent','Accepted','Declined','Ordered'];
$factory->define(\App\Estimate::class, function (Faker $faker) {
    return [
        'estimate_no' => 'SE000001',
        'estimate_date' => carbon(),
        'expiry_date' => carbon()->addDays($faker->numberBetween(2, 9)),
        'terms' => 'Fake',
        'notes' => 'Fake',
        'prepared_by' => \App\User::find(1)->id ?? 1,
        'rep_id' => \App\Rep::all()->random()->id ?? 1,
        'customer_id' => \App\Customer::all()->random()->id ?? 1,
        'business_type_id' => \App\BusinessType::all()->random()->id ?? 1,
        'company_id' => 1,
    ];
});
