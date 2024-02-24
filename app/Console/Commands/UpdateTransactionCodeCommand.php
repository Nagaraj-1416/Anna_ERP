<?php

namespace App\Console\Commands;

use App\Repositories\Finance\TransactionRepository;
use App\Transaction;
use Illuminate\Console\Command;

class UpdateTransactionCodeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:update:code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update transaction code';

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
        $transactions = Transaction::where('code', '')->orWhere('code', null)->orderBy('id')->get();
        foreach ($transactions as $transaction){
            /** @var Transaction $transaction */
            $transaction->setAttribute('code', $this->getCode($transaction));
            $transaction->save();
            $transaction->refresh();
        }
    }

    public function getCode(Transaction $transaction): string
    {
        $transactionRep = new TransactionRepository();
        $transactionRep->getModel();
        $prefix = $transactionRep->getCodePrefix();
        // Get the last created order
        $lastItem = Transaction::where('id', '<',  $transaction->id)->orderBy('id', 'desc')->first();
        // get last record cord no
        if (!$lastItem)
            $number = 0;
        else
            $number = preg_replace('/\D/', '', $lastItem->getAttribute($transactionRep->codeColumn));

        return $prefix . sprintf('%07d', intval($number) + 1);
    }
}
