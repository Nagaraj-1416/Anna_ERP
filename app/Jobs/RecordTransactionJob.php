<?php

namespace App\Jobs;

use App\{Transaction, Account, TransactionRecord};
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\{
    SerializesModels, InteractsWithQueue
};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RecordTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transactionable;
    /** @var Account  $debitAccount*/
    protected $debitAccount;
    /** @var Account  $debitAccount*/
    protected $creditAccount;
    protected $data;
    protected $isEdit;
    protected $action;
    protected $transaction;

    /**
     * Create a new job instance.
     * @param Model $transactionable
     * @param Account $debitAccount
     * @param Account $creditAccount
     * @param array $data
     * @param string $action
     * @param bool $isEdit
     */
    public function __construct(
        Model $transactionable,
        Account $debitAccount,
        Account $creditAccount,
        $data = [],
        $action = null,
        $isEdit = false
    )
    {
        $this->transactionable = $transactionable;
        $this->debitAccount = $debitAccount;
        $this->creditAccount = $creditAccount;
        $this->data = $data;
        $this->action = $action;
        $this->isEdit = $isEdit;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var Transaction $transaction */
        $this->createTransaction();
        $this->createTransactionRecords();
    }

    /**
     * Create transactions
     */
    public function createTransaction()
    {
        if ($this->isEdit){
            $this->transaction =  $this->transactionable->transactions()->where('action', $this->action)->first();
        }
        if (!$this->transaction){
            /** @var Transaction transaction */
            $this->transaction = new Transaction();
            $this->transaction->setAttribute('date', null);
        }
        $this->transaction->setAttribute('date', array_get($this->data, 'date'));
        $this->transaction->setAttribute('category', 'Auto');
        $this->transaction->setAttribute('type', array_get($this->data, 'type'));
        $this->transaction->setAttribute('amount', array_get($this->data, 'amount'));
        $this->transaction->setAttribute('auto_narration', array_get($this->data, 'auto_narration'));
        $this->transaction->setAttribute('manual_narration', array_get($this->data, 'manual_narration'));
        $this->transaction->setAttribute('tx_type_id', array_get($this->data, 'tx_type_id'));
        $this->transaction->setAttribute('transactionable_id', $this->transactionable->id);
        $this->transaction->setAttribute('transactionable_type', 'App\\' . class_basename($this->transactionable));
        $this->transaction->setAttribute('supplier_id', array_get($this->data, 'supplier_id'));
        $this->transaction->setAttribute('customer_id', array_get($this->data, 'customer_id'));
        $this->transaction->setAttribute('prepared_by', array_get($this->data, 'prepared_by'));
        $this->transaction->setAttribute('business_type_id', array_get($this->data, 'business_type_id'));
        $this->transaction->setAttribute('business_type_id', array_get($this->data, 'business_type_id'));
        $this->transaction->setAttribute('company_id', array_get($this->data, 'company_id'));
        if (!$this->isEdit){
            $this->transaction->setAttribute('action', $this->action);
        }
        $this->transaction->save();
    }

    /**
     * Create transactions records
     */
    public function createTransactionRecords()
    {
        /**
         * create debit account records
         */
        $deposit = null;
        if ($this->isEdit){
            $deposit = $this->transaction->records->where('type', 'Debit')->first();
        }
        if (!$deposit){
            $deposit = new TransactionRecord();
        }
        $deposit->setAttribute('date', $this->transaction->date);
        $deposit->setAttribute('amount', $this->transaction->amount);
        $deposit->setAttribute('type', 'Debit');
        $deposit->setAttribute('account_id', $this->debitAccount->id);
        $deposit->setAttribute('transaction_id', $this->transaction->id);
        $deposit->save();
        if (!$this->debitAccount->first_tx_date){
            $this->debitAccount->first_tx_date = now()->toDateString();
        }
        $this->debitAccount->latest_tx_date = now()->toDateString();
        $this->debitAccount->save();

        /**
         * create credit transaction records
         */
        $credit = null;
        if ($this->isEdit){
            $credit = $this->transaction->records->where('type', 'Credit')->first();
        }
        if (!$credit){
            $credit = new TransactionRecord();
        }
        $credit->setAttribute('date', $this->transaction->date);
        $credit->setAttribute('amount', $this->transaction->amount);
        $credit->setAttribute('type', 'Credit');
        $credit->setAttribute('account_id', $this->creditAccount->id);
        $credit->setAttribute('transaction_id', $this->transaction->id);
        $credit->save();
        if (!$this->creditAccount->first_tx_date){
            $this->creditAccount->first_tx_date = now()->toDateString();
        }
        $this->creditAccount->latest_tx_date = now()->toDateString();
        $this->creditAccount->save();
    }

    /**
     * Create transaction record for customer account
     */
    public function createCusTranRecord()
    {

    }

}
