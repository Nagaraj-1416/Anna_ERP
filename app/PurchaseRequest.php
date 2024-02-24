<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

class PurchaseRequest extends Model
{
    use LogsAudit;
    use SoftDeletes;

    protected $fillable = [
        'request_no', 'request_date', 'request_type', 'request_mode', 'request_for', 'notes', 'status', 'prepared_by',
        'production_unit_id', 'store_id', 'shop_id', 'supplier_id', 'company_id', 'supply_store_id', 'supply_from'
    ];

    protected static $logAttributes = [
        'request_no', 'request_date', 'request_type', 'request_mode', 'request_for', 'notes', 'status', 'prepared_by',
        'production_unit_id', 'store_id', 'shop_id', 'supplier_id', 'company_id', 'supply_store_id', 'supply_from'
    ];

    protected $dates = ['deleted_at'];

    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'purchase-request';
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

    /**
     * @return HasMany
     */
    public function items()
    {
        return $this->hasMany(PurchaseRequestItem::class);
    }

    /**
     * @return HasMany
     */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * get all related documents
     * @return MorphMany
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * @return MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->with('user');
    }

}
