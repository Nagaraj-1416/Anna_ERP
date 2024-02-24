<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\{
    belongsToMany, HasMany, MorphMany, belongsTo
};

use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class Route
 * @package App
 * @property int $id
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property string $code
 * @property string $name
 * @property string $notes
 * @property string $is_active
 * @property array $start_point
 * @property array $end_point
 * @property array $way_points
 * @property mixed $customers
 * @property mixed $products
 */
class Route extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'notes', 'is_active', 'start_point', 'end_point', 'way_points', 'company_id', 'cl_amount',
        'cl_notify_rate', 'nxt_day_al_route'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'code', 'name', 'notes', 'is_active', 'start_point', 'end_point', 'way_points', 'company_id', 'cl_amount',
        'cl_notify_rate', 'nxt_day_al_route'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'start_point' => 'array',
        'end_point' => 'array',
        'way_points' => 'array',
    ];

    /**
     * Get the log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'sales-routes';
    }

    /**
     * @return HasMany
     */
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class, 'route_id');
    }

    /**
     * @return belongsToMany
     */
    public function reps(): belongsToMany
    {
        return $this->belongsToMany(Rep::class, 'route_rep');
    }

    /**
     * @return HasMany
     */
    public function targets(): HasMany
    {
        return $this->hasMany(RouteTarget::class);
    }

    /**
     * @return HasMany
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * @return MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->with('user');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function allowance()
    {
        return $this->morphOne(Allowance::class, 'allowanceable');
    }

    /**
     * @return belongsToMany
     */
    public function products(): belongsToMany
    {
        return $this->belongsToMany(Product::class, 'route_product')->withPivot('default_qty');
    }

    /**
     * @return belongsTo
     */
    public function company(): belongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return HasMany
     */
    public function allocations(): HasMany
    {
        return $this->hasMany(DailySale::class);
    }

}
