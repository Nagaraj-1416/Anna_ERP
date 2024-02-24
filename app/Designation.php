<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class Designation
 * @package App
 */
class Designation extends Model
{
    use LogsAudit;
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['name', 'notes'];

    /**
     * @var array
     */
    protected $logAttributes = ['name', 'notes'];
}
