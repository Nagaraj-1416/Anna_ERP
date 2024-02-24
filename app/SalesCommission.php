<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

use Illuminate\Database\Eloquent\{
    Relations\BelongsTo
};

class SalesCommission extends Model
{
    use SoftDeletes;
    use LogsAudit;

    protected $fillable = [
        'date', 'year', 'month', 'credit_sales', 'cheque_received', 'cheque_collection_dr', 'sales_returned', 'cheque_returned', 'sales_target', 'special_target',
        'total_sales', 'cash_collection', 'cheque_collection_cr', 'cheque_realized', 'customer_visited_count', 'customer_visited_rate', 'customer_visited',
        'product_sold_count', 'product_sold_rate', 'product_sold', 'debit_balance', 'credit_balance', 'status', 'notes', 'prepared_by', 'prepared_on',
        'approved_by', 'approved_on', 'staff_id', 'rep_id', 'company_id', 'debit_account', 'credit_account', 'special_commission'
    ];

    protected static $logAttributes = [
        'date', 'year', 'month', 'credit_sales', 'cheque_received', 'cheque_collection_dr', 'sales_returned', 'cheque_returned', 'sales_target', 'special_target',
        'total_sales', 'cash_collection', 'cheque_collection_cr', 'cheque_realized', 'customer_visited_count', 'customer_visited_rate', 'customer_visited',
        'product_sold_count', 'product_sold_rate', 'product_sold', 'debit_balance', 'credit_balance', 'status', 'notes', 'prepared_by', 'prepared_on',
        'approved_by', 'approved_on', 'staff_id', 'rep_id', 'company_id', 'debit_account', 'credit_account', 'special_commission'
    ];

    protected $dates = ['deleted_at'];

    /**
     * @return BelongsTo
     */
    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    /**
     * @return BelongsTo
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * @return BelongsTo
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * @return BelongsTo
     */
    public function rep(): BelongsTo
    {
        return $this->belongsTo(Rep::class, 'rep_id');
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return BelongsTo
     */
    public function debitAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'debit_account');
    }

    /**
     * @return BelongsTo
     */
    public function creditAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'credit_account');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    /**
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'sales-commission';
    }

}
