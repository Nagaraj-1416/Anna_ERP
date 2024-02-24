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
 * Class Supplier
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
 * @property string $notes
 * @property string $is_active
 * @property mixed $credits
 * @property mixed $payments
 * @property mixed $bills
 * @property mixed $orders
 * @property mixed $company
 * @property mixed $addresses
 */
class Supplier extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'code', 'salutation', 'first_name', 'last_name', 'full_name', 'display_name', 'phone', 'fax', 'mobile', 'email',
        'website', 'type', 'notes', 'is_active', 'supplier_logo', 'company_id'
    ];
    /**
     * @var array
     */
    public $searchable = [
        'code', 'salutation', 'first_name', 'last_name', 'full_name', 'display_name', 'phone', 'fax', 'mobile', 'email',
        'website', 'type', 'notes', 'is_active',
    ];
    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'code', 'salutation', 'first_name', 'last_name', 'full_name', 'display_name', 'phone', 'fax', 'mobile', 'email',
        'website', 'type', 'notes', 'is_active', 'supplier_logo', 'company_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get supplier's addresses.
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
        return 'supplier';
    }

    /**
     * Get supplier's contactPersons.
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
     * @return MorphMany
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
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * @return HasMany
     */
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    /**
     * @return HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(BillPayment::class);
    }

    /**
     * @return HasMany
     */
    public function credits(): HasMany
    {
        return $this->hasMany(SupplierCredit::class);
    }

    /**
     * @return HasMany
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * @return HasMany
     */
    public function journals(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function openingReferences()
    {
        return $this->hasMany(OpeningBalanceReference::class, 'supplier_id');
    }
}
