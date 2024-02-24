<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

use Illuminate\Database\Eloquent\Relations\{
    BelongsTo, HasMany, MorphMany
};

class PurchaseReturn extends Model
{
    use LogsAudit;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'code', 'date', 'items', 'total', 'notes', 'category', 'supplier_id', 'unit_id',
        'store_id', 'shop_id', 'prepared_by', 'prepared_on', 'is_approved', 'approved_by', 'approved_on',
        'company_id'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'code', 'date', 'items', 'total', 'notes', 'category', 'supplier_id', 'unit_id',
        'store_id', 'shop_id', 'prepared_by', 'prepared_on', 'is_approved', 'approved_by', 'approved_on',
        'company_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'purchase-return';
    }

    /**
     * @return belongsTo
     */
    public function supplier(): belongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * @return belongsTo
     */
    public function productionUnit(): belongsTo
    {
        return $this->belongsTo(ProductionUnit::class, 'unit_id');
    }

    /**
     * @return belongsTo
     */
    public function store(): belongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    /**
     * @return belongsTo
     */
    public function shop(): belongsTo
    {
        return $this->belongsTo(SalesLocation::class, 'shop_id');
    }

    /**
     * @return belongsTo
     */
    public function preparedBy(): belongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    /**
     * @return belongsTo
     */
    public function approvedBy(): belongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * @return belongsTo
     */
    public function company(): belongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return HasMany
     */
    public function items()
    {
        return $this->hasMany(PurchaseReturnItem::class);
    }

    /**
     * get all related documents
     * @return MorphMany
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * @return MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->with('user');
    }

}
