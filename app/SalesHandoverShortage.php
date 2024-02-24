<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class SalesHandoverShortage
 * @package App
 */
class SalesHandoverShortage extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * @var array
     */
    protected $fillable = [
        'daily_sale_id', 'sales_handover_id', 'rep_id', 'date', 'amount', 'submitted_by',
        'approved_by', 'status', 'approved_at', 'rejected_by', 'rejected_at'
    ];
    /**
     * @var array
     */
    protected static $logAttributes = [
        'daily_sale_id', 'sales_handover_id', 'rep_id', 'date', 'amount', 'submitted_by'
    ];
    /**
     * @var array
     */
    protected $dates = ['deleted_at', 'date'];

    /**
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'sale-handover-shortage';
    }

    public function dailySale()
    {
        return $this->belongsTo(DailySale::class, 'daily_sale_id')->withTrashed();
    }

    public function rep()
    {
        return $this->belongsTo(Rep::class, 'rep_id');
    }

    public function handover()
    {
        return $this->belongsTo(SalesHandover::class, 'sales_handover_id');
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * @return MorphOne
     */
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

}
