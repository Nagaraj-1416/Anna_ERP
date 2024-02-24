<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class SupplierCredit
 * @package App
 * @property int $id
 * @property string $code
 * @property Carbon $date
 * @property string $amount
 * @property string $notes
 * @property string $status
 * @property int $prepared_by
 * @property int $customer_id
 * @property int $business_type_id
 * @property int $company_id
 * @property int $bill_id
 * @property mixed $company
 * @property mixed $preparedBy
 * @property mixed $bill
 * @property mixed $supplier
 * @property mixed $businessType
 * @property mixed $refunds
 * @property mixed $payments
 */
class SupplierCredit extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * @var array
     */
    protected $fillable = [
        'code', 'date', 'amount', 'notes', 'status', 'prepared_by', 'supplier_id', 'business_type_id', 'company_id', 'bill_id'
    ];
    /**
     * @var array
     */
    public $searchable = [
        'code', 'date', 'amount', 'notes', 'status'
    ];
    /**
     * @var array
     */
    protected $logAttributes = [
        'code', 'date', 'amount', 'notes', 'status', 'prepared_by', 'supplier_id', 'business_type_id', 'company_id', 'bill_id'
    ];
    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * @return BelongsTo
     */
    public function businessType()
    {
        return $this->belongsTo(BusinessType::class);
    }

    /**
     * @return BelongsTo
     */
    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    /**
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->with('user');
    }

    /**
     * @return BelongsTo
     */
    public function preparedBy()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    /**
     * @return MorphMany
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * @return HasMany
     */
    public function refunds()
    {
        return $this->hasMany(SupplierCreditRefund::class, 'credit_id');
    }

    /**
     * @return HasMany
     */
    public function payments()
    {
        return $this->hasMany(BillPayment::class, 'credit_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

}
