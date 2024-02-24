<?php

use App\SupplierCredit;
use Faker\Generator as Faker;

$factory->define(SupplierCredit::class, function (Faker $faker) {
    $supplier = \App\Supplier::whereHas('bills')->get()->random();
    $bill = $supplier->bills->random();
    return [
        'code' => 'SCN0000001',
        'date' => carbon()->subDays(random_int(1, 60)),
        'amount' => $faker->numberBetween(100000, 900000),
        'notes' => 'FROM FAKER',
        'status' => 'Open',
        'prepared_by' => \App\User::all()->random()->id,
        'supplier_id' => $supplier->id,
        'business_type_id' => $bill->business_type_id,
        'company_id' => $bill->company_id,
        'bill_id' => $bill->id
    ];
});
