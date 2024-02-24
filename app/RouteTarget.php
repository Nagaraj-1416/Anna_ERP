<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class RouteTarget
 * @package App
 * @property int $id
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property string $type
 * @property string $target
 * @property string $achieved
 * @property integer $route_id
 * @property string $is_active
 */
class RouteTarget extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'type', 'start_date', 'end_date', 'target', 'achieved', 'route_id', 'is_active'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'type', 'start_date', 'end_date', 'target', 'achieved', 'route_id', 'is_active'
    ];

    /**
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'route-targets';
    }
}
