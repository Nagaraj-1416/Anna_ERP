<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class ChequeInHand
 * @package App
 * @property Carbon $registered_date
 * @property string $type
 * @property double $amount
 * @property Carbon $cheque_date
 * @property string $cheque_no
 * @property int $bank_id
 * @property int $chequeable_id
 * @property string $chequeable_type
 * @property int $sales_handover_id
 * @property string $notes
 * @property int $credited_to
 * @property int $deposited_to
 * @property int $prepared_by
 * @property int $business_type_id
 * @property int $customer_id
 * @property int $daily_sale_id
 * @property int $company_id
 * @property string $shortage
 */
class ChequeInHand extends Model
{
    use SoftDeletes;
    use LogsAudit;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['registered_date', 'type', 'amount', 'cheque_date', 'cheque_no', 'bank_id',
        'chequeable_id', 'chequeable_type', 'sales_handover_id', 'notes', 'status', 'credited_to',
        'deposited_to', 'prepared_by', 'business_type_id', 'company_id', 'shortage', 'customer_id',
        'daily_sale_id', 'is_transferred', 'transferred_from', 'transferred_to', 'bounced_date', 'rep_id'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected $logAttributes = ['registered_date', 'type', 'amount', 'cheque_date', 'cheque_no', 'bank_id',
        'chequeable_id', 'chequeable_type', 'sales_handover_id', 'notes', 'status', 'credited_to',
        'deposited_to', 'prepared_by', 'business_type_id', 'company_id', 'shortage', 'customer_id',
        'daily_sale_id', 'is_transferred', 'transferred_from', 'transferred_to'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'cheque-in-hand';
    }

    /**
     * @return BelongsTo
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    /**
     * @return BelongsTo
     */
    public function handover(): BelongsTo
    {
        return $this->belongsTo(SalesHandover::class, 'sales_handover_id');
    }

    /**
     * @return BelongsTo
     */
    public function creditedTo(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'credited_to');
    }

    /**
     * @return BelongsTo
     */
    public function depositedTo(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'deposited_to');
    }

    /**
     * @return BelongsTo
     */
    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    /**
     * @return BelongsTo
     */
    public function businessType(): BelongsTo
    {
        return $this->belongsTo(BusinessType::class, 'business_type_id');
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function chequeable()
    {
        return $this->morphTo();
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * @return BelongsTo
     */
    public function dailySale(): BelongsTo
    {
        return $this->belongsTo(DailySale::class, 'daily_sale_id');
    }

    /**
     * @return BelongsTo
     */
    public function transferredFrom(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'transferred_from');
    }

    /**
     * @return BelongsTo
     */
    public function transferredTo(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'transferred_to');
    }

}
