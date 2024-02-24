<?php

namespace App\Jobs;

use App\DailySale;
use App\DailyStock;
use App\DailyStockItem;
use App\Product;
use App\Route;
use App\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NextDayAllocationCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $routeId = array_get($this->data, 'route');
        $storeId = array_get($this->data, 'store');
        $allocation = array_get($this->data, 'allocation');
        $handover = array_get($this->data, 'handover');
        $user = array_get($this->data, 'user');
        $products = $allocation->items;
        $route = Route::find($routeId);
        $store = Store::find($storeId);
        $routeProducts = $route->products;

        $cfProducts = [];
        foreach ($products as $product) {
            $quantity = $this->calculateBalanceQty($product);
            //$quantity = $product->actual_stock;
            if ($quantity > 0) {
                $cfProducts[$product->product_id] = $quantity;
            }
        }
        $cfProductsIds = array_keys($cfProducts);
        $routeProductsIds = $routeProducts->pluck('id')->toArray();
        $differentData = array_diff($cfProductsIds, $routeProductsIds);

        /**
         * @var DailySale $allocation
         */
        $stock = new DailyStock();
        $stock->pre_allocation_id = $allocation->id;
        $stock->rep_id = $allocation->rep_id;
        $stock->route_id = $route->id;
        $stock->store_id = $store->id;
        $stock->sales_location_id = $allocation->sales_location_id;
        $stock->sales_location = $allocation->sales_location;
        $stock->prepared_by = $user->id;
        $stock->company_id = $route->company_id;
        $stock->save();

        foreach ($routeProducts as $product) {
            $stockItem = new DailyStockItem();
            $stockItem->daily_stock_id = $stock->id;
            $stockItem->product_id = $product->id;
            $stockItem->store_id = $store->id;
            $stockItem->default_qty = $product->pivot->default_qty;;
            $stockItem->available_qty = array_get($cfProducts, $product->id, 0);
            if (($stockItem->default_qty - $stockItem->available_qty) > 0) {
                $stockItem->required_qty = $stockItem->default_qty - $stockItem->available_qty;
            } else {
                $stockItem->required_qty = 0;
            }

            $stockItem->save();
        }


        foreach ($differentData as $key => $product) {
            $product = Product::find($product);
            if (!$product) continue;
            $stockItem = new DailyStockItem();
            $stockItem->daily_stock_id = $stock->id;
            $stockItem->product_id = $product->id;
            $stockItem->store_id = $store->id;
            $stockItem->default_qty = 0;
            $stockItem->available_qty = array_get($cfProducts, $product->id, 0);
            $stockItem->required_qty = 0;
            $stockItem->save();
        }
    }

    public function calculateBalanceQty($product)
    {
        //$totalQty = ($product->quantity + $product->cf_qty + $product->returned_qty + $product->excess_qty);
        //$forDeduct = ($product->sold_qty + $product->restored_qty + $product->replaced_qty + $product->shortage_qty + $product->damaged_qty);
        //$forDeduct = ($product->sold_qty + $product->restored_qty + $product->replaced_qty + $product->shortage_qty);
        //return ($totalQty - $forDeduct);
        //return $product->actual_stock;
        if($product->restored_qty > 0){
            $balanceQty = ($product->actual_stock - $product->restored_qty);
        }else{
            $balanceQty = $product->actual_stock;
        }
        return $balanceQty;
    }
}
