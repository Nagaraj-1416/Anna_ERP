<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{
    Model, Relations\BelongsTo, SoftDeletes
};

use Jeylabs\AuditLog\Traits\LogsAudit;


/**
 * Class ExpenseItem
 * @package App
 * @property int $id
 * @property string $notes
 * @property float $amount
 * @property int $expense_id
 * @property int $category_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property mixed $expense
 * @property mixed $category
 * @property mixed $company
 */
class ExpenseItem extends Model
{
    use SoftDeletes;
    use LogsAudit;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'expense_id', 'item', 'expiry_date', 'notes'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'expense_id', 'item', 'expiry_date', 'notes'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     *  the item that belong to the expense.
     * @return BelongsTo
     */
    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }

}
