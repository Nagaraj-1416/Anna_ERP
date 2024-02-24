<?php

namespace App\Jobs;

use App\DailySale;
use App\Stock;
use App\Store;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class NotifyLowStocks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {

    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {
        $stocks = Stock::with('product')->get();
        $stocks = $stocks->transform(function ($stock) {
            $min = $stock->min_stock_level ?? 0;
            if ($stock->available_stock == 0) return null;
            if ($stock->available_stock >= $min) {
                return null;
            }
            return $stock;
        })->filter();

        $storeIds = $stocks->pluck('store_id');
        $stores = Store::whereIn('id', $storeIds)->get();

        $emails = $stores->pluck('staff')->collapse()->pluck('email')->toArray();

        $data['stocks'] = $stocks;

        /** notify required allocation products to store staff if low stocks available */
        if($stocks){
            Mail::send('emails.stock.notify-low-stocks', $data, function ($m) use ($emails){
                $m->to($emails);
                $m->subject(env('APP_NAME').' - Products are reached to re-order level');
            });
        }
    }
}
