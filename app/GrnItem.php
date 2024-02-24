<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

use Illuminate\Database\Eloquent\Relations\{
    BelongsTo
};

class GrnItem extends Model
{
    use LogsAudit;
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'quantity', 'issued_qty', 'pending_qty', 'received_qty', 'rejected_qty', 'rate', 'discount_type', 'discount_rate', 'discount', 'amount', 'manufacture_date', 'expiry_date',
        'batch_no', 'grade', 'color', 'packing_type', 'brand_id', 'status', 'grn_id', 'product_id', 'no_of_bags'
    ];

    /**
     * @var array
     */
    protected static $logAttributes = [
        'quantity', 'issued_qty', 'pending_qty', 'received_qty', 'rejected_qty', 'rate', 'discount_type', 'discount_rate', 'discount', 'amount', 'manufacture_date', 'expiry_date',
        'batch_no', 'grade', 'color', 'packing_type', 'brand_id', 'status', 'grn_id', 'product_id', 'no_of_bags'
    ];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'grn-item';
    }

    public function brand(): belongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function grn(): belongsTo
    {
        return $this->belongsTo(Grn::class, 'grn_id');
    }

    public function product(): belongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
