<?php

namespace App\Jobs;

use App\DailySale;
use App\Store;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class NotifyAllocationToStore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $allocation;

    public function __construct(DailySale $allocation)
    {
        $this->allocation = $allocation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $allocation = $this->allocation;

        $products = $allocation->items;
        $storeIds = $allocation->items()->groupBy('store_id')->pluck('store_id');
        $stores = Store::where('id', $storeIds)->with('staff')->get();

        $emails = $stores->pluck('staff')->collapse()->pluck('email')->toArray();

        $data['allocation'] = $allocation;
        $data['products'] = $products;

        /** notify required allocation products to store staff */
        Mail::send('emails.allocation.notify-allocation-to-store', $data, function ($m) use ($allocation, $emails) {
            $m->to($emails);
            $m->subject(env('APP_NAME').' - Products are required to allocate for '.$allocation->salesLocation->name);
        });
    }

}
