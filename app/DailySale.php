<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class DailySale
 * @package App
 * @property string $code
 * @property string $day_type
 * @property carbon $from_date
 * @property carbon $to_date
 * @property carbon $start_time
 * @property carbon $end_time
 * @property string $days
 * @property string $sales_location
 * @property int $sales_location_id
 * @property int $vehicle_id
 * @property int $rep_id
 * @property int $route_id
 * @property string $notes
 * @property string $status
 * @property int $prepared_by
 * @property int $company_id
 * @property double $allowance
 * @property mixed $route
 * @property mixed $items
 * @property mixed $customers
 */
class DailySale extends Model
{
    use LogsAudit;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'code', 'day_type', 'from_date', 'to_date', 'days', 'sales_location', 'sales_location_id', 'vehicle_id', 'rep_id',
        'route_id', 'notes', 'status', 'prepared_by', 'company_id', 'driver_id', 'labour_id', 'store_id'
    ];

    protected $appends = ['visited_customers', 'not_visited_customers', 'sales_starts_at', 'sales_ends_at', 'sales_time'];

    /**
     * @var array
     */
    public $searchable = [
        'code', 'day_type', 'from_date', 'to_date', 'days', 'sales_location', 'notes', 'status',
    ];
    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'code', 'day_type', 'from_date', 'to_date', 'days', 'sales_location', 'sales_location_id', 'vehicle_id', 'rep_id',
        'route_id', 'notes', 'status', 'prepared_by', 'company_id', 'driver_id', 'labour_id', 'store_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return BelongsTo
     */
    public function salesLocation(): BelongsTo
    {
        return $this->belongsTo(SalesLocation::class, 'sales_location_id');
    }

    /**
     * @return BelongsTo
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
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
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    /**
     * @return BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'driver_id');
    }

    /**
     * @return BelongsTo
     */
    public function labour(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'labour_id');
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
        return $this->hasMany(DailySaleItem::class);
    }

    /**
     * @return HasMany
     */
    public function customers(): HasMany
    {
        return $this->hasMany(DailySaleCustomer::class, 'daily_sale_id');
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
    public function stockHistories()
    {
        return $this->morphMany(StockHistory::class, 'transable');
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
        return 'allocation';
    }

    /**
     * @return HasOne
     */
    public function salesHandover(): HasOne
    {
        return $this->hasOne(SalesHandover::class);
    }

    /**
     * @return HasMany
     */
    public function salesExpenses(): HasMany
    {
        return $this->hasMany(SalesExpense::class, 'daily_sale_id');
    }

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(SalesOrder::class, 'daily_sale_id');
    }

    /**
     * @return HasMany
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'daily_sale_id');
    }

    /**
     * @return HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(InvoicePayment::class, 'daily_sale_id');
    }

    /**
     * @return HasOne
     */
    public function odoMeterReading()
    {
        return $this->hasOne(DailySalesOdoReading::class, 'daily_sale_id');
    }

    /**
     * @return HasMany
     */
    public function dailySaleCreditOrders()
    {
        return $this->hasMany(DailySaleCreditOrder::class, 'daily_sale_id');
    }

    public function getVisitedCustomersAttribute()
    {
        $visitedCustomers = $this->customers()->where('is_visited', 'Yes')->count();
        return $visitedCustomers;
    }

    public function getNotVisitedCustomersAttribute()
    {
        $totalCustomers = $this->customers()->count();
        $visitedCustomers = $this->customers()->where('is_visited', 'Yes')->count();
        $notVisitedCustomers = ($totalCustomers - $visitedCustomers);
        return $notVisitedCustomers;
    }

    /**
     * @return HasMany
     */
    public function returns(): HasMany
    {
        return $this->hasMany(SalesReturn::class, 'daily_sale_id');
    }

    public function nextDayRoute()
    {
        return $this->belongsTo(Route::class, 'nxt_day_al_route');
    }

    /**
     * @return hasOne
     */
    public function dailyStock(): HasOne
    {
        return $this->hasOne(DailyStock::class, 'pre_allocation_id');
    }

    public function getSalesStartsAtAttribute()
    {
        return date("F j, Y, g:i:s a", strtotime($this->logged_in_at));
    }

    public function getSalesEndsAtAttribute()
    {
        return $this->logged_out_at ? date("F j, Y, g:i:s a", strtotime($this->logged_out_at)) : 'None';
    }

    public function getSalesTimeAttribute()
    {
        $salesTime = getDifferentTime($this->logged_in_at, $this->logged_out_at);
        return $salesTime;
    }

}
