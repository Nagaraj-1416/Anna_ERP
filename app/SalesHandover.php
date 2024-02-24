<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class SalesHandover
 * @package App
 */
class SalesHandover extends Model
{
    use LogsAudit;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'code', 'date', 'daily_sale_id', 'sales', 'cash_sales', 'cheque_sales', 'deposit_sales', 'card_sales', 'credit_sales',
        'old_sales', 'old_cash_sales', 'old_cheque_sales', 'old_deposit_sales', 'old_card_sales', 'old_credit_sales', 'cheques_count',
        'total_collect', 'total_expense', 'allowance', 'sales_commission', 'shortage', 'rep_id', 'notes', 'status', 'prepared_by', 'company_id',
        'rc_collection', 'rc_cash', 'rc_cheque', 'rc_deposit', 'rc_card', 'rc_credit'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'code', 'date', 'daily_sale_id', 'sales', 'cash_sales', 'cheque_sales', 'deposit_sales', 'card_sales', 'credit_sales',
        'old_sales', 'old_cash_sales', 'old_cheque_sales', 'old_deposit_sales', 'old_card_sales', 'old_credit_sales', 'cheques_count',
        'total_collect', 'total_expense', 'allowance', 'sales_commission', 'shortage', 'rep_id', 'notes', 'status', 'prepared_by', 'company_id',
        'rc_collection', 'rc_cash', 'rc_cheque', 'rc_deposit', 'rc_card', 'rc_credit'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return BelongsTo
     */
    public function dailySale(): BelongsTo
    {
        return $this->belongsTo(DailySale::class, 'daily_sale_id');
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
    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return HasMany
     */
    public function breakdowns(): HasMany
    {
        return $this->hasMany(CashBreakdown::class);
    }

    /**
     * @return HasMany
     */
    public function chequeInHands(): HasMany
    {
        return $this->hasMany(ChequeInHand::class, 'sales_handover_id');
    }

    /**
     * @return HasMany
     */
    public function salesExpenses(): HasMany
    {
        return $this->hasMany(SalesExpense::class, 'sales_handover_id')->with('type');
    }

    /**
     * @return HasMany
     */
    public function notVisitedCustomers()
    {
        return $this->hasMany(NotVisitCustomer::class, 'sales_handover_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function stockHistories()
    {
        return $this->morphMany(StockHistory::class, 'transable');
    }

    /**
     * Get audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'sales-handover';
    }

}
