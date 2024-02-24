<?php

namespace App;

use Jeylabs\AuditLog\Models\AuditLog;

/**
 * Class Activity
 * @package App
 */
class Activity extends AuditLog
{
    /**
     * @var string
     */
    protected $table = 'audit_logs';
    /**
     * @var array
     */
    public $guarded = [];
    /**
     * @var array
     */
    protected $casts = [
        'properties' => 'collection',
    ];
}
