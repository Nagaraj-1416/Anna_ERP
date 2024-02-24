<?php

namespace App;

use Illuminate\Database\Eloquent\{
    Model, Relations\BelongsTo, SoftDeletes
};
use Jeylabs\AuditLog\Traits\LogsAudit;

class SalesExpense extends Model
{
    use SoftDeletes;
    use LogsAudit;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'code', 'expense_date', 'expense_time', 'type_id', 'calculate_mileage_using', 'notes', 'amount',
        'distance', 'start_reading', 'end_reading', 'status', 'prepared_by', 'approved_by', 'staff_id', 'company_id',
        'daily_sale_id', 'sales_handover_id', 'gps_long', 'gps_lat', 'odometer', 'liter'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'code', 'expense_date', 'expense_time', 'type_id', 'calculate_mileage_using', 'notes', 'amount',
        'distance', 'start_reading', 'end_reading', 'status', 'prepared_by', 'approved_by', 'staff_id', 'company_id',
        'daily_sale_id', 'sales_handover_id', 'gps_long', 'gps_lat', 'odometer', 'liter'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     *  the expense  that belong to the prepared by user.
     * @return BelongsTo
     */
    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    /**
     *  the expense that belong to the approved by user.
     * @return BelongsTo
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * the expense that belong to the approved by user.
     * @return BelongsTo
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * the expense that belong to the company.
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * the expense that belong to the company.
     * @return BelongsTo
     */
    public function dailySale()
    {
        return $this->belongsTo(DailySale::class, 'daily_sale_id');
    }

    /**
     * the expense that belong to the sale handover.
     * @return BelongsTo
     */
    public function salesHandover()
    {
        return $this->belongsTo(SalesHandover::class, 'sales_handover_id');
    }

    public function expense()
    {
        return $this->hasOne(Expense::class, 'sales_expense_id');
    }

    /**
     *  the expense that belong to the type.
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(ExpenseType::class, 'type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

}
