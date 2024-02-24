<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\VehicleTypeStoreRequest;
use App\Repositories\Settings\VehicleTypeRepository;
use App\VehicleType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function Symfony\Component\Debug\Tests\testHeader;

class VehicleTypeController extends Controller
{
    /**
     * @var VehicleTypeRepository
     */
    protected $vehicleType;

    /**
     * VehicleTypeController constructor.
     * @param VehicleTypeRepository $vehicleType
     */
    public function __construct(VehicleTypeRepository $vehicleType)
    {
        $this->vehicleType = $vehicleType;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        if (\request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $vehicles = VehicleType::get();
            return response()->json($vehicles);
        }
        $this->authorize('index', $this->vehicleType->getModel());
        $breadcrumb = $this->vehicleType->breadcrumbs('index');
        return view('settings.vehicle.type.index', compact('breadcrumb'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function dataTableData(Request $request)
    {
        if (\request()->ajax()){
            return $this->vehicleType->dataTable($request);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->vehicleType->getModel());
        $breadcrumb = $this->vehicleType->breadcrumbs('create');
        return view('settings.vehicle.type.create', compact('breadcrumb'));
    }

    /**
     * @param VehicleTypeStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(VehicleTypeStoreRequest $request)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            $vehicle =  $this->vehicleType->save($request);
            return response()->json($vehicle);
        }
        $this->authorize('store', $this->vehicleType->getModel());
        $this->vehicleType->save($request);
        alert()->success('Vehicle type created successfully', 'Success')->persistent();
        return redirect()->route('setting.vehicle.type.index');
    }

    /**
     * @param VehicleType $vehicleType
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(VehicleType $vehicleType)
    {  
        if ( \request()->header('User-Agent') == 'Postman') {
        return response()->json($vehicleType);
    }
        $this->authorize('show', $this->vehicleType->getModel());
        $breadcrumb = $this->vehicleType->breadcrumbs('show', $vehicleType);
        return view('settings.vehicle.type.show', compact('breadcrumb', 'vehicleType'));
    }

    /**
     * @param VehicleType $vehicleType
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(VehicleType $vehicleType)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            return response()->json($vehicleType);
        }
        $this->authorize('edit', $this->vehicleType->getModel());
        $breadcrumb = $this->vehicleType->breadcrumbs('edit', $vehicleType);
        return view('settings.vehicle.type.edit', compact('breadcrumb', 'vehicleType'));
    }

    /**
     * @param VehicleTypeStoreRequest $request
     * @param VehicleType $vehicleType
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(VehicleTypeStoreRequest $request, VehicleType $vehicleType)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            $vehicle = $this->vehicleType->update($request, $vehicleType);
            return response()->json($vehicle);
        }
        $this->authorize('update', $this->vehicleType->getModel());
        $this->vehicleType->update($request, $vehicleType);
        alert()->success('Vehicle type updated successfully', 'Success')->persistent();
        return redirect()->route('setting.vehicle.type.index');
    }

    /**
     * @param VehicleType $vehicleType
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(VehicleType $vehicleType): JsonResponse
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $response = $this->vehicleType->delete($vehicleType);
            return response()->json($response);
      }
        $this->authorize('delete', $this->vehicleType->getModel());
        $response = $this->vehicleType->delete($vehicleType);
        return response()->json($response);
    }
}
