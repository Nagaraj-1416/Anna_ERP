<?php

namespace App\Repositories\Settings;

use App\Http\Requests\Setting\VehicleModelStoreRequest;
use App\Repositories\BaseRepository;
use App\VehicleModel;
use Illuminate\Http\Request;

/**
 * Class VehicleTypeRepository
 * @package App\Repositories\Settings
 */
class VehicleModelRepository extends BaseRepository
{
    /**
     * VehicleRepository constructor.
     * @param VehicleModel|null $vehicleModel
     */
    public function __construct(VehicleModel $vehicleModel = null)
    {
        $this->setModel($vehicleModel ?? new VehicleModel());
    }

    public function dataTable(Request $request): array
    {
        $columns = ['name', 'is_active'];
        $searchingColumns = ['name', 'is_active'];
        $relationColumns = [
            'make' => [
                [
                    'column' => 'name', 'as' => 'make_name'
                ]
            ]
        ];
        $data = $this->getTableData($request, $columns, $searchingColumns, $relationColumns);
        $data['data'] = array_map(function ($item) {
            //$item['name'] = '<a href="' . route('setting.vehicle.model.show', $item['id']) . '">'.$item['name'].'</a>';
            $item['action'] = "<div class=\"button-group\">";
            if (can('edit', $this->getModel())) {
                $item['action'] .= actionBtn('Edit', null, ['setting.vehicle.model.edit', [$item['id']]]);
            }
            if (can('edit', $this->getModel())) {
                $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-vehicle-model']);
            }
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    public function save(VehicleModelStoreRequest $request)
    {
        $vehicleModel = $this->model->fill($request->toArray());
        $vehicleModel->save();
        return $vehicleModel;
    }

    public function update($request, VehicleModel $vehicleModel)
    {
        $this->setModel($vehicleModel);
        $this->model->update($request->toArray());
        return $vehicleModel;
    }

    public function delete(VehicleModel $vehicleModel): array
    {
        if ($vehicleModel->vehicles()->count() > 0) {
            return ['success' => false, 'message' => 'This vehicle model is associated with vehicles. \n Please disassociate the vehicle model from vehicles and then try delete.'];
        }
        $vehicleModel->delete();
        return ['success' => true];
    }

    /**
     * @param string $method
     * @param VehicleModel|null $vehicleModel
     * @return array
     */
    public function breadcrumbs(string $method, VehicleModel $vehicleModel = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Vehicles', 'route' => 'setting.vehicle.index'],
                ['text' => 'Vehicle Models'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Vehicles', 'route' => 'setting.vehicle.index'],
                ['text' => 'Vehicle Models', 'route' => 'setting.vehicle.model.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Vehicles', 'route' => 'setting.vehicle.index'],
                ['text' => 'Vehicle Models', 'route' => 'setting.vehicle.model.index'],
                ['text' => $vehicleModel->name ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Vehicles', 'route' => 'setting.vehicle.index'],
                ['text' => 'Vehicle Models', 'route' => 'setting.vehicle.model.index'],
                ['text' => $vehicleModel->name ?? ''],
                ['text' => 'Edit'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}