<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{
    Model, SoftDeletes
};
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo, MorphTo
};
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class Document
 * @package App
 * @property int $id
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property string $name
 * @property string $mime
 * @property string $extension
 * @property string $size
 * @property string $documentable_type
 * @property int $documentable_id
 * @property int $user_id
 * @property mixed $user
 * @property mixed $documentable
 */
class Document extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'mime', 'extension', 'size', 'documentable_type', 'documentable_id', 'user_id'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'name', 'mime', 'extension', 'size', 'documentable_type', 'documentable_id', 'user_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The document that belong to the user.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all of the owning documentable models.
     * @return MorphTo
     */
    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'document';
    }
}
