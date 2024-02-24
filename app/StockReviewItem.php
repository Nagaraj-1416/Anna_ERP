<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

use Illuminate\Database\Eloquent\{
    Relations\BelongsTo
};

class StockReviewItem extends Model
{
    use SoftDeletes;
    use LogsAudit;

    protected $fillable = [
        'date', 'available_qty', 'actual_qty', 'excess_qty', 'shortage_qty', 'rate', 'amount', 'product_id', 'stock_id', 'stock_review_id'
    ];

    protected static $logAttributes = [
        'date', 'available_qty', 'actual_qty', 'excess_qty', 'shortage_qty', 'rate', 'amount', 'product_id', 'stock_id', 'stock_review_id'
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
    public function stockReview(): BelongsTo
    {
        return $this->belongsTo(StockReview::class, 'stock_review_id');
    }

    /**
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'stock-review-item';
    }

}
