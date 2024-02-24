<?php


use App\Product;
use App\Store;
use Illuminate\Database\Seeder;

class EstimationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::all();
        $stores = Store::all();
        factory(\App\Estimate::class, 10)->create()->each(function (\App\Estimate $estimate) use ($products, $stores) {
            $quantity = random_int(10, 200);
            $rate = random_int(10, 999);
            $amount = $quantity * $rate;
            $discountRate = 10;
            $totalAmount = $amount - $discountRate;
            $estimate->sub_total = $amount;
            $estimate->total = $totalAmount;
            $estimate->save();
            $mappedProduct = [
                'estimate_id' => $estimate->id ?? null,
                'product_id' => $products->random()->id ?? null,
                'store_id' => $stores->random()->id ?? null,
                'quantity' => $quantity ?? null,
                'rate' => $rate ?? null,
                'discount_type' => 'Amount' ?? null,
                'discount_rate' => $discountRate ?? null,
                'discount' => $discountRate,
                'amount' => $totalAmount ?? null,
                'notes' => 'From Faker' ?? null,
            ];
            $estimate->products()->attach([$mappedProduct]);
        });
    }
}
