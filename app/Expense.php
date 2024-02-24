<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{
    Model, Relations\BelongsTo, Relations\HasMany, SoftDeletes, Relations\MorphMany
};
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class Expense
 * @package App
 * @property int $id
 * @property string $expense_no
 * @property string $expense_date
 * @property string $claim_reimburse
 * @property string $expense_type
 * @property string $notes
 * @property double $amount
 * @property string $status
 * @property int $category_id
 * @property int $expense_account
 * @property int $paid_through
 * @property int $prepared_by
 * @property int $approved_by
 * @property int $supplier_id
 * @property int $customer_id
 * @property int $business_type_id
 * @property int $company_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property mixed $type
 * @property mixed $paidThrough
 * @property mixed $preparedBy
 * @property mixed $approvedBy
 * @property mixed $supplier
 * @property mixed $customer
 * @property mixed $businessType
 * @property mixed $company
 * @property mixed $expenseAccount
 * @property mixed $staff
 * @property mixed $report
 */
class Expense extends Model
{
    use SoftDeletes;
    use LogsAudit;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'expense_no', 'expense_date', 'expense_time', 'claim_reimburse', 'expense_items', 'notes', 'amount', 'status',
        'calculate_mileage_using', 'distance', 'start_reading', 'end_reading', 'staff_id', 'type_id',
        'expense_account', 'paid_through', 'prepared_by', 'approved_by', 'supplier_id', 'customer_id',
        'business_type_id', 'company_id', 'report_id', 'sales_expense_id', 'type_id', 'expense_category',
        'card_holder_name', 'card_no', 'expiry_date', 'vehicle_id', 'month', 'installment_period', 'no_of_days',
        'what_was_repaired', 'changed_item', 'repair_expiry_date', 'repairing_shop', 'labour_charge', 'driver_id',
        'odo_at_repair', 'service_station', 'odo_at_service', 'parking_name', 'vehicle_maintenance_type',
        'from_date', 'to_date', 'no_of_months', 'fine_reason', 'from_destination', 'to_destination', 'no_of_bags',
        'account_number', 'units_reading', 'machine', 'festival_name', 'donated_to', 'donated_reason',
        'hotel_name', 'bank_number', 'expense_mode', 'branch_id', 'shop_id', 'approval_required', 'approved_by', 'approved_on'
    ];
    /**
     * @var array
     */
    public $searchable = [
        'expense_no', 'expense_date', 'claim_reimburse', 'expense_items', 'notes', 'amount', 'status',
        'calculate_mileage_using', 'distance', 'start_reading', 'end_reading', 'sales_expense_id'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'expense_no', 'expense_date', 'claim_reimburse', 'expense_items', 'notes', 'amount', 'status',
        'calculate_mileage_using', 'distance', 'start_reading', 'end_reading', 'staff_id', 'type_id',
        'expense_account', 'paid_through', 'prepared_by', 'approved_by', 'supplier_id', 'customer_id',
        'business_type_id', 'company_id', 'report_id', 'sales_expense_id', 'type_id', 'expense_category',
        'card_holder_name', 'card_no', 'expiry_date', 'vehicle_id', 'month', 'installment_period', 'no_of_days',
        'what_was_repaired', 'changed_item', 'repair_expiry_date', 'repairing_shop', 'labour_charge', 'driver_id',
        'odo_at_repair', 'service_station', 'odo_at_service', 'parking_name', 'vehicle_maintenance_type',
        'from_date', 'to_date', 'no_of_months', 'fine_reason', 'from_destination', 'to_destination', 'no_of_bags',
        'account_number', 'units_reading', 'machine', 'festival_name', 'donated_to', 'donated_reason',
        'hotel_name', 'bank_number', 'expense_mode', 'branch_id', 'shop_id', 'approval_required', 'approved_by', 'approved_on'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     *  the expense that belong to the type.
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(ExpenseType::class, 'type_id');
    }

    /**
     *  the expense that belong to the paid through account.
     * @return BelongsTo
     */
    public function paidThrough(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'paid_through');
    }

    /**
     *  the expense that belong to the prepared by user.
     * @return BelongsTo
     */
    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    /**
     *  the expense that belong to the approved by user.
     * @return BelongsTo
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     *  the expense that belong to the supplier.
     * @return BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     *  the expense that belong to the customer.
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     *  the expense that belong to the customer.
     * @return BelongsTo
     */
    public function businessType(): BelongsTo
    {
        return $this->belongsTo(BusinessType::class, 'business_type_id');
    }

    /**
     *  the expense that belong to the company.
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     *  the expense that belong to the expense account.
     * @return BelongsTo
     */
    public function expenseAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'expense_account');
    }

    /**
     *  the expense that belong to the staff.
     * @return BelongsTo
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     *  the expense that belong to the staff.
     * @return BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'driver_id');
    }

    /**
     *  the expense that belong to the report.
     * @return BelongsTo
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(ExpenseReport::class, 'report_id');
    }

    /**
     *  the expense that belong to the bank.
     * @return BelongsTo
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    /**
     *  the expense that have many categories.
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(ExpenseItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
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

    public function salesExpense(): BelongsTo
    {
        return $this->belongsTo(SalesExpense::class, 'sales_expense_id');
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'branch_id');
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(SalesLocation::class, 'shop_id');
    }

    /**
     * Get the audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'expense';
    }

    /**
     * @return HasMany
     */
    public function cheques(): HasMany
    {
        return $this->hasMany(ExpenseCheque::class);
    }

    /**
     * @return HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(ExpensePayment::class);
    }
}
