<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

class TaxRate extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'rate', 'notes', 'is_active'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'name', 'rate', 'notes', 'is_active'
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
        return 'tax-rate';
    }
}
