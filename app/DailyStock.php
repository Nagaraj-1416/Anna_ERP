<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class DailyStock
 * @package App
 * @property integer $pre_allocation_id
 * @property integer $sales_location_id
 * @property integer $rep_id
 * @property integer $store_id
 * @property integer $prepared_by
 * @property integer $company_id
 * @property string $sales_location
 * @property string $notes
 * @property string $status
 * @property string $route_id
 */
class DailyStock extends Model
{
    use LogsAudit;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'pre_allocation_id', 'sales_location', 'sales_location_id', 'rep_id', 'store_id', 'prepared_by', 'notes', 'status', 'company_id', 'route_id'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'pre_allocation_id', 'sales_location', 'sales_location_id', 'rep_id', 'store_id', 'prepared_by', 'notes', 'status', 'company_id', 'route_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return BelongsTo
     */
    public function preAllocation(): BelongsTo
    {
        return $this->belongsTo(DailySale::class, 'pre_allocation_id');
    }

    /**
     * @return BelongsTo
     */
    public function saleLocation(): BelongsTo
    {
        return $this->belongsTo(SalesLocation::class, 'sales_location_id');
    }

    /**
     * @return BelongsTo
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    /**
     * @return BelongsTo
     */
    public function rep(): BelongsTo
    {
        return $this->belongsTo(Rep::class, 'rep_id');
    }

    /**
     * @return BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    /**
     * @return BelongsTo
     */
    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(DailyStockItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->with('user');
    }

    /**
     * @return MorphMany
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Get audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'daily-stock-allocation';
    }

}
