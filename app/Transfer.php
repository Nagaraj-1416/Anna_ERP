<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Transfer extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'type', 'category', 'date', 'amount', 'transfer_by', 'sender', 'receiver', 'status', 'received_by',
        'received_on', 'transaction_id', 'transfer_mode', 'handed_over_date', 'handed_over_time', 'handed_order_to',
        'deposited_date', 'deposited_time', 'deposited_to', 'received_amount', 'deposited_receipt',
        'receipt_uploaded_on', 'receipt_uploaded_by'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'type', 'category', 'date', 'amount', 'transfer_by', 'sender', 'receiver', 'status', 'received_by',
        'received_on', 'transaction_id', 'transfer_mode', 'handed_over_date', 'handed_over_time', 'handed_order_to',
        'deposited_date', 'deposited_time', 'deposited_to', 'received_amount', 'deposited_receipt',
        'receipt_uploaded_on', 'receipt_uploaded_by'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return BelongsTo
     */
    public function transferBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'transfer_by');
    }

    /**
     * @return BelongsTo
     */
    public function senderCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'sender');
    }

    /**
     * @return BelongsTo
     */
    public function receiverCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'receiver');
    }

    /**
     * @return BelongsTo
     */
    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    /**
     * @return HasMany
     */
    public function items()
    {
        return $this->hasMany(TransferItem::class, 'transfer_id');
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
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
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
    public function debitedTo(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'debited_to');
    }

    /**
     * @return BelongsTo
     */
    public function handedOrderTo(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'handed_order_to');
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
    public function receiptUploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receipt_uploaded_by');
    }

}
