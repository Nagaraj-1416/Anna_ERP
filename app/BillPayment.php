<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

use Illuminate\Database\Eloquent\Relations\{
    BelongsTo
};

/**
 * Class Supplier
 * @package App
 * @property int $id
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property float $payment
 * @property string $payment_date
 * @property string $payment_type
 * @property string $payment_mode
 * @property string $payment_from
 * @property string $cheque_no
 * @property string $cheque_date
 * @property string $account_no
 * @property string $deposited_date
 * @property int $prepared_by
 * @property mixed $preparedBy
 * @property int $bill_id
 * @property mixed $bill
 * @property int $bank_id
 * @property mixed $bank
 * @property string $status
 * @property string $notes
 */
class BillPayment extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'payment', 'payment_date', 'payment_type', 'payment_mode', 'payment_from', 'cheque_type', 'cheque_no', 'cheque_date', 'account_no',
        'deposited_date', 'bank_id', 'status', 'notes', 'prepared_by', 'bill_id', 'purchase_order_id', 'supplier_id',
        'business_type_id', 'company_id', 'paid_through', 'card_holder_name', 'card_no', 'expiry_date'
    ];
    /**
     * @var array
     */
    public $searchable = [
        'payment', 'payment_date', 'payment_type', 'payment_mode', 'payment_from', 'cheque_type', 'cheque_no', 'cheque_date', 'account_no',
        'deposited_date', 'status', 'notes', 'card_holder_name', 'card_no', 'expiry_date'
    ];
    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'payment', 'payment_date', 'payment_type', 'payment_mode', 'payment_from', 'cheque_type', 'cheque_no', 'cheque_date', 'account_no',
        'deposited_date', 'bank_id', 'status', 'notes', 'prepared_by', 'bill_id', 'purchase_order_id', 'supplier_id',
        'business_type_id', 'company_id', 'paid_through', 'card_holder_name', 'card_no', 'expiry_date'
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
        return 'bill-payment';
    }

    /**
     * @return belongsTo
     */
    public function bank(): belongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    /**
     * @return belongsTo
     */
    public function preparedBy(): belongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    /**
     * @return belongsTo
     */
    public function bill(): belongsTo
    {
        return $this->belongsTo(Bill::class, 'bill_id');
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
        return $this->belongsTo(BusinessType::class, 'business_type_id');
    }

    /**
     * @return belongsTo
     */
    public function company(): belongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return belongsTo
     */
    public function paidThrough(): belongsTo
    {
        return $this->belongsTo(Account::class, 'paid_through');
    }

    /**
     * @return belongsTo
     */
    public function credit(): belongsTo
    {
        return $this->belongsTo(SupplierCredit::class, 'credit_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

}
