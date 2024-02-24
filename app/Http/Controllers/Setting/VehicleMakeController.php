<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\VehicleMakeStoreRequest;
use App\Repositories\Settings\VehicleMakeRepository;
use App\VehicleMake;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleMakeController extends Controller
{
    /**
     * @var VehicleMakeRepository
     */
    protected $vehicleMake;

    /**
     * VehicleMakeController constructor.
     * @param VehicleMakeRepository $vehicleMake
     */
    public function __construct(VehicleMakeRepository $vehicleMake)
    {
        $this->vehicleMake = $vehicleMake;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        if (\request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $vehicles = VehicleMake::get();
            return response()->json($vehicles);
        }
        $this->authorize('index', $this->vehicleMake->getModel());
        $breadcrumb = $this->vehicleMake->breadcrumbs('index');
        return view('settings.vehicle.make.index', compact('breadcrumb'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function dataTableData(Request $request)
    {
        if (\request()->ajax()){
            return $this->vehicleMake->dataTable($request);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->vehicleMake->getModel());
        $breadcrumb = $this->vehicleMake->breadcrumbs('create');
        return view('settings.vehicle.make.create', compact('breadcrumb'));
    }

    /**
     * @param VehicleMakeStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(VehicleMakeStoreRequest $request)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            $vehicle =   $this->vehicleMake->save($request);
            return response()->json($vehicle);
        }
        $this->authorize('store', $this->vehicleMake->getModel());
        $this->vehicleMake->save($request);
        alert()->success('Vehicle make created successfully', 'Success')->persistent();
        return redirect()->route('setting.vehicle.make.index');
    }

    /**
     * @param VehicleMake $vehicleMake
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(VehicleMake $vehicleMake)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            return response()->json($vehicleMake);
        }
        $this->authorize('show', $this->vehicleMake->getModel());
        $breadcrumb = $this->vehicleMake->breadcrumbs('show', $vehicleMake);
        return view('settings.vehicle.make.show', compact('breadcrumb', 'vehicleMake'));
    }

    /**
     * @param VehicleMake $vehicleMake
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(VehicleMake $vehicleMake)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            return response()->json($vehicleMake);
        }
        $this->authorize('edit', $this->vehicleMake->getModel());
        $breadcrumb = $this->vehicleMake->breadcrumbs('edit', $vehicleMake);
        return view('settings.vehicle.make.edit', compact('breadcrumb', 'vehicleMake'));
    }

    /**
     * @param VehicleMakeStoreRequest $request
     * @param VehicleMake $vehicleMake
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(VehicleMakeStoreRequest $request, VehicleMake $vehicleMake)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            $vehicle = $this->vehicleMake->update($request, $vehicleMake);
            return response()->json($vehicle);
        }
        $this->authorize('update', $this->vehicleMake->getModel());
        $this->vehicleMake->update($request, $vehicleMake);
        alert()->success('Vehicle make updated successfully', 'Success')->persistent();
        return redirect()->route('setting.vehicle.make.index');
    }

    /**
     * @param VehicleMake $vehicleMake
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(VehicleMake $vehicleMake): JsonResponse
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $response = $this->vehicleMake->delete($vehicleMake);
            return response()->json($response);
      }
        $this->authorize('delete', $this->vehicleMake->getModel());
        $response = $this->vehicleMake->delete($vehicleMake);
        return response()->json($response);
    }
}
