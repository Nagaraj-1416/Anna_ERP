<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{
    Model, Relations\BelongsTo, SoftDeletes
};
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class Address
 * @package App
 * @property int $id
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property string $street_one
 * @property string $street_two
 * @property string $city
 * @property string $province
 * @property string $postal_code
 * @property int $country_id
 * @property int $addressable_id
 * @property string $addressable_type
 * @property mixed $addressable
 * @property mixed $country
 */
class Address extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'street_one', 'street_two', 'city', 'province', 'postal_code', 'country_id'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'street_one', 'street_two', 'city', 'province', 'postal_code', 'country_id', 'addressable_id', 'addressable_type'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get all of the owning addressable models.
     * @return MorphTo
     */
    public function addressable(): MorphTo
    {
        return $this->morphTo('addressable');
    }

    /**
     * The addresses that belong to the country.
     * @return BelongsTo
     */
    public function country() : BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'address';
    }
}
