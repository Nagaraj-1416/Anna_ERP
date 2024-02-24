<?php

namespace App\Swagger\Controllers\Api\OAuth;

use App\Http\Controllers\Controller;

class TokenController extends Controller
{
    /**
     * @SWG\Post(path="/oauth/token",
     *   tags={"Get Token"},
     *   summary="Returns access token for authorized user",
     *   description="",
     *   operationId="getToken",
     *   produces={"application/xml", "application/json" ,"text/plain"},
     *   @SWG\Parameter(
     *     name="username",
     *     in="body",
     *     description="Username of the person to get access token",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *      example="example@gmail.com"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="body",
     *     description="Password of the user",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *      example="password123"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="grant_type",
     *     in="body",
     *     description="Site accessible method type (ex-password)",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *      example="password"
     *     )
     *   ),
     *
     *      @SWG\Parameter(
     *     name="client_id",
     *     in="body",
     *     description="Client ID which is generated for that user by using API client as passport install",
     *     required=true,
     *     @SWG\Schema(
     *       type="integer",
     *      example="2"
     *     )
     *   ),
     *
     *       @SWG\Parameter(
     *     name="client_secret",
     *     in="body",
     *     description="Client secret which is generated for that user by using API client (passport install)",
     *     required=true,
     *      @SWG\Schema(
     *       type="string",
     *      example="asd5as4badsa57asdbdasdd57asbd122"
     *     )
     *   ),
     *
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/OAuthToken")
     *   ),
     *   @SWG\Response(response=400, description="Bad request credentials"),
     *     @SWG\Response(response=401, description="Unauthorized user credentials"),
     *     @SWG\Response(response=403, description="Forbidden"),
     *     @SWG\Response(response=404, description="Not found"),
     *     @SWG\Response(response=405, description="Method not allowed"),
     *     @SWG\Response(response=408, description="Request Time out"),
     *     @SWG\Response(response=502, description="Bad Gateway"),
     *     @SWG\Response(response=503, description="Service unavailable"),
     *     @SWG\Response(response=504, description="Gateway time out"),
     *     @SWG\Response(response=505, description="HTTP version is not supported"),
     * )
     */
    public function index()
    {

    }
}
