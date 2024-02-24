<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class Supplier
 * @package App
 * @property int $id
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property string $code
 * @property string $name
 * @property string $salutation
 * @property string $contact_person
 * @property string $phone
 * @property string $fax
 * @property string $mobile
 * @property string $email
 * @property string $website
 * @property string $notes
 * @property string $is_active
 */
class Bank extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'salutation', 'contact_person', 'phone', 'fax', 'mobile', 'email', 'website', 'notes', 'is_active'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'code', 'name', 'salutation', 'contact_person', 'phone', 'fax', 'mobile', 'email', 'website', 'notes', 'is_active'
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
        return 'bank';
    }

}
