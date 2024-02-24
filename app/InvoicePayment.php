<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rules\In;
use Jeylabs\AuditLog\Traits\LogsAudit;

use Illuminate\Database\Eloquent\Relations\{
    BelongsTo
};

/**
 * Class InvoicePayment
 * @package App
 */
class InvoicePayment extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'payment', 'payment_date', 'payment_type', 'payment_mode', 'payment_from', 'cheque_type', 'cheque_no', 'cheque_date', 'account_no',
        'deposited_date', 'bank_id', 'status', 'notes', 'prepared_by', 'invoice_id', 'sales_order_id', 'customer_id',
        'business_type_id', 'company_id', 'deposited_to', 'sales_location_id', 'card_holder_name', 'card_no', 'expiry_date', 'daily_sale_id',
        'is_cheque_realized', 'route_id', 'is_opening'
    ];
    /**
     * @var array
     */
    public $searchable = [
        'payment', 'payment_date', 'payment_type', 'payment_mode', 'payment_from', 'cheque_type', 'cheque_no', 'cheque_date', 'account_no',
        'deposited_date', 'status', 'notes', 'card_holder_name', 'card_no', 'expiry_date', 'is_cheque_realized'
    ];
    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'payment', 'payment_date', 'payment_type', 'payment_mode', 'payment_from', 'cheque_type', 'cheque_no', 'cheque_date', 'account_no',
        'deposited_date', 'bank_id', 'status', 'notes', 'prepared_by', 'invoice_id', 'sales_order_id', 'customer_id',
        'business_type_id', 'company_id', 'deposited_to', 'sales_location_id', 'card_holder_name', 'card_no', 'expiry_date', 'daily_sale_id', 'is_cheque_realized'
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
        return 'invoice-payment';
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
    public function invoice(): belongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    /**
     * @return BelongsTo
     */
    public function order(): belongsTo
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }

    /**
     * @return BelongsTo
     */
    public function customer(): belongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
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
    public function depositedTo(): belongsTo
    {
        return $this->belongsTo(Account::class, 'deposited_to');
    }

    /**
     * @return belongsTo
     */
    public function credit(): belongsTo
    {
        return $this->belongsTo(CustomerCredit::class, 'credit_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function chequeInHand()
    {
        return $this->morphOne(ChequeInHand::class, 'chequeable');
    }

    /**
     * @return BelongsTo
     */
    public function salesLocation()
    {
        return $this->belongsTo(SalesLocation::class, 'sales_location_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    /**
     * @return BelongsTo
     */
    public function dailySales(){
        return $this->belongsTo(DailySale::class, 'daily_sale_id');
    }

    /**
     * @return belongsTo
     */
    public function route(): belongsTo
    {
        return $this->belongsTo(Route::class, 'route_id');
    }
}
