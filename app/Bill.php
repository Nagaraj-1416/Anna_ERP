<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\{
    BelongsTo, MorphMany, HasMany
};
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class Supplier
 * @package App
 * @property int $id
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property string $bill_no
 * @property string $bill_date
 * @property string $due_date
 * @property float $amount
 * @property int $prepared_by
 * @property mixed $preparedBy
 * @property string $approval_status
 * @property int $approved_by
 * @property mixed $approvedBy
 * @property int $purchase_order_id
 * @property mixed $purchaseOrder
 * @property int $supplier_id
 * @property mixed $supplier
 * @property int $business_type_id
 * @property mixed $businessType
 * @property mixed $company
 * @property mixed $payments
 * @property string $notes
 */
class Bill extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'bill_no', 'bill_date', 'due_date', 'amount', 'prepared_by', 'approval_status', 'approved_by', 'status',
        'purchase_order_id', 'supplier_id', 'business_type_id', 'notes', 'company_id'
    ];
    /**
     * @var array
     */
    public $searchable = [
        'bill_no', 'bill_date', 'due_date', 'amount', 'approval_status', 'status', 'notes',
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'bill_no', 'bill_date', 'due_date', 'amount', 'prepared_by', 'approval_status', 'approved_by', 'status',
        'purchase_order_id', 'supplier_id', 'business_type_id', 'notes', 'company_id'
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
        return 'bill';
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
    public function approvedBy(): belongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * @return BelongsTo
     */
    public function order(): belongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
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
    public function businessType(): belongsTo
    {
        return $this->belongsTo(BusinessType::class, 'purchase_order_id');
    }

    /**
     * @return belongsTo
     */
    public function company(): belongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return HasMany
     */
    public function payments()
    {
        return $this->hasMany(BillPayment::class);
    }

    /**
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
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

}
