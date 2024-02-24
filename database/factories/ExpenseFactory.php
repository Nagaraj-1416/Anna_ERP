<?php

use Faker\Generator as Faker;

$factory->define(\App\Expense::class, function (Faker $faker) {
    return [
        'expense_no' => 'EX0000001',
        'expense_date' => $faker->dateTimeBetween('2016-01-01', '2019-01-01'),
        'claim_reimburse' => 'Yes',
        'expense_type' => 'General',
        'expense_items' => 'Single',
        'notes' => 'sss',
        'amount' => $faker->numberBetween(10000, 90000),
        'status' => 'Unreported',
        'calculate_mileage_using' => 'Distance',
        'category_id' => \App\ExpenseCategory::all()->random()->id,
        'expense_account' => \App\Account::where('account_type_id', 21)->first()->id ?? 1,
        'paid_through' => \App\Account::first()->id ?? 1,
        'prepared_by' => 1,
        'supplier_id' => \App\Supplier::all()->random()->id,
        'customer_id' => \App\Customer::all()->random()->id,
        'business_type_id' => \App\BusinessType::all()->random()->id,
        'company_id' => 1,
    ];
});
