<?php

namespace App\Jobs;

use App\DailySale;
use App\Repositories\Sales\AllocationRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CancelHandoverJob implements ShouldQueue
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
        $handoverRepo = new AllocationRepository();
        $allocation->status = 'Canceled';
        $allocation->save();
        $handoverRepo->setModel($allocation);
        $handoverRepo->restoreProduct($allocation);
        $handoverRepo->stockUpdateJob('In');
    }
}
