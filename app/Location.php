<?php

namespace App;

use Illuminate\Database\Eloquent\{
    Model, SoftDeletes
};
use Jeylabs\AuditLog\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Location
 * @package App
 * @property string $notes
 * @property string $name
 * @property string $code
 * @property string $is_active
 * @property integer $route_id
 * @property mixed $route
 */
class Location extends Model
{
    use SoftDeletes;
    use LogsAudit;
    /**
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'notes', 'is_active', 'route_id'
    ];

    /**
     * @var array
     */
    protected $logAttributes = [
        'code', 'name', 'notes', 'is_active', 'route_id'
    ];

    /**
     * @return HasMany
     */
    public function customers() :HasMany
    {
        return $this->hasMany(Customer::class);
    }

}
