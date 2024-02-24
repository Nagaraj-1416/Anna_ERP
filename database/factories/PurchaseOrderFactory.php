<?php

use Faker\Generator as Faker;

$factory->define(\App\PurchaseOrder::class, function (Faker $faker) {
    return array(
        'po_no' => 'PO000001',
        'order_date' => carbon(),
        'delivery_date' => carbon()->addDays(5),
        //'po_type' => 'Direct',
        //'terms' => 'fake orders',
        'notes' => 'fake orders',
        'prepared_by' => \App\User::find(1)->id,
        'supplier_id' => function () {
            return factory(\App\Supplier::class)->create()->id;
        },
        //'business_type_id' => function () {
        //    return factory(\App\BusinessType::class)->create()->id;
        //},
        'company_id' => \App\Company::all()->random()->id ?? 1,
        //'total' => 0
    );
});
