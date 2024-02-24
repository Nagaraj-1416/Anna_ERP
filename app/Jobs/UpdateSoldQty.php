<?php

namespace App\Jobs;

use App\DailySaleItem;
use App\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Class UpdateSoldQty
 * @package App\Jobs
 */
class UpdateSoldQty implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $action;
    protected $order;

    /**
     * UpdateSoldQty constructor.
     * @param $action
     * @param $order
     */
    public function __construct($action, $order)
    {
        $this->action = $action;
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $action = $this->action;
        $order = $this->order;
        $orderProducts = $order->products;

        if($action == 'Save'){
            $orderProducts->each(function (Product $orderProduct) use ($order){
                $salesItem = DailySaleItem::where('daily_sale_id', $order->daily_sale_id)
                    ->where('product_id', $orderProduct->id)->first();
                if($salesItem){
                    $salesItem->sold_qty = ($salesItem->sold_qty + $orderProduct->pivot->quantity);
                    $salesItem->save();
                }
            });
        }elseif($action == 'Update'){
            $orderProducts->each(function (Product $orderProduct) use ($order){
                $salesItem = DailySaleItem::where('daily_sale_id', $order->daily_sale_id)
                    ->where('product_id', $orderProduct->id)->first();
                if($salesItem){
                    $salesItem->sold_qty = ($salesItem->sold_qty - $orderProduct->pivot->quantity);
                    $salesItem->save();
                }
            });
        }
    }
}
