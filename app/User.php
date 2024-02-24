<?php

namespace App;

use App\Swagger\Models\Collection;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo, HasMany
};
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Jeylabs\AuditLog\Traits\LogsAudit;
use Laravel\Passport\HasApiTokens;
use League\OAuth2\Server\Exception\OAuthServerException;

/**
 * Class User
 * @package App
 * @property int $id
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $tfa_expiry
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property string $is_active
 * @property string $tfa
 * @property int $role_id
 * @property mixed $role
 * @property mixed $staffs
 * @property boolean isAdminUser
 * @property boolean isRepUser
 */
class User extends Authenticatable
{
    use Notifiable;
    use LogsAudit;
    use SoftDeletes;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_active', 'role_id', 'prefix', 'tfa_expiry', 'tfa'
    ];

    /**
     * The attributes that are audit logs.
     * @var array
     */
    protected static $logAttributes = [
        'name', 'email', 'password', 'is_active', 'role_id', 'prefix', 'tfa_expiry', 'tfa'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at', 'tfa_expiry'];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The users that belong to the role.
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get all of the user's staff.
     * @return HasMany
     */
    public function staffs(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    /**
     * Check is a admin user
     * @return bool
     */
    public function isAdminUser()
    {
        return $this->role ? $this->role->access_level == 500 : false;
    }

    public function isRepUser()
    {
        return $this->role ? $this->role->access_level == 200 : false;
    }

    /**
     * Check is a active user
     * @return bool
     */
    public function isActive()
    {
        return $this->is_active == 'Yes';
    }

    /**
     * user's faces
     * @return HasMany
     */
    public function faceIds()
    {
        return $this->hasMany(FaceId::class);
    }

    /**
     * Get the audit log name
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'user';
    }

    /**
     * @param $password
     * @return bool
     * @throws OAuthServerException
     */
    public function validateForPassportPasswordGrant($password)
    {
        if (Hash::check($password, $this->getAuthPassword())) {
            //is user active?
            if (userCompany($this) && $this->isActive() && $this->isRepUser()) {
                $allocation = getRepAllocation(null, null, $this);
                $loggedInAllocation = $allocation->where('is_logged_in', 'Yes')->first();
                if ($loggedInAllocation) {
                    throw new OAuthServerException(
                        'You are already logged in another device.',
                        6,
                        'login_failed',
                        401
                    );
                }

                if (todayHandOver($this)) {
                    throw new OAuthServerException(
                        'Today allocated sales, handover processed already completed.',
                        6,
                        'login_failed',
                        401
                    );
                }

                if (todayHandOver($this)) {
                    throw new OAuthServerException(
                        'Today allocated sales, handover processed already completed.',
                        6,
                        'login_failed',
                        401
                    );
                }
                /**  check allocated customers */
                /** @var \Illuminate\Database\Eloquent\Collection $customers */
                $customers = getAllAllocatedCustomers($allocation, $this);
                if ($customers->count() == 0) {
                    throw new OAuthServerException(
                        'You don\'t have sales allocation for today, please contact your manager or administrator.',
                        6,
                        'login_failed',
                        401
                    );
                }
                AfterLogin($this);
                repLoggedInSuccess($allocation);
                return true;
            } else {
                throw new OAuthServerException(
                    'Unable to login your account, Please contact your administrator',
                    6,
                    'login_failed',
                    401
                );
            }
        }
    }
}
