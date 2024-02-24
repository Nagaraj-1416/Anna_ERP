<?php

namespace App;

use Illuminate\Database\Eloquent\{
    Model, SoftDeletes
};
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class PDFTemplate
 * @package App
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property string $class
 * @property string $name
 * @property string $description
 * @property string $template_properties
 * @property string $header_properties
 * @property string $footer_properties
 * @property string $content_properties
 * @property string $read_only
 */
class PDFTemplate extends Model
{
    use SoftDeletes;
    use LogsAudit;
    /**
     * The attributes that are table.
     * @var string
     */
    protected $table = 'pdf_templates';
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'class', 'name', 'description', 'template_properties', 'header_properties', 'footer_properties',
        'content_properties', 'read_only'
    ];
    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected $logAttributes = [
        'class', 'name', 'description', 'template_properties', 'header_properties', 'footer_properties',
        'content_properties', 'read_only'
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'template_properties' => 'array',
        'header_properties' => 'array',
        'footer_properties' => 'array',
        'content_properties' => 'array',
    ];
    
    /**
     * Get the audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'PDFTemplates';
    }
}
