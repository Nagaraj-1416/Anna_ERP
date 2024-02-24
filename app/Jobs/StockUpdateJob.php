<?php

namespace App\Jobs;

use App\DailySale;
use App\StockHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Class StockUpdateJob
 * @package App\Jobs
 */
class StockUpdateJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $type;
    protected $data;

    /**
     * StockUpdateJob constructor.
     * @param $type
     * @param $data
     */
    public function __construct($type, $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Loop for Stock Array
        foreach ($this->data as $key => $data) {

            /** Get Stock */
            $stock = array_get($data, 'stock');

            /** Get Quantity */
            $quantity = array_get($data, 'quantity');

            /** Get Transable Model*/
            $transable = array_get($data, 'transable');

            /** return when stock or quantity not available */
            if (!$stock || !$quantity) continue;

            /** Update Stock Available Stock */
            $oldQuantity = $stock->available_stock;
            if ($this->type == 'In') {
                $requestQuantity = ($oldQuantity + $quantity);
                //$transDesc = 'Stock restored';
                if(array_get($data, 'allocationRoute') && array_get($data, 'allocationDate')){
                    $transDesc = 'Stock restored from '.array_get($data, 'allocationRoute').' ('.array_get($data, 'allocationDate').')';
                }else{
                    $transDesc = 'Stock restored';
                }
                $transType = 'Restore';
            } else {
                $requestQuantity = ($oldQuantity - $quantity);
                if(array_get($data, 'allocationRoute') && array_get($data, 'allocationDate')){
                    $transDesc = 'Stock taken for '.array_get($data, 'allocationRoute').' ('.array_get($data, 'allocationDate').')';
                }else{
                    $transDesc = 'Stock taken';
                }
                $transType = 'Taken';
            }
            $stock->available_stock = $requestQuantity;
            $stock->save();

            /** Create New Stock History */
            $history = new StockHistory();
            $history->stock_id = $stock->id;
            $history->quantity = $quantity;
            $history->transaction = $this->type;
            $history->trans_date = carbon();
            $history->trans_description = $transDesc;
            $history->type = $transType;
            if ($transable) {
                $history->transable_id = $transable->id;
                $history->transable_type = 'App\\' . class_basename($transable);
            }
            $history->save();
        }
    }
}
