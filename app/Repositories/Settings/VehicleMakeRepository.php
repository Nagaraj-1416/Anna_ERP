<?php

namespace App\Repositories\Settings;

use App\Http\Requests\Setting\VehicleMakeStoreRequest;
use App\Repositories\BaseRepository;
use App\VehicleMake;
use Illuminate\Http\Request;

/**
 * Class VehicleTypeRepository
 * @package App\Repositories\Settings
 */
class VehicleMakeRepository extends BaseRepository
{
    /**
     * VehicleRepository constructor.
     * @param VehicleMake|null $vehicleMake
     */
    public function __construct(VehicleMake $vehicleMake = null)
    {
        $this->setModel($vehicleMake ?? new VehicleMake());
    }

    public function dataTable(Request $request): array
    {
        $columns = ['name', 'is_active'];
        $searchingColumns = ['name', 'is_active'];
        $data = $this->getTableData($request, $columns, $searchingColumns);
        $data['data'] = array_map(function ($item) {
            //$item['name'] = '<a href="' . route('setting.vehicle.make.show', $item['id']) . '">'.$item['name'].'</a>';
            $item['action'] = "<div class=\"button-group\">";
            if (can('edit', $this->getModel())) {
                $item['action'] .= actionBtn('Edit', null, ['setting.vehicle.make.edit', [$item['id']]]);
            }
            if (can('delete', $this->getModel())) {
                $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-vehicle-make']);
            }
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    public function save(VehicleMakeStoreRequest $request)
    {
        $vehicleMake = $this->model->fill($request->toArray());
        $vehicleMake->save();
        return $vehicleMake;
    }

    public function update($request, VehicleMake $vehicleMake)
    {
        $this->setModel($vehicleMake);
        $this->model->update($request->toArray());
        return $vehicleMake;
    }

    public function delete(VehicleMake $vehicleMake): array
    {
        if ($vehicleMake->vehicles()->count() > 0) {
            return ['success' => false, 'message' => 'This vehicle type is associated with vehicles. \n Please disassociate the vehicle type from vehicles and then try delete.'];
        }
        $vehicleMake->delete();
        return ['success' => true];
    }

    /**
     * @param string $method
     * @param VehicleMake|null $vehicleMake
     * @return array
     */
    public function breadcrumbs(string $method, VehicleMake $vehicleMake = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Vehicles', 'route' => 'setting.vehicle.index'],
                ['text' => 'Vehicle Makes'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Vehicles', 'route' => 'setting.vehicle.index'],
                ['text' => 'Vehicle Makes', 'route' => 'setting.vehicle.make.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Vehicles', 'route' => 'setting.vehicle.index'],
                ['text' => 'Vehicle Makes', 'route' => 'setting.vehicle.make.index'],
                ['text' => $vehicleMake->name ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Vehicles', 'route' => 'setting.vehicle.index'],
                ['text' => 'Vehicle Makes', 'route' => 'setting.vehicle.make.index'],
                ['text' => $vehicleMake->name ?? ''],
                ['text' => 'Edit'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}