<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

class SalesReturnReplaces extends Model
{
    use LogsAudit;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'qty', 'rate', 'amount', 'product_id', 'resolution_id', 'sales_return_id'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'qty', 'rate', 'amount', 'product_id', 'resolution_id', 'sales_return_id'
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
        return 'sales-return-replace';
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
    public function resolution(): BelongsTo
    {
        return $this->belongsTo(SalesReturnResolution::class, 'resolution_id');
    }

    /**
     * @return BelongsTo
     */
    public function salesReturn(): BelongsTo
    {
        return $this->belongsTo(SalesReturn::class, 'sales_return_id');
    }

}
