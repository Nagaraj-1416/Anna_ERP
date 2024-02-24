<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockShortageItem extends Model
{
    use SoftDeletes;
    use LogsAudit;

    protected $fillable = [
        'date', 'qty', 'rate', 'amount', 'product_id', 'stock_id', 'store_id', 'stock_shortage_id',
        'status', 'approved_by', 'approved_on'
    ];

    protected static $logAttributes = [
        'date', 'qty', 'rate', 'amount', 'product_id', 'stock_id', 'store_id', 'stock_shortage_id',
        'status', 'approved_by', 'approved_on'
    ];

    protected $dates = ['deleted_at'];

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
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    /**
     * @return BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    /**
     * @return BelongsTo
     */
    public function stockShortage(): BelongsTo
    {
        return $this->belongsTo(StockShortage::class, 'stock_shortage_id');
    }

    /**
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'stock-shortage-item';
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

}
