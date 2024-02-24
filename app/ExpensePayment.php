<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

class ExpensePayment extends Model
{
    use LogsAudit;
    use SoftDeletes;

    protected $fillable = [
        'payment', 'payment_date', 'payment_mode', 'cheque_no', 'cheque_date', 'account_no',
        'deposited_date', 'card_holder_name', 'card_no', 'expiry_date', 'bank_id', 'status',
        'notes', 'prepared_by', 'paid_through', 'expense_id', 'company_id'
    ];

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function paidThrough(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'paid_through');
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    /**
     * @return HasMany
     */
    public function cheques(): HasMany
    {
        return $this->hasMany(ExpenseCheque::class);
    }

}
