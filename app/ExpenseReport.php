<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{
    Model, Relations\BelongsTo, Relations\HasMany, Relations\MorphMany, SoftDeletes
};
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class ExpenseReport
 * @package App
 * @property int $id
 * @property string $report_no
 * @property string $title
 * @property string $report_from
 * @property string $report_to
 * @property string $notes
 * @property float $amount
 * @property string $status
 * @property int $prepared_by
 * @property int $approved_by
 * @property int $company_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property mixed $preparedBy
 * @property mixed $approvedBy
 * @property mixed $company
 * @property mixed $comments
 */
class ExpenseReport extends Model
{
    use SoftDeletes;
    use LogsAudit;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'report_no', 'title', 'report_from', 'report_to', 'notes', 'amount', 'status', 'prepared_by', 'approved_by',
        'company_id', 'business_type_id', 'submitted_by', 'submitted_on'
    ];
    /**
     * @var array
     */
    public $searchable = [
        'report_no', 'title', 'report_from', 'report_to', 'notes', 'amount', 'status', 'submitted_on'
    ];
    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'report_no', 'title', 'report_from', 'report_to', 'notes', 'amount', 'status', 'prepared_by', 'approved_by',
        'company_id', 'business_type_id', 'submitted_by', 'submitted_on'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     *  the expense report that belong to the prepared by user.
     * @return BelongsTo
     */
    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    /**
     *  the expense report that belong to the approved by user.
     * @return BelongsTo
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     *  the expense report that belong to the submitted by user.
     * @return BelongsTo
     */
    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     *  the expense report that belong to the company.
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     *  the expense report that belong to the business type.
     * @return BelongsTo
     */
    public function businessType(): BelongsTo
    {
        return $this->belongsTo(BusinessType::class, 'business_type_id');
    }

    /**
     * the expense report that belong to many expenses.
     * @return HasMany
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'report_id');
    }

    /**
     * the expense report that belong to many expenses.
     * @return HasMany
     */
    public function reimburses()
    {
        return $this->hasMany(ExpenseReportReimburse::class, 'report_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->with('user');
    }

    /**
     * @return MorphMany
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Get the audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'expense-report';
    }
}
