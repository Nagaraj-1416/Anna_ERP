<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class SalesHandoverExcess
 * @package App
 */
class SalesHandoverExcess extends Model
{
    use LogsAudit;
    use SoftDeletes;

    protected $fillable = [
        'daily_sale_id', 'sales_handover_id', 'rep_id', 'date', 'amount', 'submitted_by'
    ];

    protected static $logAttributes = [
        'daily_sale_id', 'sales_handover_id', 'rep_id', 'date', 'amount', 'submitted_by'
    ];

    protected $dates = ['deleted_at', 'date'];

    public function dailySale()
    {
        return $this->belongsTo(DailySale::class, 'daily_sale_id');
    }

    public function handover()
    {
        return $this->belongsTo(SalesHandover::class, 'sales_handover_id');
    }

    public function rep()
    {
        return $this->belongsTo(Rep::class, 'rep_id');
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'sale-handover-excess';
    }

    /**
     * @return MorphOne
     */
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

}
