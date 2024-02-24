<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\{
    BelongsTo, BelongsToMany, HasMany, MorphMany
};

use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class Rep
 * @package App
 */
class Rep extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'notes', 'is_active', 'staff_id', 'vehicle_id', 'cl_amount', 'cl_notify_rate', 'company_id'
    ];
    /**
     * @var array
     */
    protected static $logAttributes = [
        'code', 'name', 'notes', 'is_active', 'staff_id', 'vehicle_id', 'cl_amount', 'cl_notify_rate', 'company_id'
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
        return 'sales-reps';
    }

    /**
     * Get rep Staff
     * @return BelongsTo
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * @return BelongsTo
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    /**
     * @return belongsToMany
     */
    public function routes(): belongsToMany
    {
        return $this->belongsToMany(Route::class, 'route_rep');
    }

    /**
     * @return HasMany
     */
    public function targets(): HasMany
    {
        return $this->hasMany(RepTarget::class, 'rep_id');
    }

    public function vehicleHistory()
    {
        return $this->hasMany(RepVehicleHistory::class, 'rep_id');
    }

    /**
     * @return MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->with('user');
    }

    /**
     * @return HasMany
     */
    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'rep_id');
    }

    /**
     * @return HasMany
     */
    public function dailySales()
    {
        return $this->hasMany(DailySale::class, 'rep_id');
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
