<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo, MorphMany, HasMany
};
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class Customer
 * @package App
 * @property int $id
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property string $code
 * @property string $salutation
 * @property string $first_name
 * @property string $last_name
 * @property string $full_name
 * @property string $display_name
 * @property string $phone
 * @property string $fax
 * @property string $mobile
 * @property string $email
 * @property string $website
 * @property string $type
 * @property string $gps_lat
 * @property string $gps_long
 * @property string $notes
 * @property string $is_active
 * @property string $tamil_name
 * @property int $route_id
 * @property mixed $orders
 * @property mixed $invoices
 * @property mixed $payments
 * @property mixed openingReferences
 */
class Customer extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'code', 'salutation', 'first_name', 'last_name', 'full_name', 'display_name', 'phone', 'fax', 'mobile', 'email',
        'website', 'type', 'gps_lat', 'gps_long', 'notes', 'is_active', 'customer_logo', 'route_id', 'location_id', 'company_id',
        'cl_amount', 'cl_notify_rate', 'tamil_name', 'category', 'cl_days'
    ];

    public $export = [
        'code', 'salutation', 'first_name', 'last_name', 'full_name', 'display_name', 'tamil_name', 'phone', 'fax', 'mobile', 'email',
        'website', 'type', 'gps_lat', 'gps_long', 'notes', 'cl_amount', 'cl_notify_rate'
    ];

    protected $appends = [
//        'total_orders',
//        'total_sales',
//        'total_paid',
//        'total_outstanding',
//        'total_allocated',
//        'total_visited',
//        'total_not_visited'
    ];

    /**
     * @var array
     */
    public $searchable = [
        'code', 'salutation', 'first_name', 'last_name', 'full_name', 'display_name', 'phone', 'fax', 'mobile', 'email',
        'website', 'type', 'gps_lat', 'gps_long', 'notes', 'is_active', 'tamil_name'
    ];
    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'code', 'salutation', 'first_name', 'last_name', 'full_name', 'display_name', 'phone', 'fax', 'mobile', 'email',
        'website', 'type', 'gps_lat', 'gps_long', 'notes', 'is_active', 'customer_logo', 'route_id', 'location_id', 'company_id',
        'cl_amount', 'cl_notify_rate', 'tamil_name', 'category', 'cl_days'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get customer's addresses.
     * @return MorphMany
     */
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Get the audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'customer';
    }

    /**
     * Get customer's contactPersons.
     * @return MorphMany
     */
    public function contactPersons(): MorphMany
    {
        return $this->morphMany(ContactPerson::class, 'contact_personable');
    }

    /**
     * @return belongsTo
     */
    public function company(): belongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return belongsTo
     */
    public function route(): belongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * @return belongsTo
     */
    public function location(): belongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * @return MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->with('user');
    }

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(SalesOrder::class);
    }

    /**
     * @return HasMany
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * @return HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(InvoicePayment::class);
    }

    /**
     * @return HasMany
     */
    public function estimates(): HasMany
    {
        return $this->hasMany(Estimate::class);
    }

    /**
     * @return HasMany
     */
    public function credits(): HasMany
    {
        return $this->hasMany(CustomerCredit::class);
    }

    /**
     * @return HasMany
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * @return MorphMany
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * @return HasMany
     */
    public function dailySalesCustomers()
    {
        return $this->hasMany(DailySaleCustomer::class, 'customer_id');
    }

    /**
     * @return HasMany
     */
    public function journals(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * @return HasMany
     */
    public function returns(): HasMany
    {
        return $this->hasMany(SalesReturn::class);
    }

    public function getTotalOrdersAttribute()
    {
        $totalOrders = $this->orders()->count();
        return $totalOrders;
    }

    public function getTotalSalesAttribute()
    {
        $totalSales = cusOutstanding($this)['ordered'];
        return $totalSales;
    }

    public function getTotalPaidAttribute()
    {
        $totalPaid = cusOutstanding($this)['paid'];
        return $totalPaid;
    }

    public function getTotalOutstandingAttribute()
    {
        $totalOutstanding = cusOutstanding($this)['balance'];
        return $totalOutstanding;
    }

    public function openingReferences()
    {
        return $this->hasMany(OpeningBalanceReference::class, 'customer_id');
    }

    public function account()
    {
        return $this->morphOne(Account::class, 'accountable');
    }

    public function getTotalAllocatedAttribute()
    {
        $totalAllocated = getCustomerTotalVisits($this)['allocated'];
        return $totalAllocated;
    }

    public function getTotalVisitedAttribute()
    {
        $totalVisited = getCustomerTotalVisits($this)['visited'];
        return $totalVisited;
    }

    public function getTotalNotVisitedAttribute()
    {
        $totalNotVisited = getCustomerTotalVisits($this)['not_visited'];
        return $totalNotVisited;
    }

}
