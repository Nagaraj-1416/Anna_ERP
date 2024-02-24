<?php

namespace App\Console\Commands;

use App\Repositories\Finance\TransactionRepository;
use App\Transaction;
use Illuminate\Console\Command;

class NotifyOutOfStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:out:of:stocks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify out of stock items to Store Keepers';

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
        /** send email notification to Store Keepers with the list items */
        dispatch(new \App\Jobs\NotifyOutOfStocks());
    }
}
