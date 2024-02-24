<?php

namespace App;

use Illuminate\Database\Eloquent\{
    SoftDeletes, Model, Relations\MorphTo
};
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class ContactPerson
 * @package App
 * @property  int $id
 * @property  string $salutation
 * @property  string $first_name
 * @property  string $last_name
 * @property  string $full_name
 * @property  string $phone
 * @property  string $mobile
 * @property  string $email
 * @property  string $designation
 * @property  string $department
 * @property  string $is_active
 * @property  mixed $contactPersonable
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property mixed $contact_personable
 * @property int $contact_personable_id
 * @property string $contact_personable_type
 */
class ContactPerson extends Model
{
    use LogsAudit;
    use SoftDeletes;
    protected $table = 'contact_persons';
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'salutation', 'full_name', 'phone','mobile', 'email', 'designation', 'department', 'is_active'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'first_name', 'last_name', 'salutation', 'full_name', 'phone','mobile', 'email', 'designation', 'department', 'is_active'
    ];

    /**
     * Get all of the owning contactable models.
     * @return MorphTo
     */
    public function contactPersonable() :MorphTo
    {
        return $this->morphTo();
    }
}
