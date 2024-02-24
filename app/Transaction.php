<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class Transaction
 * @package App
 * @property string id
 * @property string date
 * @property double amount
 * @property string category
 * @property string type
 * @property string auto_narration
 * @property string manual_narration
 * @property int tx_type_id
 * @property int transactionable_id
 * @property string transactionable_type
 * @property int prepared_by
 * @property int business_type_id
 * @property int company_id
 * @property int customer_id
 * @property int supplier_id
 * @property mixed txType
 * @property mixed preparedBy
 * @property mixed businessType
 * @property mixed company
 * @property mixed records
 * @property mixed customer
 * @property mixed supplier
 * @property mixed comments
 * @property mixed documents
 */
class Transaction extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'code', 'date', 'amount', 'category', 'type', 'auto_narration', 'manual_narration', 'tx_type_id', 'transactionable_id',
        'transactionable_type', 'prepared_by', 'business_type_id', 'company_id', 'customer_id', 'supplier_id', 'action'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'code', 'date', 'amount', 'category', 'type', 'auto_narration', 'manual_narration', 'tx_type_id', 'transactionable_id',
        'transactionable_type', 'prepared_by', 'business_type_id', 'company_id', 'customer_id', 'supplier_id', 'action'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'transaction-type';
    }

    /**
     * @return BelongsTo
     */
    public function txType(): BelongsTo
    {
        return $this->belongsTo(TransactionType::class, 'tx_type_id');
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

    public function transactionable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function records()
    {
        return $this->hasMany(TransactionRecord::class, 'transaction_id');
    }

    /**
     * @return BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * @return BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
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

}
