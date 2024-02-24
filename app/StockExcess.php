<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockExcess extends Model
{
    use SoftDeletes;
    use LogsAudit;

    protected $fillable = [
        'date', 'amount', 'status', 'notes', 'prepared_by', 'prepared_on', 'approved_by',
        'approved_on', 'route_id', 'rep_id', 'staff_id', 'daily_sale_id', 'sales_handover_id',
        'company_id'
    ];

    protected static $logAttributes = [
        'date', 'amount', 'status', 'notes', 'prepared_by', 'prepared_on', 'approved_by',
        'approved_on', 'route_id', 'rep_id', 'staff_id', 'daily_sale_id', 'sales_handover_id',
        'company_id'
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
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'route_id');
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
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

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
    public function handover(): BelongsTo
    {
        return $this->belongsTo(SalesHandover::class, 'sales_handover_id');
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
    public function items(): HasMany
    {
        return $this->hasMany(StockExcessItem::class);
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
        return 'stock-excess';
    }

    /**
     * @return MorphMany
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->with('user');
    }

}
