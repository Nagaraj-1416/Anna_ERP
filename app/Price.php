<?php

namespace App;

use Illuminate\Database\Eloquent\{
    Model, SoftDeletes
};
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo, MorphTo
};
use Jeylabs\AuditLog\Traits\LogsAudit;

class Price extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'price', 'range_start_from', 'range_end_to', 'product_id', 'price_book_id'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'price', 'range_start_from', 'range_end_to', 'product_id', 'price_book_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    public $export = [
        'product_id', 'price', 'range_start_from', 'range_end_to'
    ];

    /**
     * @return BelongsTo
     */
    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }


    /**
     * @return BelongsTo
     */
    public function priceBook() : BelongsTo
    {
        return $this->belongsTo(PriceBook::class);
    }

    /**
     * Get the audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'price';
    }

}
