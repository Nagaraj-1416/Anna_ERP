<?php

use Faker\Generator as Faker;

$factory->define(\App\SalesOrder::class, function (Faker $faker) {
    $status = ['Scheduled', 'Draft', 'Awaiting Approval', 'Open', 'Closed', 'Canceled'];
    $statusVal = $status[array_rand($status)];
    return [
        'order_no' => 'SO000001',
        'order_date' => carbon(),
        'delivery_date' => carbon()->addDays($faker->numberBetween(3, 10)),
        'order_type' => 'Direct',
        'terms' => 'fake',
        'notes' => 'fake',
        'prepared_by' => \App\User::all()->random()->id ?? 1,
        'customer_id' => \App\Customer::all()->random()->id ?? 1,
        'business_type_id' => \App\BusinessType::all()->random()->id ?? 1,
        'company_id' => \App\Company::all()->random()->id ?? 1,
        'status' => $statusVal,
        'rep_id' => \App\Rep::all()->random()->id ?? 1,
        'ref' => 'JA/IS/OR/000005'
    ];
});
