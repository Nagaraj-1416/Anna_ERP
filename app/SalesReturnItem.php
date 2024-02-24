<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

class SalesReturnItem extends Model
{
    use LogsAudit;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'date', 'qty', 'type', 'sold_rate', 'returned_rate', 'returned_amount', 'reason', 'sales_return_id', 'order_id',
        'product_id', 'returned_to', 'route_id', 'rep_id', 'customer_id', 'company_id', 'daily_sale_id',
        'manufacture_date', 'expiry_date'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'date', 'qty', 'type', 'sold_rate', 'returned_rate', 'returned_amount', 'reason', 'sales_return_id', 'order_id',
        'product_id', 'returned_to', 'route_id', 'rep_id', 'rep_id', 'customer_id', 'company_id', 'daily_sale_id',
        'manufacture_date', 'expiry_date'
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
        return 'sales-return-item';
    }

    /**
     * @return BelongsTo
     */
    public function salesReturn(): BelongsTo
    {
        return $this->belongsTo(SalesReturn::class, 'sales_return_id');
    }

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class, 'order_id');
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * @return BelongsTo
     */
    public function returnedTo(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'returned_to');
    }

}
