<?php

namespace App\Repositories\Settings;

use App\Http\Requests\Setting\VehicleTypeStoreRequest;
use App\Repositories\BaseRepository;
use App\Vehicle;
use App\VehicleType;
use Illuminate\Http\Request;

/**
 * Class VehicleTypeRepository
 * @package App\Repositories\Settings
 */
class VehicleTypeRepository extends BaseRepository
{
    /**
     * VehicleRepository constructor.
     * @param VehicleType|null $vehicleType
     */
    public function __construct(VehicleType $vehicleType = null)
    {
        $this->setModel($vehicleType ?? new Vehicle());
    }

    public function dataTable(Request $request): array
    {
        $columns = ['name', 'is_active'];
        $searchingColumns = ['name', 'is_active'];
        $data = $this->getTableData($request, $columns, $searchingColumns);
        $data['data'] = array_map(function ($item) {
            //$item['name'] = '<a href="' . route('setting.vehicle.type.show', $item['id']) . '">'.$item['name'].'</a>';
            $item['action'] = "<div class=\"button-group\">";
            if (can('edit', $this->getModel())) {
                $item['action'] .= actionBtn('Edit', null, ['setting.vehicle.type.edit', [$item['id']]]);
            }
            if (can('delete', $this->getModel())) {
                $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-vehicle-type']);
            }
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    public function save(VehicleTypeStoreRequest $request)
    {
        $vehicleType = $this->model->fill($request->toArray());
        $vehicleType->save();
        return $vehicleType;
    }

    public function update($request, VehicleType $vehicleType)
    {
        $this->setModel($vehicleType);
        $this->model->update($request->toArray());
        return $vehicleType;
    }

    public function delete(VehicleType $vehicleType): array
    {
        if ($vehicleType->vehicles()->count() > 0) {
            return ['success' => false, 'message' => 'This vehicle type is associated with vehicles. \n Please disassociate the vehicle type from vehicles and then try delete.'];
        }
        $vehicleType->delete();
        return ['success' => true];
    }

    /**
     * @param string $method
     * @param VehicleType|null $vehicleType
     * @return array
     */
    public function breadcrumbs(string $method, VehicleType $vehicleType = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Vehicles', 'route' => 'setting.vehicle.index'],
                ['text' => 'Vehicle Types'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Vehicles', 'route' => 'setting.vehicle.index'],
                ['text' => 'Vehicle Types', 'route' => 'setting.vehicle.type.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Vehicles', 'route' => 'setting.vehicle.index'],
                ['text' => 'Vehicle Types', 'route' => 'setting.vehicle.type.index'],
                ['text' => $vehicleType->name ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Vehicles', 'route' => 'setting.vehicle.index'],
                ['text' => 'Vehicle Types', 'route' => 'setting.vehicle.type.index'],
                ['text' => $vehicleType->name ?? ''],
                ['text' => 'Edit'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}