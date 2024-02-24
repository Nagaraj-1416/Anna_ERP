<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class AccountGroup
 * @package App
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property string $name
 * @property string $description
 * @property string $is_active
 * @property integer $parent_id
 * @property mixed $accounts
 * @property mixed $children
 */
class AccountGroup extends Model
{
    use SoftDeletes;
    use LogsAudit;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'is_active', 'parent_id', 'category_id',
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'name', 'description', 'is_active', 'parent_id', 'category_id',
    ];

    /**
     * group's accounts
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts()
    {
        return $this->hasMany(Account::class, 'group_id');
    }

    /**
     * group's parent
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(AccountGroup::class, 'parent_id');
    }

    /**
     * group's children
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(AccountGroup::class, 'parent_id');
    }

    /**
     * group's parent
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(AccountCategory::class, 'category_id');
    }

    /**
     * Get the log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'account-group';
    }

    /**
     * @return MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->with('user');
    }
}