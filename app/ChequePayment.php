<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

use Illuminate\Database\Eloquent\Relations\{
    BelongsTo
};

class ChequePayment extends Model
{
    use LogsAudit;
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'cheque', 'payment', 'payment_date', 'payment_type', 'payment_mode', 'cheque_type', 'cheque_no', 'cheque_date', 'account_no',
        'deposited_date', 'card_holder_name', 'card_no', 'expiry_date', 'bank_id', 'status', 'notes', 'prepared_by', 'gps_lat', 'gps_long',
        'deposited_to', 'daily_sale_id', 'invoice_id', 'sales_order_id', 'customer_id', 'rep_id', 'route_id', 'company_id', 'is_printed'
    ];

    /**
     * @var array
     */
    protected static $logAttributes = [
        'cheque', 'payment', 'payment_date', 'payment_type', 'payment_mode', 'cheque_type', 'cheque_no', 'cheque_date', 'account_no',
        'deposited_date', 'card_holder_name', 'card_no', 'expiry_date', 'bank_id', 'status', 'notes', 'prepared_by', 'gps_lat', 'gps_long',
        'deposited_to', 'daily_sale_id', 'invoice_id', 'sales_order_id', 'customer_id', 'rep_id', 'route_id', 'company_id', 'is_printed'
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
        return 'cheque-payment';
    }

    /**
     * @return BelongsTo
     */
    public function bank(): belongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id');
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
    public function depositedTo(): belongsTo
    {
        return $this->belongsTo(Account::class, 'deposited_to');
    }

    /**
     * @return BelongsTo
     */
    public function dailySale(): belongsTo
    {
        return $this->belongsTo(DailySale::class, 'daily_sale_id');
    }

    /**
     * @return BelongsTo
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
    public function rep(): belongsTo
    {
        return $this->belongsTo(Rep::class, 'rep_id');
    }

    /**
     * @return BelongsTo
     */
    public function route(): belongsTo
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    /**
     * @return BelongsTo
     */
    public function company(): belongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

}
