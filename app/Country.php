<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{
    Model, SoftDeletes
};
use Illuminate\Database\Eloquent\Relations\{
    HasMany
};
use Jeylabs\AuditLog\Traits\LogsAudit;

/**
 * Class Country
 * @package App
 * @property int $id
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property string $capital
 * @property string $citizenship
 * @property string $country_code
 * @property string $currency
 * @property string $currency_code
 * @property string $currency_sub_unit
 * @property string $currency_symbol
 * @property string $full_name
 * @property string $iso_3166_2
 * @property string $iso_3166_3
 * @property string $name
 * @property string $region_code
 * @property string $sub_region_code
 * @property boolean $eea
 * @property string $calling_code
 * @property string $flag
 * @property mixed $companies
 */
class Country extends Model
{
    use LogsAudit;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'capital', 'citizenship', 'country_code', 'currency', 'currency_code', 'currency_sub_unit', 'currency_symbol',
        'full_name', 'iso_3166_2', 'iso_3166_3', 'name', 'region_code', 'sub_region_code', 'eea', 'calling_code',
        'flag'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'capital', 'citizenship', 'country_code', 'currency', 'currency_code', 'currency_sub_unit', 'currency_symbol',
        'full_name', 'iso_3166_2', 'iso_3166_3', 'name', 'region_code', 'sub_region_code', 'eea', 'calling_code',
        'flag'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get all of the country's companies.
     * @return HasMany
     */
    public function companies(): HasMany
    {
        return $this->hasMany(Company::class, 'business_location');
    }

    /**
     * Get the audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'country';
    }
}
