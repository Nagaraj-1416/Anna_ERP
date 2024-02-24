<?php

use App\Invoice;
use App\Product;
use App\SalesOrder;
use App\Store;
use Illuminate\Database\Seeder;

class SalesDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * @var Product $products
         * @var Store $stores
         */
        $products = Product::where('type', 'Finished Good')->get();
        $stores = Store::all();
        factory(SalesOrder::class, 100)->create()->each(function (SalesOrder $order) use ($products, $stores) {
            $quantity = random_int(10, 200);
            $rate = random_int(10, 999);
            $amount = $quantity * $rate;
            $discountRate = random_int(10, 100);
            $totalAmount = $amount - $discountRate;
            $order->sub_total = $amount;
            $order->total = $totalAmount;
            $order->save();
            $mappedProduct = [
                'sales_order_id' => $order->id ?? null,
                'product_id' => $products->random()->id ?? 1,
                'store_id' => $stores->random()->id ?? 1,
                'quantity' => $quantity ?? null,
                'rate' => $rate ?? null,
                'discount_type' => 'Amount',
                'discount_rate' => $discountRate,
                'discount' => $discountRate,
                'amount' => $totalAmount ?? null,
                'status' => 'Pending',
                'notes' => 'Faker',
            ];
            $order->products()->attach([$mappedProduct]);
            $this->createInvoice($order);
        });
    }

    public function createInvoice(SalesOrder $order)
    {
        $date = carbon()->subDays(random_int(1, 100));
        $invoice = new Invoice();
        $invoice->setAttribute('invoice_no', 'SI000001');
        $invoice->setAttribute('prepared_by', \App\User::all()->random()->id ?? 1);
        $invoice->setAttribute('sales_order_id', $order->getAttribute('id'));
        $invoice->setAttribute('customer_id', $order->getAttribute('customer_id'));
        $invoice->setAttribute('business_type_id', $order->getAttribute('business_type_id'));
        $invoice->setAttribute('company_id', $order->getAttribute('company_id'));
        $invoice->setAttribute('notes', 'Faker');
        $invoice->setAttribute('ref', 'JA/IS/OR/000005');
        $invoice->setAttribute('invoice_date', carbon());
        $invoice->setAttribute('due_date', carbon()->addDays(3));
        $invoice->setAttribute('amount', ($order->total / 2) ?? 1000);
        $invoice->save();
        $order->setAttribute('invoice_status', 'Invoiced');
        $order->save();
    }
}
