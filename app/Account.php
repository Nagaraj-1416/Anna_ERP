<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

class Account extends Model
{
    use LogsAudit;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'code', 'prefix', 'name', 'short_name', 'notes', 'is_default', 'is_active', 'closing_bl_carried', 'first_tx_date',
        'latest_tx_date', 'accountable_id', 'accountable_type', 'parent_account_id', 'group_id',
        'account_type_id', 'account_category_id', 'company_id', 'opening_balance', 'opening_balance_at', 'opening_balance_type'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'code', 'prefix', 'name', 'short_name', 'notes', 'is_default', 'is_active', 'closing_bl_carried', 'first_tx_date',
        'latest_tx_date', 'accountable_id', 'accountable_type', 'parent_account_id','group_id',
        'account_type_id', 'account_category_id', 'company_id', 'opening_balance', 'opening_balance_at', 'opening_balance_type'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_account_id');
    }

    /**
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(AccountCategory::class, 'account_category_id');
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return MorphTo
     */
    public function accountable(): MorphTo
    {
        return $this->morphTo();
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
     * @return HasMany
     */
    public function transactions()
    {
        return $this->hasMany(TransactionRecord::class);
    }

    /**
     * Get audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'account';
    }

    /**
     * @return HasMany
     */
    public function references()
    {
        return $this->hasMany(OpeningBalanceReference::class, 'account_id');
    }

    /**
     * @return BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(AccountGroup::class, 'group_id');
    }

}
