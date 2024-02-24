<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class ExpenseType
 * @package App
 * @property integer id
 * @property string name
 * @property string description
 * @property string is_active
 * @property string is_deletable
 * @property integer account_id
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 * @property string is_mobile_enabled
 */
class ExpenseType extends Model
{
    use SoftDeletes;
    use LogsAudit;

    protected $fillable = ['name', 'description', 'is_active', 'is_deletable', 'account_id', 'is_mobile_enabled'];

    protected static $logAttributes = ['name', 'description', 'is_active', 'is_deletable', 'account_id', 'is_mobile_enabled'];

    /**
     * Expense type's accounts
     */
    public function account()
    {
        $this->belongsTo(Account::class);
    }
}
