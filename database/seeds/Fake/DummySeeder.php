<?php

use App\Bill;
use App\BillPayment;
use App\PurchaseOrder;
use Illuminate\Database\Seeder;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = \App\Product::where('type', 'Raw Material')->get();
        $stores = \App\Store::all();

        factory(PurchaseOrder::class, 20)->create()->each(function (PurchaseOrder $order) use ($products, $stores) {
            $quantity = random_int(10, 200);
            $rate = random_int(10, 999);
            $amount = $quantity * $rate;
            $discountRate = 10;
            $totalAmount = $amount - $discountRate;
            $order->sub_total = $amount;
            $order->total = $totalAmount;
            $order->save();
            $mappedProduct = [
                'purchase_order_id' => $order->id ?? null,
                'product_id' => $products->random()->id ?? null,
                'store_id' => 1,
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
            $this->createBill($order);
        });
    }

    public function createBill(PurchaseOrder $order)
    {
        $bill = new Bill();
        $bill->setAttribute('bill_no', 'PB0000001');
        $bill->setAttribute('prepared_by', \App\User::find(1)->id ?? 1);
        $bill->setAttribute('purchase_order_id', $order->getAttribute('id'));
        $bill->setAttribute('supplier_id', $order->getAttribute('supplier_id'));
        $bill->setAttribute('business_type_id', $order->getAttribute('business_type_id'));
        $bill->setAttribute('company_id', $order->getAttribute('company_id'));
        $bill->setAttribute('notes', 'fake');
        $bill->setAttribute('bill_date', carbon());
        $bill->setAttribute('due_date', carbon()->addDays(7));
        $bill->setAttribute('amount', ($order->sub_total / 2));
        $bill->save();
        $order->setAttribute('bill_status', 'Partially Billed');
        $order->save();
    }
}
