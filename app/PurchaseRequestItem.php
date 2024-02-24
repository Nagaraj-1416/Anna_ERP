<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

class PurchaseRequestItem extends Model
{
    use LogsAudit;
    use SoftDeletes;

    protected $fillable = [
        'purchase_request_id', 'product_id', 'production_unit_id', 'store_id', 'shop_id', 'supplier_id',
        'quantity', 'status'
    ];

    protected static $logAttributes = [
        'purchase_request_id', 'product_id', 'production_unit_id', 'store_id', 'shop_id', 'supplier_id',
        'quantity', 'status'
    ];

    protected $dates = ['deleted_at'];

    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'purchase-request-item';
    }

    /**
     * @return BelongsTo
     */
    public function purchaseRequest(): belongsTo
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }

    /**
     * @return BelongsTo
     */
    public function product(): belongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * @return BelongsTo
     */
    public function store(): belongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    /**
     * @return BelongsTo
     */
    public function supplier(): belongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
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
    public function shop(): belongsTo
    {
        return $this->belongsTo(SalesLocation::class, 'shop_id');
    }

    /**
     * @return BelongsTo
     */
    public function productionUnit(): belongsTo
    {
        return $this->belongsTo(ProductionUnit::class, 'production_unit_id');
    }

}
