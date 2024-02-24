<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo, BelongsToMany, HasMany, MorphMany
};

/**
 * Class Estimate
 * @package App
 * @property string $estimate_no
 * @property mixed $company
 * @property mixed $customer
 * @property mixed $products
 */
class Estimate extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'estimate_no', 'estimate_date', 'expiry_date', 'terms', 'notes', 'sub_total', 'discount', 'discount_rate',
        'discount_type', 'adjustment', 'total', 'status', 'order_status', 'rep_id', 'prepared_by', 'customer_id',
        'business_type_id', 'company_id'
    ];
    /**
     * @var array
     */
    public $searchable = [
        'estimate_no', 'estimate_date', 'expiry_date', 'terms', 'notes', 'sub_total', 'discount', 'discount_rate',
        'discount_type', 'adjustment', 'total', 'status', 'order_status',
    ];
    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'estimate_no', 'estimate_date', 'expiry_date', 'terms', 'notes', 'sub_total', 'discount', 'discount_rate',
        'discount_type', 'adjustment', 'total', 'status', 'order_status', 'rep_id', 'prepared_by', 'customer_id',
        'business_type_id', 'company_id'
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
        return 'estimate';
    }

    /**
     * @return belongsTo
     */
    public function preparedBy(): belongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    /**
     * @return BelongsTo
     */
    public function rep(): belongsTo
    {
        return $this->belongsTo(Rep::class, 'rep_id');
    }

    /**
     * @return BelongsTo
     */
    public function customer(): belongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * @return BelongsTo
     */
    public function businessType(): belongsTo
    {
        return $this->belongsTo(BusinessType::class, 'business_type_id');
    }

    /**
     * @return BelongsTo
     */
    public function company(): belongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return BelongsToMany
     */
    public function products(): belongsToMany
    {
        return $this->belongsToMany(Product::class, 'estimate_product')->withPivot('price_book_id', 'store_id',
            'quantity', 'rate', 'discount_type', 'discount_rate', 'discount', 'amount', 'notes');
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

}
