<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

use Illuminate\Database\Eloquent\Relations\{
    BelongsTo
};

/**
 * Class Route
 * @package App
 * @property int $id
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property int $stock_id
 * @property mixed $stock
 * @property string $quantity
 * @property string $transaction
 * @property string $trans_date
 * @property string $trans_description
 * @property string $rate
 * @property string $type
 */

class StockHistory extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'stock_id', 'quantity', 'transaction', 'trans_date', 'trans_description',
        'production_unit_id', 'sales_location_id', 'rate', 'type', 'store_id'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'stock_id', 'quantity', 'transaction', 'trans_date', 'trans_description',
        'production_unit_id', 'sales_location_id', 'rate', 'type', 'store_id'
    ];

    public $export = [
        'trans_date', 'type', 'quantity', 'transaction', 'trans_description'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The departments that belong to the stock.
     * @return belongsTo
     */
    public function stock(): belongsTo
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function transable()
    {
        return $this->morphTo();
    }

    /**
     * Get audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'stock-history';
    }

    /**
     * @return belongsTo
     */
    public function productionUnit(): belongsTo
    {
        return $this->belongsTo(ProductionUnit::class, 'production_unit_id');
    }

    /**
     * @return belongsTo
     */
    public function salesLocation(): belongsTo
    {
        return $this->belongsTo(SalesLocation::class, 'sales_location_id');
    }

    /**
     * @return belongsTo
     */
    public function store(): belongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

}
