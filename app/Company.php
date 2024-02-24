<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{
    Model, SoftDeletes
};
use Illuminate\Database\Eloquent\Relations\{
    HasMany, MorphMany, MorphToMany
};
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class Company
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
 * @property string $website
 * @property int $business_location
 * @property string $base_currency
 * @property string $fy_starts_month
 * @property string $fy_starts_from
 * @property string $timezone
 * @property string $date_time_format
 * @property string $business_starts_at
 * @property string $business_end_at
 * @property string $is_active
 * @property string $company_logo
 * @property mixed $country
 * @property mixed $departments
 * @property mixed $sales_locations
 * @property mixed $stores
 * @property mixed $staff
 * @property mixed $machines
 * @property mixed $addresses
 */
class Company extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'phone', 'fax', 'mobile', 'email', 'website', 'business_location', 'base_currency',
        'fy_starts_month', 'fy_starts_from', 'timezone', 'date_time_format', 'business_starts_at', 'business_end_at',
        'is_active', 'company_logo', 'display_name'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'code', 'name', 'phone', 'fax', 'mobile', 'email', 'website', 'business_location', 'base_currency',
        'fiscal_year_start', 'fiscal_year_end', 'timezone', 'date_time_format', 'business_starts_at', 'business_end_at',
        'is_active', 'company_logo', 'display_name'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The company that belong to the country.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'business_location');
    }

    /**
     * Get all of the company's departments.
     * @return HasMany
     */
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function routes(): HasMany
    {
        return $this->hasMany(Route::class);
    }

    public function reps(): HasMany
    {
        return $this->hasMany(Rep::class);
    }

    /**
     * Get all of the company's sales locations.
     * @return HasMany
     */
    public function salesLocations(): HasMany
    {
        return $this->hasMany(SalesLocation::class);
    }

    /**
     * Get all of the company's stores.
     * @return HasMany
     */
    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    /**
     * Get all of the company's staff.
     * @return MorphToMany
     */
    public function staff(): MorphToMany
    {
        return $this->morphToMany(Staff::class, 'staffable', 'staffable');
    }

    /**
     * Get all of the company's machines.
     * @return HasMany
     */
    public function machines(): HasMany
    {
        return $this->hasMany(Machine::class);
    }

    /**
     * Get company's addresses.
     * @return MorphMany
     */
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Get the log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'company';
    }

    /**
     * @return MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->with('user');
    }

    public function journals(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

}
