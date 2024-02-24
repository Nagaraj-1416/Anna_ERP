<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

use Illuminate\Database\Eloquent\Relations\{
    BelongsTo, MorphMany, HasMany
};

/**
 * Class Invoice
 * @package App
 * @property mixed $customer
 * @property mixed $company
 * @property mixed $payments
 * @property string $amount
 * @property string sales_location_id
 * @property string company_id
 * @property mixed transactions
 */
class Invoice extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'invoice_no', 'invoice_date', 'due_date', 'invoice_type', 'amount', 'prepared_by', 'approval_status',
        'approved_by', 'status', 'notes', 'sales_order_id', 'customer_id', 'business_type_id', 'company_id', 'ref',
        'sales_location_id', 'daily_sale_id', 'route_id', 'is_opening'
    ];

    protected $appends = ['payment_received', 'payment_remaining'];

    /**
     * @var array
     */
    public $searchable = [
        'invoice_no', 'invoice_date', 'due_date', 'invoice_type', 'amount', 'approval_status', 'status',
        'notes', 'ref',
    ];
    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'invoice_no', 'invoice_date', 'due_date', 'invoice_type', 'amount', 'prepared_by', 'approval_status',
        'approved_by', 'status', 'notes', 'sales_order_id', 'customer_id', 'business_type_id', 'company_id', 'ref',
        'sales_location_id', 'daily_sale_id'
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
        return 'invoice';
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
    public function approvedBy(): belongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * @return BelongsTo
     */
    public function order(): belongsTo
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
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
     * @return belongsTo
     */
    public function company(): belongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return HasMany
     */
    public function payments()
    {
        return $this->hasMany(InvoicePayment::class);
    }

    /**
     * @return MorphMany
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * @return MorphMany
     */
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
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
    public function salesLocation()
    {
        return $this->belongsTo(SalesLocation::class, 'sales_location_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    public function getPaymentReceivedAttribute()
    {
        $paymentReceived = invOutstanding($this)['paid'];
        return $paymentReceived;
    }

    public function getPaymentRemainingAttribute()
    {
        $paymentRemaining = invOutstanding($this)['balance'];
        return $paymentRemaining;
    }

    /**
     * @return BelongsTo
     */
    public function dailySales(){
        return $this->belongsTo(DailySale::class, 'daily_sale_id');
    }

    /**
     * @return belongsTo
     */
    public function route(): belongsTo
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

}
