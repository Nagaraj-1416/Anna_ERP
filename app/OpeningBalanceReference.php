<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;
use Illuminate\Database\Eloquent\{
    Relations\BelongsTo
};

class OpeningBalanceReference extends Model
{
    use LogsAudit;
//    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'date', 'amount', 'account_id', 'updated_by', 'customer_id', 'supplier_id', 'reference_no'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'date', 'amount', 'account_id', 'updated_by', 'customer_id', 'supplier_id', 'reference_no'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The stores that belong to the account
     * @return belongsTo
     */
    public function account(): belongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * The stores that belong to the user
     * @return belongsTo
     */
    public function updatedBy(): belongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * The stores that belong to the customer
     * @return belongsTo
     */
    public function customer(): belongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * The stores that belong to the supplier
     * @return belongsTo
     */
    public function supplier(): belongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Get the audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'opening-balance-reference';
    }

    /**
     * @return BelongsTo
     */
    public function order(){
        return $this->belongsTo(SalesOrder::class, 'order_id');
    }
}
