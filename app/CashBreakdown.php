<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

class CashBreakdown extends Model
{
    use LogsAudit;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'date', 'rupee_type', 'count', 'sales_handover_id', 'prepared_by'
    ];

    protected $appends = ['amount'];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'date', 'rupee_type', 'count', 'sales_handover_id', 'prepared_by'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'cash-breakdown';
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
    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function getAmountAttribute()
    {
        $amount = $this->rupee_type * $this->count;
        return $amount;
    }

}
