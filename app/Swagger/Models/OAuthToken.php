<?php

namespace App\Swagger\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * @SWG\Definition(type="object", @SWG\Xml(name="OAuthToken"))
 */
class OAuthToken extends Model
{
    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $token_type;

    /**
     * @SWG\Property(format="int64")
     * @var int
     */
    public $expires_in;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $access_token;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $refresh_token;

}
