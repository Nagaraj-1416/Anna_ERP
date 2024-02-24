<?php

namespace App\Console\Commands;

use App\Bill;
use App\Customer;
use App\Department;
use App\Estimate;
use App\Invoice;
use App\Location;
use App\Product;
use App\PurchaseOrder;
use App\Rep;
use App\Role;
use App\Route;
use App\SalesOrder;
use App\Staff;
use App\Store;
use App\Supplier;
use App\SupplierCredit;
use App\User;
use App\Vehicle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class FakeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:fake {--count=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate fake data for development purpose. also you can pass the no of date {--count=2000}';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $count = (int)$this->option('count');
        if (!$count) {
            $count = 100;
        }
        if (config('app.env') != 'production') {
            $this->info('Generating please wait.');
            $this->generate($count);
            $this->info('Done!');
        } else {
            if ($this->confirm('Are you sure. This is a production env')) {
                $this->info('Generating please wait.');
                $this->generate($count);
                $this->info('Done!');
            }

        }
    }

    protected function generate($count)
    {
        $this->comment('Routes fake data generating..');
        factory(Route::class, 100)->create()->each(function ($route) {
            factory(Location::class, 10)->create([
                'route_id' => $route->id
            ]);
        });

        $this->info('Routes fake data generated.');

        $this->comment('Suppliers fake data generating..');
        factory(Supplier::class, $count)->create();
        $this->info('Suppliers fake data generated');

        $this->comment('customers fake data generating..');
        factory(Customer::class, $count)->create();
        $this->info('customers fake data generated');

        $this->comment('stores fake data generating..');
        factory(Product::class, 100)->create();
        $this->info('stores fake data generated');

        $this->comment('stores fake data generating..');
        factory(Store::class, 10)->create();
        $this->info('stores fake data generated');

        $this->comment('Departments fake data generating..');
        factory(Department::class, 10)->create();
        $this->info('Departments fake data generated');

        $this->comment('Purchase orders fake data generating..');
        factory(Bill::class, $count);
//        $this->generatePurchaseOrder($count);
        $this->info('Purchase orders fake data generated');

        $this->comment('Vehicle fake data generating..');
        factory(Vehicle::class, 40)->create();
        $this->info('Vehicle fake data generated');

        $this->comment('Staff fake data generating..');
        $this->generateStaff($count);
        $this->info('Staff fake data generated');

        $this->comment('Sales order fake data generating..');
        $this->generateSalesOrder($count);
        $this->info('Sales order fake data generated');


        $this->comment('Estimate fake data generating..');
        $this->generateEstimations($count);
        $this->info('Estimate fake data generated');


        $this->comment('Supplier Credit fake data generating..');
        factory(SupplierCredit::class, $count)->create();
        $this->info('Supplier Credit fake data generated');
    }


    public function generateEstimations($count)
    {
        $products = Product::all();
        $stores = Store::all();
        factory(Estimate::class, $count)->create()->each(function (Estimate $estimate) use ($products, $stores) {
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

    public function generateStaff($count)
    {
        factory(Staff::class, 100)->create()->each(function (Staff $staff) {
            //For Rep Creation
            $salesRep = new Rep();
            $salesRep->setAttribute('code', $staff->getAttribute('code'));
            $salesRep->setAttribute('name', $staff->getAttribute('short_name'));
            $salesRep->setAttribute('notes', 'from faker');
            $salesRep->setAttribute('is_active', 'Yes');
            $salesRep->setAttribute('vehicle_id', Vehicle::all()->random()->id ?? 1);
            $salesRep->setAttribute('staff_id', $staff->id ?? 1);
            $salesRep->save();
            $salesRep->routes()->attach(Route::all()->random()->id ?? 1);
            //For User Creation
            $user = new User();
            ['name', 'email', 'password', 'is_active', 'role_id'];
            $user->setAttribute('name', $staff->getAttribute('short_name'));
            $user->setAttribute('email', $staff->getAttribute('email'));
            $user->setAttribute('password', Hash::make(123456));
            $user->setAttribute('is_active', 'Yes');
            $user->setAttribute('role_id', Role::all()->random()->id ?? 1);
            $user->save();
            $staff->user()->associate($user);
            $staff->save();
        });
    }

    /**
     * @param $count
     */
    public function generatePurchaseOrder($count)
    {
        $products = Product::where('type', 'Raw Material')->get();
        $stores = Store::all();

        factory(PurchaseOrder::class, $count)->create()->each(function (PurchaseOrder $order) use ($products, $stores) {
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
        $bill->setAttribute('amount', ($order->amount / 2));
        $bill->save();
        $order->setAttribute('bill_status', 'Partially Billed');
        $order->save();
    }

    /**
     * @param $count
     */
    protected function generateSalesOrder($count)
    {
        $products = Product::where('type', 'Finished Good')->get();
        $stores = Store::all();
        factory(SalesOrder::class, $count)->create()->each(function (SalesOrder $order) use ($products, $stores) {
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

    /**
     * @param SalesOrder $order
     */
    protected function createInvoice(SalesOrder $order)
    {
        $invoice = new Invoice();
        $invoice->setAttribute('invoice_no', 'SI000001');
        $invoice->setAttribute('prepared_by', \App\User::all()->random()->id ?? 1);
        $invoice->setAttribute('sales_order_id', $order->getAttribute('id'));
        $invoice->setAttribute('customer_id', $order->getAttribute('customer_id'));
        $invoice->setAttribute('business_type_id', $order->getAttribute('business_type_id'));
        $invoice->setAttribute('company_id', $order->getAttribute('company_id'));
        $invoice->setAttribute('notes', 'Faker');
        $invoice->setAttribute('invoice_date', carbon());
        $invoice->setAttribute('due_date', carbon()->addDays(random_int(1, 20)));
        $invoice->setAttribute('amount', ($order->total / 2) ?? 1000);
        $invoice->save();
        $order->setAttribute('invoice_status', 'Invoiced');
        $order->save();
    }
}
