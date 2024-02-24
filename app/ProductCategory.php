<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class ProductCategory
 * @package App
 * @property $name
 */
class ProductCategory extends Model
{
    use SoftDeletes;
    use LogsAudit;
    /**
     * @var array
     */
    protected $fillable = [
        'name'
    ];
    /**
     * @var array
     */
    protected static $logAttributes = [
        'name'
    ];

    /**
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
