<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

use Illuminate\Database\Eloquent\Relations\{
    BelongsTo, HasMany, MorphMany
};

class Grn extends Model
{
    use LogsAudit;
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'code', 'date', 'grn_for', 'notes', 'status', 'transfer_by', 'vehicle_id', 'odo_starts_at', 'odo_ends_at',
        'driver', 'helper', 'vehicle_no', 'transport_name', 'driver_name', 'helper_name', 'loaded_by',
        'trans_started_at', 'trans_ended_at', 'received_by', 'parent_grn', 'purchase_order_id', 'bill_id',
        'prepared_by', 'production_unit_id', 'store_id', 'shop_id', 'supplier_id', 'company_id', 'unloaded_by'
    ];

    /**
     * @var array
     */
    protected static $logAttributes = [
        'code', 'date', 'grn_for', 'notes', 'status', 'transfer_by', 'vehicle_id', 'odo_starts_at', 'odo_ends_at',
        'driver', 'helper', 'vehicle_no', 'transport_name', 'driver_name', 'helper_name', 'loaded_by',
        'trans_started_at', 'trans_ended_at', 'received_by', 'parent_grn', 'purchase_order_id', 'bill_id',
        'prepared_by', 'production_unit_id', 'store_id', 'shop_id', 'supplier_id', 'company_id', 'unloaded_by'
    ];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'grn';
    }

    /**
     * @return BelongsTo
     */
    public function vehicle(): belongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    /**
     * @return BelongsTo
     */
    public function driver(): belongsTo
    {
        return $this->belongsTo(Staff::class, 'driver');
    }

    /**
     * @return BelongsTo
     */
    public function helper(): belongsTo
    {
        return $this->belongsTo(Staff::class, 'helper');
    }

    /**
     * @return BelongsTo
     */
    public function receivedBy(): belongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * @return BelongsTo
     */
    public function parentGrn(): belongsTo
    {
        return $this->belongsTo(Grn::class, 'parent_grn');
    }

    /**
     * @return BelongsTo
     */
    public function purchaseOrder(): belongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    /**
     * @return BelongsTo
     */
    public function bill(): belongsTo
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }

    /**
     * @return BelongsTo
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
    public function productionUnit(): belongsTo
    {
        return $this->belongsTo(ProductionUnit::class, 'production_unit_id');
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
     * @return HasMany
     */
    public function items()
    {
        return $this->hasMany(GrnItem::class);
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

    /**
     * @return MorphMany
     */
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

}
