<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class EmailTemplate
 * @package App
 * @property int $id
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $deleted_at
 * @property string $class
 * @property string $name
 * @property string $description
 * @property string $subject
 * @property string $content
 * @property array $variables
 * @property array $loops
 * @property array $links
 * @property string read_only
 */
class EmailTemplate extends Model
{
    use LogsAudit;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'class', 'name', 'description', 'subject', 'content',  'variables', 'loops', 'links', 'read_only'
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'variables' => 'array',
        'loops' => 'array',
        'links' => 'array',
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'class', 'name', 'description','subject', 'content',  'variables', 'loops', 'links', 'read_only'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'emailTemplate';
    }
}
