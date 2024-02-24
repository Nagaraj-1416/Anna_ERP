<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

class ShopHandover extends Model
{
    use LogsAudit;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'date', 'daily_sale_id', 'shop_id', 'sales', 'cash_sales', 'cheque_sales', 'deposit_sales', 'card_sales', 'credit_sales',
        'cheques_count', 'total_collect', 'total_expense', 'allowance', 'sales_commission', 'shortage', 'excess',
        'staff_id', 'notes', 'status', 'prepared_by', 'company_id'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'date', 'daily_sale_id', 'shop_id', 'sales', 'cash_sales', 'cheque_sales', 'deposit_sales', 'card_sales', 'credit_sales',
        'cheques_count', 'total_collect', 'total_expense', 'allowance', 'sales_commission', 'shortage', 'excess',
        'staff_id', 'notes', 'status', 'prepared_by', 'company_id'
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
    public function shop(): BelongsTo
    {
        return $this->belongsTo(SalesLocation::class, 'shop_id');
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
     * Get audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'shop-handover';
    }

}
