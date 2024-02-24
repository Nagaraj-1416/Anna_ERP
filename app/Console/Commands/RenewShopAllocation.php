<?php

namespace App\Console\Commands;

use App\DailySale;
use App\SalesLocation;
use Illuminate\Console\Command;

class RenewShopAllocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'renew:shops:allocation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update shops allocation "to_date" on daily basis.';

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
        $shops = SalesLocation::where('type', 'Shop')->get();
        foreach ($shops as $shop){
            $allocation = DailySale::where('sales_location', 'Shop')
                ->where('sales_location_id', $shop->id)
                ->where('company_id', $shop->company_id)
                ->first();
            if($allocation){
                if($allocation->to_date <= carbon()->now()->toDateString()){
                    $allocation->to_date = carbon()->now()->addDay(1)->toDateString();
                    $allocation->save();
                }
            }
        }
    }
}
