<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{
    Model, SoftDeletes
};
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo, BelongsToMany, MorphMany, MorphToMany
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
 * @property string $is_selling_price
 * @property string $type
 * @property mixed $company
 * @property mixed $staff
 * @property int $sales_location_id
 * @property mixed orders
 */
class SalesLocation extends Model
{
    use LogsAudit;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'phone', 'fax', 'mobile', 'email', 'notes', 'company_id', 'is_active', 'vehicle_id',
        'is_selling_price', 'type'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'code', 'name', 'phone', 'fax', 'mobile', 'email', 'notes', 'company_id', 'is_active', 'vehicle_id',
        'is_selling_price', 'type'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The sales locations that belong to the company
     * @return belongsTo
     */
    public function company(): belongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * The sales locations that belong to the vehicle
     * @return belongsTo
     */
    public function vehicle(): belongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    /**
     * Get all of the sales location's staff.
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
        return 'sales-location';
    }

    /**
     * @return MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->with('user');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(SalesOrder::class, 'sales_location_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'sales_location_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoicePayments()
    {
        return $this->hasMany(InvoicePayment::class, 'sales_location_id');
    }

    /**
     * @return belongsToMany
     */
    public function products(): belongsToMany
    {
        return $this->belongsToMany(Product::class, 'sales_location_product')->withPivot('default_qty');
    }

    public function dailySales()
    {
        return $this->hasMany(DailySale::class, 'sales_location_id');
    }
}
