<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTransfer extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'date', 'transfer_by', 'vehicle_id', 'transfer_from', 'transfer_to', 'company_id', 'status', 'notes'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'date', 'transfer_by', 'vehicle_id', 'transfer_from', 'transfer_to', 'company_id', 'status', 'notes'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return BelongsTo
     */
    public function transferBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'transfer_by');
    }

    /**
     * @return BelongsTo
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    /**
     * @return BelongsTo
     */
    public function transferFrom(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'transfer_from');
    }

    /**
     * @return BelongsTo
     */
    public function transferTo(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'transfer_to');
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

}
