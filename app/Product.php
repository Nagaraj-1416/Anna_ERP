<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{
    Model, SoftDeletes
};
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo, BelongsToMany, HasMany, HasOne, MorphMany
};
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class Product
 * @package App
 * @property int $id
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property string $code
 * @property string $name
 * @property string $type
 * @property string $base_buying_price
 * @property string $base_wholesale_price
 * @property string $base_retail_price
 * @property string $base_distribution_price
 * @property string $measurement
 * @property string $min_stock_level
 * @property string $notes
 * @property string $tamil_name
 * @property string $is_active
 * @property string $barcode_number
 * @property mixed $batches
 */
class Product extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'type', 'buying_price', 'expense_account', 'wholesale_price', 'retail_price', 'distribution_price',
        'income_account', 'measurement', 'min_stock_level', 'inventory_account', 'notes', 'is_active', 'category_id',
        'tamil_name', 'is_expirable', 'opening_cost', 'opening_cost_at', 'opening_qty', 'opening_qty_at', 'barcode_number', 'packet_price'
    ];

    public $export = [
        'code', 'name', 'tamil_name', 'type', 'buying_price', 'expense_account', 'wholesale_price', 'retail_price', 'distribution_price',
        'income_account', 'measurement', 'min_stock_level', 'notes', 'is_expirable', 'opening_cost', 'opening_cost_at', 'opening_qty', 'opening_qty_at',
        'barcode_number', 'packet_price'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'code', 'name', 'type', 'buying_price', 'expense_account', 'wholesale_price', 'retail_price', 'distribution_price',
        'income_account', 'measurement', 'min_stock_level', 'inventory_account', 'notes', 'is_active', 'category_id',
        'tamil_name', 'is_expirable', 'opening_cost', 'opening_cost_at', 'opening_qty', 'opening_qty_at', 'barcode_number', 'packet_price'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get all of the product's batches.
     * @return HasMany
     */
    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    /**
     * Get audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'product';
    }

    /**
     * Get  product's measurement.
     * @return BelongsTo
     */
    public function measurement(): BelongsTo
    {
        return $this->belongsTo(Measurement::class, 'measurement');
    }

    /**
     * @return MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->with('user');
    }

    /**
     * @return HasMany
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class, 'product_id');
    }

    /**
     * @return BelongsTo
     */
    public function expenseAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'expense_account');
    }

    /**
     * @return BelongsTo
     */
    public function incomeAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'income_account');
    }

    /**
     * @return BelongsTo
     */
    public function inventoryAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'inventory_account');
    }

    /**
     * @return BelongsToMany
     */
    public function purchaseOrders(): belongsToMany
    {
        return $this->belongsToMany(PurchaseOrder::class, 'product_purchase_order',
            'product_id', 'purchase_order_id')
            ->withPivot('store_id', 'quantity', 'rate', 'discount_type', 'discount_rate', 'discount', 'amount', 'status', 'notes');
    }

    public function salesOrders(): belongsToMany
    {
        return $this->belongsToMany(SalesOrder::class, 'product_sales_order',
            'product_id', 'sales_order_id')
            ->withPivot('price_book_id', 'unit_type_id', 'store_id',
                'quantity', 'rate', 'discount_type', 'discount_rate', 'discount', 'amount', 'status', 'notes');
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * @return BelongsToMany
     */
    public function routes(): belongsToMany
    {
        return $this->belongsToMany(Route::class, 'route_product');
    }

    /**
     * @return BelongsToMany
     */
    public function salesLocation(): belongsToMany
    {
        return $this->belongsToMany(SalesLocation::class, 'sales_location_product');
    }

    /**
     * @return HasOne
     */
    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
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
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class, 'product_id');
    }

}
