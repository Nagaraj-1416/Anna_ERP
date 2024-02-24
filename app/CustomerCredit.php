<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class CustomerCredit
 * @package App
 * @property int $id
 * @property string $code
 * @property carbon $date
 * @property string $amount
 * @property string $notes
 * @property string $status
 * @property int $prepared_by
 * @property int $customer_id
 * @property int $business_type_id
 * @property int $company_id
 * @property int $invoice_id
 * @property carbon $created_at
 * @property carbon $updated_at
 * @property carbon $deleted_at
 * @property mixed $refunds
 * @property mixed $company
 * @property mixed $customer
 * @property mixed $payments
 */
class CustomerCredit extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * @var array
     */
    protected $fillable = [
        'code', 'date', 'amount', 'notes', 'status', 'prepared_by', 'customer_id', 'business_type_id', 'company_id', 'invoice_id'
    ];

    /**
     * @var array
     */
    public $searchable = [
        'code', 'date', 'amount', 'notes', 'status'
    ];
    /**
     * @var array
     */
    protected static $logAttributes = [
        'code', 'date', 'amount', 'notes', 'status', 'prepared_by', 'customer_id', 'business_type_id', 'company_id', 'invoice_id'
    ];
    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return BelongsTo
     */
    public function businessType()
    {
        return $this->belongsTo(BusinessType::class);
    }

    /**
     * @return BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
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
    public function preparedBy()
    {
        return $this->belongsTo(User::class, 'prepared_by');
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
    public function refunds()
    {
        return $this->hasMany(CustomerCreditRefund::class, 'credit_id');
    }

    /**
     * @return HasMany
     */
    public function payments()
    {
        return $this->hasMany(InvoicePayment::class, 'credit_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

}
