<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class DailySaleCreditOrder
 * @package App
 */
class DailySaleCreditOrder extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * @var array
     */
    protected $fillable = [
        'daily_sale_id', 'customer_id', 'sales_order_id'
    ];
    /**
     * @var array
     */
    protected static $logAttributes = [
        'daily_sale_id', 'customer_id', 'sales_order_id'
    ];
    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return BelongsTo
     */
    public function allocation()
    {
        return $this->belongsTo(DailySale::class, 'daily_sale_id');
    }

    /**
     * @return BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * @return BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }

    /**
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'daily-sale-credit-order';
    }
}
