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

class NotifyOutOfStocks implements ShouldQueue
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
        $stocks = Stock::with('product')->where('available_stock', 0)->get();

        $storeIds = $stocks->pluck('store_id');
        $stores = Store::whereIn('id', $storeIds)->get();

        $emails = $stores->pluck('staff')->collapse()->pluck('email')->toArray();

        $data['stocks'] = $stocks;

        /** notify required allocation products to store staff if out of stocks available */
        if($stocks){
            Mail::send('emails.stock.notify-out-of-stocks', $data, function ($m) use ($emails) {
                $m->to($emails);
                $m->subject(env('APP_NAME').' - Products are now out of stock');
            });
        }
    }
}
