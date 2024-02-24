<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class SupplierCreditRefund
 * @package App
 * @property int $id
 * @property carbon $refunded_on
 * @property string $amount
 * @property string $notes
 * @property string $payment_mode
 * @property string $cheque_no
 * @property carbon $cheque_date
 * @property string $account_no
 * @property carbon $deposited_date
 * @property string $status
 * @property string $reason_to_cancel
 * @property int $refunded_from
 * @property int $credit_id
 * @property int $bank_id
 * @property carbon $created_at
 * @property carbon $updated_at
 * @property carbon $deleted_at
 * @property mixed $credit
 */
class SupplierCreditRefund extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * @var array
     */
    protected $fillable = [
        'refunded_on', 'amount', 'notes', 'payment_mode', 'cheque_no', 'cheque_date',
        'account_no', 'deposited_date', 'bank_id', 'status', 'reason_to_cancel', 'refunded_to', 'credit_id'
    ];
    /**
     * @var array
     */
    protected $logAttributes = [
        'refunded_on', 'amount', 'notes', 'payment_mode', 'cheque_no', 'cheque_date',
        'account_no', 'deposited_date', 'bank_id', 'status', 'reason_to_cancel', 'refunded_to', 'credit_id'
    ];

    /**
     * @return BelongsTo
     */
    public function credit()
    {
        return $this->belongsTo(SupplierCredit::class, 'credit_id');
    }

    /**
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class, 'refunded_to');
    }

    /**
     * @return BelongsTo
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
}
