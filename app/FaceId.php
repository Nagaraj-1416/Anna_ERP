<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jeylabs\AuditLog\Traits\LogsAudit;

class FaceId extends Model
{
    use LogsAudit;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    public $fillable = ['face_id', 'face_data', 'user_id', 'image_path'];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    public static $logAttributes = ['face_id', 'face_data', 'user_id', 'image_path'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param array|null $value
     */
    public function setFaceDataAttribute(array $value = null)
    {
        $this->attributes['face_data'] = json_encode($value, true);
    }

    /**
     * @param string|null $value
     * @return mixed
     */
    public function getFaceDataAttribute(string $value = null)
    {
        return json_decode($value, true);
    }
}
