<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class Allowance
 * @package App
 * @property int $id
 * @property string assigned_date
 * @property float amount
 * @property string notes
 * @property string is_active
 * @property int assigned_by
 * @property string allowanceable_type
 * @property int allowanceable_id
 * @property int company_id
 */
class Allowance extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'assigned_date', 'amount', 'notes', 'is_active', 'assigned_by', 'allowanceable_type', 'allowanceable_id', 'company_id'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'assigned_date', 'amount', 'notes', 'is_active', 'assigned_by', 'allowanceable_type', 'allowanceable_id', 'company_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return MorphTo
     */
    public function allowanceable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
