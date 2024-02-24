<?php

namespace App\Swagger\Controllers\Api\OAuth;

use App\Http\Controllers\Controller;

class TFAController extends Controller
{
    /**
     * @SWG\Post(path="/tfa/verify",
     *   tags={"Face ID"},
     *   summary="Face id verification response",
     *   description="",
     *   operationId="FaceId",
     *   @SWG\Parameter(
     *         description="User's face image",
     *         in="body",
     *         name="image",
     *         required=true,
     *         type="string",
     *     @SWG\Schema(
     *       type="string",
     *       example="--"
     *     )
     *     ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/FaceIdVerify")
     *   ),
     *   @SWG\Response(response=400, description="Bad request credentials"),
     *     @SWG\Response(response=401, description="Unauthorized user's face"),
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
