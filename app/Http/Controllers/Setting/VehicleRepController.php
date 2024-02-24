<?php

namespace App\Http\Controllers\Setting;

use App\Http\Requests\Setting\VehicleRepRequest;
use App\Rep;
use App\Repositories\Settings\VehicleRepRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VehicleRepController extends Controller
{
    /**
     * @var VehicleRepRepository
     */
    protected $vehicleRep;

    /**
     * VehicleRepController constructor.
     * @param VehicleRepRepository $vehicleRep
     */
    public function __construct(VehicleRepRepository $vehicleRep)
    {
        $this->vehicleRep = $vehicleRep;
    }

    /**
     * @param $model
     * @param $modelId
     * @param $searchModal
     * @param $relation
     * @param $column
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($model, $modelId, $searchModal, $relation, $column, $q=null)
    {
       $response = $this->vehicleRep->searchData($model, $modelId, $searchModal, $relation, $column, $q);
        return response()->json($response);
    }

    /**
     * @param $model
     * @param $modelId
     * @param $relation
     * @param VehicleRepRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function attach($model, $modelId, $relation, VehicleRepRequest $request)
    {
        $this->vehicleRep->attach($model, $modelId, $relation, $request);
        return redirect()->back();
    }

    /**
     * @param $method
     * @param Rep $modal
     * @param $vehicle
     * @param VehicleRepRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function statusChange($method, Rep $modal, $vehicle, VehicleRepRequest $request)
    {
        $this->vehicleRep->statusChange($method, $modal, $vehicle, $request);
        return redirect()->back();
    }

    /**
     * @param $method
     * @param Rep $rep
     * @param VehicleRepRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function vehicleStatusChange($method, Rep $rep, VehicleRepRequest $request)
    {
        $this->vehicleRep->vehicleStatusChange($method, $rep, $request);
        return redirect()->back();
    }

}
