<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{
    Model, SoftDeletes
};
use Illuminate\Database\Eloquent\Relations\{
    belongsTo, BelongsToMany, HasMany, MorphMany
};
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class VehicleModel
 * @package App
 * @property int $id
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property string $vehicle_no
 * @property string $engine_no
 * @property string $chassis_no
 * @property string $reg_date
 * @property string $year
 * @property string $color
 * @property string $fuel_type
 * @property string $notes
 * @property string $is_active
 * @property mixed $type
 * @property int $type_id
 * @property mixed $make
 * @property int $make_id
 * @property mixed $model
 * @property int $model_id
 * @property mixed $company
 * @property int $company_id
 */
class Vehicle extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'engine_no', 'chassis_no', 'reg_date', 'year', 'color', 'fuel_type', 'type_id', 'make_id',
        'model_id', 'notes', 'is_active', 'company_id', 'vehicle_no', 'category', 'type_of_body',
        'seating_capacity', 'weight', 'gross', 'tyre_size_front', 'tyre_size_rear', 'length',
        'width', 'height', 'wheel_front', 'wheel_rear'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'vehicle_no', 'engine_no', 'chassis_no', 'reg_date', 'year', 'color', 'fuel_type', 'type_id', 'make_id',
        'model_id', 'notes', 'is_active', 'company_id', 'category', 'type_of_body',
        'seating_capacity', 'weight', 'gross', 'tyre_size_front', 'tyre_size_rear', 'length',
        'width', 'height', 'wheel_front', 'wheel_rear'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The vehicle that belong to the type.
     * @return belongsTo
     */
    public function type(): belongsTo
    {
        return $this->belongsTo(VehicleType::class, 'type_id');
    }

    /**
     * The vehicle that belong to the make.
     * @return belongsTo
     */
    public function make(): belongsTo
    {
        return $this->belongsTo(VehicleMake::class, 'make_id');
    }

    /**
     * The vehicle that belong to the model.
     * @return belongsTo
     */
    public function model(): belongsTo
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }

    /**
     * The vehicle that belong to the company.
     * @return belongsTo
     */
    public function company(): belongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'vehicle';
    }

    /**
     * @return BelongsToMany
     */
    public function reps(): BelongsToMany
    {
        return $this->belongsToMany(Rep::class, 'vehicle_rep')->withPivot('assigned_date', 'revoked_date', 'blocked_date', 'status');
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
    public function salesLocations()
    {
        return $this->hasMany(SalesLocation::class, 'vehicle_id');
    }

    /**
     * @return HasMany
     */
    public function renewals()
    {
        return $this->hasMany(VehicleRenewal::class, 'vehicle_id');
    }

    /**
     * @return HasMany
     */
    public function odoMeterReadings()
    {
        return $this->hasMany(DailySalesOdoReading::class, 'vehicle_id');
    }
}
