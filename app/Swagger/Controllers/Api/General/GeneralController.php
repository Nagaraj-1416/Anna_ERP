<?php

namespace App\Swagger\Controllers\Api\Sales;


/**
 * Class GeneralController
 * @package App\Swagger\Controllers\Api\Common
 */
class GeneralController
{
    /**
     * @SWG\Get(
     *     path="/mata",
     *     summary="Get Mata data",
     *     tags={"General"},
     *     description="Get mata data . This API call is available to authenticated users.",
     *     operationId="mata",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Mata")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function mata()
    {

    }
}