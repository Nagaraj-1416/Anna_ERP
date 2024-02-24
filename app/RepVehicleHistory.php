<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{
    Model, Relations\BelongsTo, SoftDeletes
};
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class Address
 * @package App
 * @property int $id
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property Carbon $assigned_date
 * @property Carbon $revoked_date
 * @property Carbon $blocked_date
 * @property string $status
 * @property int vehicle_id
 * @property int $rep_id
 * @property mixed $vehicle
 * @property mixed $rep
 */
class RepVehicleHistory extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'vehicle_id', 'rep_id', 'assigned_date', 'revoked_date', 'blocked_date', 'status'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'vehicle_id', 'rep_id', 'assigned_date', 'revoked_date', 'blocked_date', 'status'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

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
    public function rep(): BelongsTo
    {
        return $this->belongsTo(Rep::class, 'rep_id');
    }
    /**
     * Get the audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'repVehicleHistory';
    }
}
