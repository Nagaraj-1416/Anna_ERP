<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class RepTarget
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
 * @property integer $rep_id
 * @property string $is_active
 */
class RepTarget extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'type', 'start_date', 'end_date', 'target', 'achieved', 'rep_id', 'is_active'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'type', 'start_date', 'end_date', 'target', 'achieved', 'rep_id', 'is_active'
    ];

    /**
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'rep-targets';
    }

    public function reps()
    {
        return $this->belongsTo(Rep::class, 'rep_id');
    }
}
