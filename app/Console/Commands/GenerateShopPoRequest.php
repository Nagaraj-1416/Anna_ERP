<?php

namespace App\Console\Commands;

use App\DailySale;
use App\PurchaseOrder;
use App\Repositories\Purchase\OrderRepository;
use App\SalesLocation;
use App\Stock;
use App\Store;
use Illuminate\Console\Command;

class GenerateShopPoRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:shop:po:request';
    protected $order;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check shops re-order levels and generate PO request on daily basis.';

    /**
     * GeneratePoRequest constructor.
     * @param OrderRepository $order
     */
    public function __construct(OrderRepository $order)
    {
        $this->order = $order;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** generate shops PO request */
        $shops = SalesLocation::where('type', 'Shop')->get();
        foreach ($shops as $shop){
            $allocation = DailySale::where('status', 'Progress')
                ->where('sales_location', 'Shop')
                ->where('sales_location_id', $shop->id)->first();
            if($allocation){
                $items = $allocation->items;
                if($items->isNotEmpty()){
                    $items = $items->reject(function ($item) use ($shop){
                        $defaultQty = getShopItemDefaultQty($shop, $item);
                        $availableQty = ($item->quantity + $item->cf_qty + $item->returned_qty + $item->excess_qty) - ($item->sold_qty + $item->restored_qty + $item->replaced_qty + $item->shortage_qty);
                        return $availableQty > $defaultQty;
                    });

                    $items->map(function ($item) use ($shop){
                        $defaultQty = getShopItemDefaultQty($shop, $item);
                        $availableQty = ($item->quantity + $item->cf_qty + $item->returned_qty + $item->excess_qty) - ($item->sold_qty + $item->restored_qty + $item->replaced_qty + $item->shortage_qty);
                        $item->require_qty = ($defaultQty - $availableQty);
                        return $item;
                    });

                    /** create PO request */
                    $this->order->generateShopPoRequest($allocation, $items, $shop);
                }
            }
        }
    }

}
