<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{
    Model, Relations\BelongsTo, Relations\BelongsToMany, Relations\MorphMany, Relations\MorphTo, SoftDeletes
};
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class SalesInquiry
 * @package App
 * @property int $id
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property string $code
 * @property string $inquiry_date
 * @property string $source_file
 * @property integer $customer_id
 * @property integer $prepared_by
 * @property integer $business_type_id
 * @property integer $converted_id
 * @property integer $company_id
 * @property string $description
 * @property string $status
 * @property string $converted_type
 * @property mixed $products
 * @property mixed $customer
 * @property mixed $businessType
 * @property mixed $company
 */
class SalesInquiry extends Model
{
    use SoftDeletes;
    use LogsAudit;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'code', 'inquiry_date', 'customer_id', 'prepared_by', 'description', 'business_type_id',
        'status', 'converted_type', 'converted_id', 'company_id'
    ];
    /**
     * @var array
     */
    public $searchable = [
        'code', 'inquiry_date', 'description', 'status', 'converted_type',
    ];
    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected $logAttributes = [
        'code', 'inquiry_date', 'source_file', 'customer_id', 'prepared_by', 'description', 'business_type_id',
        'status', 'converted_type', 'converted_id', 'company_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

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
     * @return BelongsToMany
     */
    public function products(): belongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot(
            'sales_inquiry_id', 'product_id', 'quantity', 'delivery_date', 'notes'
        );
    }

    /**
     * @return BelongsTo
     */
    public function company()
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
     * @return MorphTo
     */
    public function converted(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'sales-inquiry';
    }
}
