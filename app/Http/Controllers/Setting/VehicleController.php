<?php

namespace App\Http\Controllers\Setting;

use App\Http\Requests\Setting\VehicleRenewalRequest;
use App\Http\Requests\Setting\VehicleStoreRequest;
use App\Repositories\Settings\VehicleRepository;
use App\Vehicle;
use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    /**
     * @var VehicleRepository
     */
    protected $vehicle;
    protected $imagePath;

    /**
     * VehicleController constructor.
     * @param VehicleRepository $vehicle
     */
    public function __construct(VehicleRepository $vehicle)
    {
        $this->vehicle = $vehicle;
        $this->imagePath = $vehicle->getImagePath();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        if (\request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $vehicles = $this->vehicle->grid();
            return response()->json($vehicles);
        }
        $this->authorize('index', $this->vehicle->getModel());
        // if (\request()->ajax()) {
        //     $vehicles = $this->vehicle->grid();
        //     return response()->json($vehicles);
        // }
        $breadcrumb = $this->vehicle->breadcrumbs('index');
        return view('settings.vehicle.index', compact('breadcrumb'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function dataTableData(Request $request)
    {
        if (\request()->ajax()) {
            return $this->vehicle->dataTable($request);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->vehicle->getModel());
        $breadcrumb = $this->vehicle->breadcrumbs('create');
        return view('settings.vehicle.create', compact('breadcrumb'));
    }

    /**
     * @param VehicleStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(VehicleStoreRequest $request)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            $vehicle=$this->vehicle->save($request);
            return response()->json($vehicle);
        }
        $this->authorize('store', $this->vehicle->getModel());
        $this->vehicle->save($request);
        alert()->success('Vehicle created successfully', 'Success')->persistent();
        return redirect()->route('setting.vehicle.index');
    }

    /**
     * @param Vehicle $vehicle
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Vehicle $vehicle)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            return response()->json($vehicle);
        }
        $this->authorize('show', $this->vehicle->getModel());
        $breadcrumb = $this->vehicle->breadcrumbs('show', $vehicle);
        return view('settings.vehicle.show', compact('breadcrumb', 'vehicle'));
    }

    /**
     * @param Vehicle $vehicle
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Vehicle $vehicle)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            $vehicle->company_id = $vehicle->company ? $vehicle->company->id : '';
            $vehicle->type_id = $vehicle->type ? $vehicle->type->id : '';
            $vehicle->make_id = $vehicle->make ? $vehicle->make->id : '';
            $vehicle->model_id = $vehicle->model ? $vehicle->model->id : '';
            return response()->json($vehicle);
        }
        $this->authorize('edit', $this->vehicle->getModel());
        $breadcrumb = $this->vehicle->breadcrumbs('edit', $vehicle);
        $vehicle->company_id = $vehicle->company ? $vehicle->company->id : '';
        $vehicle->type_id = $vehicle->type ? $vehicle->type->id : '';
        $vehicle->make_id = $vehicle->make ? $vehicle->make->id : '';
        $vehicle->model_id = $vehicle->model ? $vehicle->model->id : '';
        return view('settings.vehicle.edit', compact('breadcrumb', 'vehicle'));
    }

    /**
     * @param VehicleStoreRequest $request
     * @param Vehicle $vehicle
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(VehicleStoreRequest $request, Vehicle $vehicle)
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
         $resp =   $this->vehicle->update($request, $vehicle);
            return response()->json($resp);
        }
        $this->authorize('update', $this->vehicle->getModel());
        $this->vehicle->update($request, $vehicle);
        alert()->success('Vehicle updated successfully', 'Success')->persistent();
        return redirect()->route('setting.vehicle.index');
    }

    /**
     * @param Vehicle $vehicle
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(Vehicle $vehicle): JsonResponse
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $response = $this->vehicle->delete($vehicle);
            return response()->json($response);
      }
        $this->authorize('delete', $this->vehicle->getModel());
        $response = $this->vehicle->delete($vehicle);
        return response()->json($response);
    }

    /**
     * @param null $q
     * @return JsonResponse
     */
    public function search($q = null): JsonResponse
    {
        $response = $this->vehicle->search($q, 'vehicle_no', ['vehicle_no']);
        return response()->json($response);
    }

    /**
     * @param Vehicle $vehicle
     * @return mixed
     */
    public function getImage(Vehicle $vehicle)
    {
        if ($vehicle->getAttribute('image')) {
            $imagePath = Storage::get($this->imagePath . $vehicle->getAttribute('image'));
        } else {
            $imagePath = Storage::get('data/default.png');
        }
        return response($imagePath)->header('Content-Type', 'image/jpg');
    }

    /**
     * @param VehicleRenewalRequest $request
     * @param Vehicle $vehicle
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addRenewal(VehicleRenewalRequest $request, Vehicle $vehicle)
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $response =  $this->vehicle->addRenewal($request, $vehicle);
            return response()->json($response);
      }
        $this->vehicle->addRenewal($request, $vehicle);
        alert()->success('Vehicle Renewal created successfully', 'Success')->persistent();
        return redirect()->route('setting.vehicle.show', [$vehicle]);
    }
}
