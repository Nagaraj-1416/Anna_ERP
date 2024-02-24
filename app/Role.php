<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{
    Model, SoftDeletes
};
use Illuminate\Database\Eloquent\Relations\{
    HasMany
};
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class Role
 * @package App
 * @property int $id
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property string $name
 * @property array $permission
 * @property string $is_deletable
 * @property int $access_level
 * @property mixed $users
 */
class Role extends Model
{
    use LogsAudit;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['name', 'description', 'permission', 'is_deletable', 'access_level'];

    /**
     * attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'name', 'description', 'permission', 'is_deletable', 'access_level'
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
        'permission' => 'array',
    ];

    /**
     * Get all of the roles's users.
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'role';
    }
}
