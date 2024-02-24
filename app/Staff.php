<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{
    Model, SoftDeletes
};
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo, HasOne, HasMany, MorphMany, MorphTo, MorphToMany
};
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class Staff
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
 * @property string $short_name
 * @property string $gender
 * @property string $dob
 * @property string $email
 * @property string $phone
 * @property string $mobile
 * @property string $joined_date
 * @property string $designation
 * @property string $resigned_date
 * @property string $bank_name
 * @property string $branch
 * @property string $account_name
 * @property string $account_no
 * @property string $epf_no
 * @property string $etf_no
 * @property string $pay_rate
 * @property string $notes
 * @property string $is_active
 * @property int $user_id
 * @property mixed $user
 * @property mixed $staffable
 * @property mixed $addresses
 * @property mixed $companies
 * @property mixed $departments
 * @property mixed $sales_locations
 * @property mixed $stores
 */
class Staff extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'code', 'salutation', 'first_name', 'last_name', 'full_name', 'short_name', 'gender', 'dob', 'email', 'phone', 'mobile',
        'joined_date', 'designation', 'resigned_date', 'bank_name', 'branch', 'account_name', 'account_no', 'epf_no',
        'etf_no', 'pay_rate', 'notes', 'is_active', 'user_id', 'profile_image', 'is_sales_rep', 'designation_id'
    ];

    public $export = [
        'code', 'salutation', 'first_name', 'last_name', 'full_name', 'short_name', 'gender', 'dob', 'email', 'phone', 'mobile',
        'joined_date', 'designation', 'resigned_date', 'bank_name', 'branch', 'account_name', 'account_no', 'epf_no',
        'etf_no', 'pay_rate', 'notes',
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'code', 'salutation', 'first_name', 'last_name', 'full_name', 'short_name', 'gender', 'dob', 'email', 'phone', 'mobile',
        'joined_date', 'designation', 'resigned_date', 'bank_name', 'branch', 'account_name', 'account_no', 'epf_no',
        'etf_no', 'pay_rate', 'notes', 'is_active', 'user_id', 'profile_image', 'is_sales_rep', 'designation_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The staff that belong to the user.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all of the owning staffable models.
     * @return MorphTo
     */
    public function staffable()
    {
        return $this->morphTo();
    }

    /**
     * Get all of the staff's addresses.
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
        return 'staff';
    }

    /**
     * Get All Departments
     */
    public function departments()
    {
        return $this->morphedByMany(Department::class, 'staffable', 'staffable');
    }

    /**
     * Get All companies
     */
    public function companies()
    {
        return $this->morphedByMany(Company::class, 'staffable', 'staffable');
    }

    /**
     * Get all units
     */
    public function units()
    {
        return $this->morphedByMany(ProductionUnit::class, 'staffable', 'staffable');
    }

    /**
     * Get All sales locations
     */
    public function salesLocations()
    {
        return $this->morphedByMany(SalesLocation::class, 'staffable', 'staffable');
    }

    /**
     * Get All stores
     */
    public function stores()
    {
        return $this->morphedByMany(Store::class, 'staffable', 'staffable');
    }

    /**
     * @return MorphToMany
     */
    public function businessTypes(): MorphToMany
    {
        return $this->morphToMany(BusinessType::class, 'businesstypeable', 'businesstypeable');
    }

    /**
     * @return HasOne
     */
    public function rep(): HasOne
    {
        return $this->hasOne(Rep::class);
    }

    /**
     * @return MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->with('user');
    }

    /**
     * @return BelongsTo
     */
    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    /**
     * @return MorphMany
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
