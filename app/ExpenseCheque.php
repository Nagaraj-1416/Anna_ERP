<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

class ExpenseCheque extends Model
{
    use LogsAudit;
    use SoftDeletes;

    protected $fillable = [
        'amount', 'expense_payment_id', 'cheque_in_hand_id', 'expense_id'
    ];

    public function expensePayment(): BelongsTo
    {
        return $this->belongsTo(ExpensePayment::class, 'expense_payment_id');
    }

    public function chequeInHand(): BelongsTo
    {
        return $this->belongsTo(ChequeInHand::class, 'cheque_in_hand_id');
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }

}
