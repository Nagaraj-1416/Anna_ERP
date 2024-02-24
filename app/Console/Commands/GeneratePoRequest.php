<?php

namespace App\Console\Commands;

use App\Repositories\Purchase\RequestRepository;
use App\Stock;
use App\Store;
use Illuminate\Console\Command;

class GeneratePoRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:po:request';
    protected $request;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check stocks re-order levels and generate PO request on daily basis.';

    /**
     * GeneratePoRequest constructor.
     * @param RequestRepository $request
     */
    public function __construct(RequestRepository $request)
    {
        $this->request = $request;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** generate stores PO request */
        $stores = Store::where('type', 'General')->get();
        foreach ($stores as $store){
            $stocks = Stock::where('store_id', $store->id)
                ->where('company_id', $store->company_id)->get();
            if($stocks->isNotEmpty()){
                $stocks = $stocks->reject(function ($stock) {
                    return $stock->available_stock > $stock->min_stock_level;
                });
                $stocks->map(function ($stock){
                    $stock->require_qty = ($stock->min_stock_level - $stock->available_stock);
                    return $stock;
                });
                /** create PO request */
                $this->request->generatePoRequest($stocks, $store);
            }
        }
    }

}
