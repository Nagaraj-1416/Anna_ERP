<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{
    Model, Relations\BelongsTo, SoftDeletes
};

use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class ExpenseReportReimburse
 * @package App
 * @property int $id
 * @property string $notes
 * @property string $reimbursed_on
 * @property float $amount
 * @property int $paid_through
 * @property int $expense_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property Carbon $expenseAccount
 * @property Carbon $paidThroughAccount
 */
class ExpenseReportReimburse extends Model
{
    use SoftDeletes;
    use LogsAudit;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'reimbursed_on', 'paid_through', 'report_id', 'notes', 'amount'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'reimbursed_on', 'paid_through', 'report_id', 'notes', 'amount'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     *  the item that belong to the paid through account.
     * @return BelongsTo
     */
    public function paidThroughAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'paid_through');
    }

    /**
     *  the item that belong to the paid through account.
     * @return BelongsTo
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(ExpenseReport::class, 'report_id');
    }

    /**
     * Get the audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'expense-report-reimburse';
    }
}
