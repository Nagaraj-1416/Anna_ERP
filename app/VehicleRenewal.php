<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

class VehicleRenewal extends Model
{
    use SoftDeletes;
    use LogsAudit;
    /**
     * @var string
     */
    protected $table = 'vehicle_renewals';
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'vehicle_id', 'date', 'type'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'vehicle_id', 'date', 'type'
    ];
    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'vehicle-renewal';
    }

    /**
     * @return BelongsTo
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}
