<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

use Illuminate\Database\Eloquent\Relations\{
    BelongsTo
};

class PurchaseReturnItem extends Model
{
    use LogsAudit;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'ordered_qty', 'returned_qty', 'ordered_rate', 'returned_rate', 'order_amount', 'returned_amount',
        'reason', 'purchase_return_id', 'order_id', 'product_id', 'supplier_id', 'company_id'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'ordered_qty', 'returned_qty', 'ordered_rate', 'returned_rate', 'order_amount', 'returned_amount',
        'reason', 'purchase_return_id', 'order_id', 'product_id', 'supplier_id', 'company_id'
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
        return 'purchase-return-item';
    }

    /**
     * @return belongsTo
     */
    public function purchaseReturn(): belongsTo
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id');
    }

    /**
     * @return belongsTo
     */
    public function purchaseOrder(): belongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'order_id');
    }

    /**
     * @return belongsTo
     */
    public function product(): belongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * @return belongsTo
     */
    public function supplier(): belongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * @return belongsTo
     */
    public function company(): belongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

}
