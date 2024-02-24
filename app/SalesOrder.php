<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

class SalesOrder extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'order_no', 'order_date', 'delivery_date', 'order_type', 'scheduled_date', 'sales_type', 'is_credit_sales',
        'is_po_received', 'po_no', 'po_date', 'po_file', 'terms', 'notes', 'sub_total', 'discount', 'discount_rate',
        'discount_type', 'adjustment', 'total', 'status', 'delivery_status', 'invoice_status', 'is_invoiced',
        'prepared_by', 'approval_status', 'approved_by', 'customer_id', 'business_type_id', 'company_id', 'rep_id',
        'ref', 'sales_location_id', 'gps_lat', 'gps_long', 'is_order_printed', 'sales_category', 'daily_sale_id',
        'location_id', 'is_opining', 'route_id', 'received_cash', 'given_change'
    ];

    protected $appends = ['payment_received', 'payment_remaining'];

    /**
     * @var array
     */
    public $searchable = [
        'order_no', 'order_date', 'delivery_date', 'order_type', 'scheduled_date', 'is_po_received', 'po_no', 'po_date',
        'po_file', 'terms', 'notes', 'sub_total', 'discount', 'discount_rate', 'discount_type', 'adjustment', 'total',
        'status', 'delivery_status', 'invoice_status', 'is_invoiced', 'approval_status',
        'ref', 'location_id',
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'order_no', 'order_date', 'delivery_date', 'order_type', 'scheduled_date', 'sales_type', 'is_credit_sales',
        'is_po_received', 'po_no', 'po_date', 'po_file', 'terms', 'notes', 'sub_total', 'discount', 'discount_rate',
        'discount_type', 'adjustment', 'total', 'status', 'delivery_status', 'invoice_status', 'is_invoiced',
        'prepared_by', 'approval_status', 'approved_by', 'customer_id', 'business_type_id', 'company_id', 'rep_id',
        'ref', 'sales_location_id', 'gps_lat', 'gps_long', 'is_order_printed', 'sales_category', 'daily_sale_id',
        'location_id', 'is_opining', 'route_id', 'received_cash', 'given_change'
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
        return 'sales-order';
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
     * @return BelongsTo
     */
    public function location(): belongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    /**
     * @return BelongsToMany
     */
    public function products(): belongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('price_book_id', 'unit_type_id', 'store_id',
            'quantity', 'rate', 'discount_type', 'discount_rate', 'discount', 'amount', 'status', 'notes', 'is_vehicle');
    }

    /**
     * @return HasMany
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * @return HasMany
     */
    public function payments()
    {
        return $this->hasMany(InvoicePayment::class);
    }

    /**
     * @return BelongsTo
     */
    public function salesRep()
    {
        return $this->belongsTo(Rep::class, 'rep_id');
    }

    /**
     * @return BelongsTo
     */
    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    /**
     * @return BelongsTo
     */
    public function priceBook()
    {
        return $this->belongsTo(PriceBook::class);
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
     * @return MorphMany
     */
    public function inquiries()
    {
        return $this->morphMany(SalesInquiry::class, 'converted', 'converted_type', 'converted_id');
    }
    /**
     * @return BelongsTo
     */
    public function salesLocation()
    {
        return $this->belongsTo(SalesLocation::class, 'sales_location_id');
    }

    public function getPaymentReceivedAttribute()
    {
        $paymentReceived = soOutstanding($this)['paid'];
        return $paymentReceived;
    }

    public function getPaymentRemainingAttribute()
    {
        $paymentRemaining = soOutstanding($this)['balance'];
        return $paymentRemaining;
    }

    /**
     * @return BelongsTo
     */
    public function dailySales()
    {
        return $this->belongsTo(DailySale::class, 'daily_sale_id');
    }

    public function openingReference()
    {
        return $this->hasOne(OpeningBalanceReference::class, 'order_id');
    }
}
