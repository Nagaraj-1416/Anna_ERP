<?php

namespace App\Console\Commands;

use App\DailySale;
use App\Jobs\CancelHandoverJob;
use App\Jobs\CreateHandoverJob;
use Illuminate\Console\Command;

class AutoCreateHanoverCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:update:allocation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update daily sales';

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
        $now = now()->toDateString();
        $allocations = DailySale::where('status', 'Progress')->where('to_date', $now)->get();
        foreach ($allocations as $allocation) {
            if ($allocation->salesHandover) continue;
            if ($allocation->orders->count()) {
                dispatch(new CreateHandoverJob($allocation));
            } else {
                dispatch(new CancelHandoverJob($allocation));
            }
        }
    }
}
