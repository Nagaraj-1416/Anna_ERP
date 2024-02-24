<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\{
    Eloquent\Model, Eloquent\SoftDeletes
};
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class ExpenseCategory
 * @package App
 * @property int $id
 * @property string $name
 * @property string $notes
 * @property string $is_default
 * @property string $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
class ExpenseCategory extends Model
{
    use SoftDeletes;
    use LogsAudit;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'notes', 'is_default', 'is_active'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'name', 'notes', 'is_default', 'is_active'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'expense-category';
    }
}
