<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

class DailySaleItem extends Model
{
    use LogsAudit;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'daily_sale_id', 'product_id', 'store_id', 'quantity', 'sold_qty', 'restored_qty', 'notes', 'cf_qty',
        'replaced_qty', 'added_stage', 'returned_qty', 'shortage_qty', 'damaged_qty', 'excess_qty', 'actual_stock'
    ];

    public $export = [
        'product_id', 'quantity', 'notes', 'store_id', 'sold_qty', 'replaced_qty', 'cf_qty', 'restored_qty',
        'excess_qty', 'returned_qty', 'damaged_qty', 'shortage_qty'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'daily_sale_id', 'product_id', 'store_id', 'quantity', 'sold_qty', 'restored_qty', 'notes', 'cf_qty',
        'replaced_qty', 'added_stage', 'returned_qty', 'shortage_qty', 'damaged_qty', 'excess_qty', 'actual_stock'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return BelongsTo
     */
    public function dailySale(): BelongsTo
    {
        return $this->belongsTo(DailySale::class, 'daily_sale_id');
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
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    /**
     * Get audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'daily-sale-item';
    }

}
