<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

use Illuminate\Database\Eloquent\Relations\{
    BelongsTo, BelongsToMany, HasMany, MorphMany
};

/**
 * Class Supplier
 * @package App
 * @property int $id
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property string $po_no
 * @property string $order_date
 * @property string $delivery_date
 * @property string $po_type
 * @property string $scheduled_date
 * @property string $terms
 * @property string $notes
 * @property string $status
 * @property string $delivery_status
 * @property string $bill_status
 * @property int $prepared_by
 * @property mixed $preparedBy
 * @property string $approval_status
 * @property int $approved_by
 * @property mixed $approvedBy
 * @property int $supplier_id
 * @property mixed $supplier
 * @property int $business_type_id
 * @property float $sub_total
 * @property float $discount
 * @property float $adjustment
 * @property float $total
 * @property mixed $business_type
 * @property mixed $bills
 * @property mixed $documents
 * @property mixed $payments
 * @property mixed $company
 */

class PurchaseOrder extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'po_no', 'order_date', 'delivery_date', 'po_type', 'po_mode', 'notes', 'status', 'grn_created',
        'grn_received', 'prepared_by', 'store_id', 'supplier_id', 'company_id', 'po_for', 'shop_id',
        'production_unit_id', 'purchase_request_id', 'supply_from', 'supply_store_id'
    ];
    /**
     * @var array
     */
    public $searchable = [
        'po_no', 'order_date', 'delivery_date', 'po_type', 'po_mode', 'notes', 'status', 'grn_created',
        'grn_received', 'prepared_by', 'store_id', 'supplier_id', 'company_id', 'po_for', 'shop_id',
        'production_unit_id', 'purchase_request_id', 'supply_from', 'supply_store_id'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'po_no', 'order_date', 'delivery_date', 'po_type', 'po_mode', 'notes', 'status', 'grn_created',
        'grn_received', 'prepared_by', 'store_id', 'supplier_id', 'company_id', 'po_for', 'shop_id',
        'production_unit_id'
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
        return 'purchase-order';
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
     * @return BelongsToMany
     */
    public function products(): belongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_purchase_order')->withPivot(
            'store_id', 'quantity', 'status'
        );
    }

    /**
     * @return HasMany
     */
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    /**
     * @return HasMany
     */
    public function payments()
    {
        return $this->hasMany(BillPayment::class);
    }

    /**
     * @return HasMany
     */
    public function grns()
    {
        return $this->hasMany(Grn::class);
    }

    /**
     * @return HasMany
     */
    public function grnItems()
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

}
