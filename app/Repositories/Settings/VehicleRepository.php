<?php

namespace App\Repositories\Settings;

use App\Http\Requests\Setting\VehicleRenewalRequest;
use App\Http\Requests\Setting\VehicleStoreRequest;
use App\Repositories\BaseRepository;
use App\Vehicle;
use App\VehicleRenewal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class VehicleRepository
 * @package App\Repositories\Settings
 */
class VehicleRepository extends BaseRepository
{
    protected $imagePath = 'vehicle-images/';

    /**
     * VehicleRepository constructor.
     * @param Vehicle|null $vehicle
     */
    public function __construct(Vehicle $vehicle = null)
    {
        $this->setModel($vehicle ?? new Vehicle());
    }

    /**
     * Get data to data table
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['vehicle_no', 'engine_no', 'chassis_no', 'reg_date', 'year', 'color', 'fuel_type', 'notes', 'is_active'];
        $searchingColumns = ['vehicle_no', 'engine_no', 'chassis_no', 'reg_date', 'year', 'color', 'fuel_type', 'notes', 'is_active'];
        $relationColumns = [
            'type' => [
                [
                    'column' => 'name', 'as' => 'type_name'
                ]
            ],
            'make' => [
                [
                    'column' => 'name', 'as' => 'make_name'
                ]
            ],
            'model' => [
                [
                    'column' => 'name', 'as' => 'model_name'
                ]
            ],
            'company' => [
                [
                    'column' => 'name', 'as' => 'company_name'
                ]
            ]
        ];
        $data = $this->getTableData($request, $columns, $searchingColumns, $relationColumns);
        $data['data'] = array_map(function ($item) {
            $item['vehicle_no'] = '<a href="' . route('setting.vehicle.show', $item['id']) . '">' . $item['vehicle_no'] . '</a>';
            $item['action'] = "<div class=\"button-group\">";
            if (can('show', $this->getModel())) {
                $item['action'] .= actionBtn('Show', null, ['setting.vehicle.show', [$item['id']]], ['class' => 'btn-success']);
            }
            if (can('edit', $this->getModel())) {
                $item['action'] .= actionBtn('Edit', null, ['setting.vehicle.edit', [$item['id']]]);
            }
            if (can('delete', $this->getModel())) {
                $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-vehicle']);
            }
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    public function grid()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $lastWeek = carbon()->subWeek();
        $vehicles = Vehicle::orderBy('created_at', 'desc')->with('company');
        if ($search) {
            $vehicles->where(function ($q) use ($search) {
                $q->where('vehicle_no', 'LIKE', '%' . $search . '%')
                    ->orWhere('engine_no', 'LIKE', '%' . $search . '%')
                    ->orWhere('category', 'LIKE', '%' . $search . '%')
                    ->orWhere('chassis_no', 'LIKE', '%' . $search . '%');
            });
        }

        switch ($filter) {
            case 'Active':
                $vehicles->where('is_active', 'Yes');
                break;
            case 'Inactive':
                $vehicles->where('is_active', 'No');
                break;
            case 'recentlyCreated':
                $vehicles->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $vehicles->where('updated_at', '>', $lastWeek);
                break;
        }

        return $vehicles->paginate(12)->toArray();
    }

    public function save(VehicleStoreRequest $request)
    {
        $vehicle = $this->model->fill($request->toArray());
        $vehicle->save();
        $vehicleImg = $request->file('image');
        if ($vehicleImg) {
            $staffImgType = $vehicleImg->getClientOriginalExtension();
            $vehicleImgName = $vehicle->getAttribute('vehicle_no') . '.' . $staffImgType;
            Storage::put($this->imagePath . $vehicleImgName, file_get_contents($vehicleImg));
            $vehicle->setAttribute('image', $vehicleImgName);
            $vehicle->save();
        }

        return $vehicle;
    }

    /**
     * @param VehicleStoreRequest $request
     * @param Vehicle $vehicle
     * @return Vehicle
     */
    public function update(VehicleStoreRequest $request, Vehicle $vehicle)
    {
        $this->setModel($vehicle);
        $this->model->update($request->toArray());

        $vehicleImg = $request->file('image');
        if ($vehicleImg) {
            $staffImgType = $vehicleImg->getClientOriginalExtension();
            $vehicleImgName = $vehicle->getAttribute('id') . '.' . $staffImgType;
            Storage::put($this->imagePath . $vehicleImgName, file_get_contents($vehicleImg));
            $vehicle->setAttribute('image', $vehicleImgName);
            $vehicle->save();
        }
        return $vehicle;
    }

    /**
     * @param Vehicle $vehicle
     * @return array
     * @throws \Exception
     */
    public function delete(Vehicle $vehicle): array
    {
        $vehicle->delete();
        return ['success' => true];
    }

    /**
     * @param string $method
     * @param Vehicle|null $vehicle
     * @return array
     */
    public function breadcrumbs(string $method, Vehicle $vehicle = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Vehicles'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Vehicles', 'route' => 'setting.vehicle.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Vehicles', 'route' => 'setting.vehicle.index'],
                ['text' => $vehicle->vehicle_no ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Vehicles', 'route' => 'setting.vehicle.index'],
                ['text' => $vehicle->vehicle_no ?? ''],
                ['text' => 'Edit'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    /**
     * @return string
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * @param VehicleRenewalRequest $request
     * @param Vehicle $vehicle
     * @return VehicleRenewal
     */
    public function addRenewal(VehicleRenewalRequest $request, Vehicle $vehicle)
    {
        $renewal = new VehicleRenewal();
        $renewal->vehicle_id = $vehicle->id;
        $renewal->type = $request->input('type');
        $renewal->date = $request->input('date');
        $renewal->save();
        return $renewal;
    }
}