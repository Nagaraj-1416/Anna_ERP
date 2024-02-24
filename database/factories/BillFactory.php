<?php

use App\PurchaseOrder;
use Faker\Generator as Faker;

$factory->define(\App\Bill::class, function (Faker $faker) {
    $order = factory(PurchaseOrder::class, 1)->create()->each(function (PurchaseOrder $order) {
        $quantity = random_int(10, 200);
        $rate = random_int(10, 999);
        $amount = $quantity * $rate;
        $discountRate = 10;
        $totalAmount = $amount - $discountRate;
        $order->sub_total = $amount;
        $order->total = $totalAmount;
        $order->save();
        $mappedProduct = [
            'purchase_order_id' => $order->id,
            'product_id' => factory(\App\Product::class)->create()->id,
            'store_id' => factory(\App\Store::class)->create()->id,
            'quantity' => $quantity,
            'rate' => $rate,
            'discount_type' => 'Amount',
            'discount_rate' => $discountRate,
            'discount' => $discountRate,
            'amount' => $totalAmount,
            'status' => 'Pending',
            'notes' => 'Fake',
        ];
        $order->products()->attach([$mappedProduct]);
    });
    $order = $order->first();
    return [
        'bill_no' => 'PB0000001',
        'prepared_by' => \App\User::find(1)->id,
        'purchase_order_id' => $order->id,
        'supplier_id' => $order->supplier_id,
        'business_type_id' => $order->business_type_id,
        'company_id' => 1,
        'notes' => 'THis is from faker',
        'bill_date' => carbon(),
        'due_date' => carbon()->addDays(7),
        'amount' => ($order->total / 2),
    ];
});
