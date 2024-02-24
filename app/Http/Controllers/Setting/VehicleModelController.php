<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\VehicleModelStoreRequest;
use App\Repositories\Settings\VehicleModelRepository;
use App\VehicleModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleModelController extends Controller
{
    /**
     * @var VehicleModelRepository
     */
    protected $vehicleModel;

    /**
     * VehicleModelController constructor.
     * @param VehicleModelRepository $vehicleModel
     */
    public function __construct(VehicleModelRepository $vehicleModel)
    {
        $this->vehicleModel = $vehicleModel;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        
        if (\request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $vehicles = VehicleModel::get();
            return response()->json($vehicles);
        }
        $this->authorize('index', $this->vehicleModel->getModel());
        $breadcrumb = $this->vehicleModel->breadcrumbs('index');
        return view('settings.vehicle.model.index', compact('breadcrumb'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function dataTableData(Request $request)
    {
        if (\request()->ajax()){
            return $this->vehicleModel->dataTable($request);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->vehicleModel->getModel());
        $breadcrumb = $this->vehicleModel->breadcrumbs('create');
        return view('settings.vehicle.model.create', compact('breadcrumb'));
    }

    /**
     * @param VehicleModelStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(VehicleModelStoreRequest $request)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            $vehicle =  $this->vehicleModel->save($request);
            return response()->json($vehicle);
        }
        $this->authorize('store', $this->vehicleModel->getModel());
        $this->vehicleModel->save($request);
        alert()->success('Vehicle model created successfully', 'Success')->persistent();
        return redirect()->route('setting.vehicle.model.index');
    }

    /**
     * @param VehicleModel $vehicleModel
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(VehicleModel $vehicleModel)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            return response()->json($vehicleModel);
        }
        $this->authorize('show', $this->vehicleModel->getModel());
        $breadcrumb = $this->vehicleModel->breadcrumbs('show', $vehicleModel);
        return view('settings.vehicle.model.show', compact('breadcrumb', 'vehicleModel'));
    }

    /**
     * @param VehicleModel $vehicleModel
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(VehicleModel $vehicleModel)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            return response()->json($vehicleModel);
        }
        $this->authorize('edit', $this->vehicleModel->getModel());
        $breadcrumb = $this->vehicleModel->breadcrumbs('edit', $vehicleModel);
        return view('settings.vehicle.model.edit', compact('breadcrumb', 'vehicleModel'));
    }

    /**
     * @param VehicleModelStoreRequest $request
     * @param VehicleModel $vehicleModel
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(VehicleModelStoreRequest $request, VehicleModel $vehicleModel)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            $vehicle = $this->vehicleModel->update($request, $vehicleModel);
            return response()->json($vehicle);
        }
        $this->authorize('update', $this->vehicleModel->getModel());
        $this->vehicleModel->update($request, $vehicleModel);
        alert()->success('Vehicle model updated successfully', 'Success')->persistent();
        return redirect()->route('setting.vehicle.model.index');
    }

    /**
     * @param VehicleModel $vehicleModel
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(VehicleModel $vehicleModel): JsonResponse
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $response = $this->vehicleModel->delete($vehicleModel);
            return response()->json($response);
      }
        $this->authorize('delete', $this->vehicleModel->getModel());
        $response = $this->vehicleModel->delete($vehicleModel);
        return response()->json($response);
    }
}
