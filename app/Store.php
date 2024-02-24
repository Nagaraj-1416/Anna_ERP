<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{
    Model, SoftDeletes
};
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo, MorphMany, MorphToMany
};
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class Department
 * @package App
 * @property int $id
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property string $code
 * @property string $name
 * @property string $phone
 * @property string $fax
 * @property string $mobile
 * @property string $email
 * @property string $notes
 * @property int $company_id
 * @property string $is_active
 * @property mixed $company
 * @property mixed $staff
 */
class Store extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'phone', 'fax', 'mobile', 'email', 'notes', 'company_id', 'is_active', 'type'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'code', 'name', 'phone', 'fax', 'mobile', 'email', 'notes', 'company_id', 'is_active', 'type'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The stores that belong to the company
     * @return belongsTo
     */
    public function company(): belongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get all of the store's staff.
     * @return MorphToMany
     */
    public function staff(): MorphToMany
    {
        return $this->morphToMany(Staff::class, 'staffable', 'staffable');
    }

    /**
     * Get the audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'store';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    /**
     * @return MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->with('user');
    }

    public function dailyStocks()
    {
        return $this->hasMany(DailyStock::class);
    }
}