<?php

namespace App\Jobs;

use App\Invoice;
use App\SalesOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateDailySalesId implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $salesOrders = SalesOrder::whereNotNull('daily_sale_id')->get();
        foreach ($salesOrders as $salesOrder){
            $salesOrder->invoices()->whereNull('daily_sale_id')->update([
                'daily_sale_id' => $salesOrder->daily_sale_id
            ]);
            $salesOrder->payments()->whereNull('daily_sale_id')->update([
                'daily_sale_id' => $salesOrder->daily_sale_id
            ]);
        }
    }
}
