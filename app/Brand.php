<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class Brand
 * @package App
 * @property integer id
 * @property string name
 * @property string description
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 */
class Brand extends Model
{
    use SoftDeletes;
    use LogsAudit;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
       'name', 'description', 'is_active', 'is_deletable'
    ];

    /**
     * @var array
     */
    public $searchable = [
        'name', 'description', 'is_active', 'is_deletable'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'name', 'description', 'is_active', 'is_deletable'
    ];

}
