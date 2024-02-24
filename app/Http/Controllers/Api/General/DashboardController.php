<?php

namespace App\Http\Controllers\API\General;


use App\Http\Controllers\Api\ApiController;
use App\Repositories\General\DashboardRepository;
use Illuminate\Http\Request;


/**
 * Class UserController
 * @package App\Http\Controllers\API\General
 */
class DashboardController extends ApiController
{
    protected $dashboard;
    public function __construct(DashboardRepository $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function index()
    {
        return $this->dashboard->apiIndex();
    }

}
