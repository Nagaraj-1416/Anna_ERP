<?php

namespace App\Repositories\Settings;

use App\Http\Requests\Setting\{
    AssignStaffRequest, ProductionUnitCreateRequest
};
use App\Repositories\BaseRepository;
use App\{
    ProductionUnit, Staff, Company
};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class ProductionUnitRepository
 * @package App\Repositories\Settings
 */
class ProductionUnitRepository extends BaseRepository
{
    /**
     * ProductionUnitRepository constructor.
     * @param ProductionUnit|null $productionUnit
     */
    public function __construct(ProductionUnit $productionUnit = null)
    {
        $this->setModel($productionUnit ?? new ProductionUnit());
        $this->setCodePrefix('PRU');
    }

    /**
     * Get data to data table
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['code', 'name', 'phone', 'fax', 'mobile', 'email', 'is_active', 'company_id'];
        $searchingColumns = ['code', 'name', 'phone', 'fax', 'mobile', 'email', 'is_active', 'company_id'];
        $relationColumns = [
            'company' => [
                [
                    'column' => 'name', 'as' => 'company_name'
                ]
            ]
        ];
        $data = $this->getTableData($request, $columns, $searchingColumns, $relationColumns);
        $data['data'] = array_map(function ($item) {
            $item['code'] = '<a href="' . route('setting.production.unit.show', $item['id']) . '">' . $item['code'] . '</a>';
            $item['action'] = "<div class=\"button-group\">";
            if(can('show', $this->getModel())){
                $item['action'] .= actionBtn('Show', null, ['setting.production.unit.show', [$item['id']]], ['class' => 'btn-success']);
            }
            if(can('edit', $this->getModel())){
                $item['action'] .= actionBtn('Edit', null, ['setting.production.unit.edit', [$item['id']]]);
            }
            if(can('delete', $this->getModel())){
                $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-productionUnit']);
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
        $units = ProductionUnit::orderBy('created_at', 'desc')->with('company', 'staff');
        if ($search) {
            $units->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', '%' . $search . '%')
                    ->orWhere('name', 'LIKE', '%' . $search . '%');
            });
        }

        switch ($filter) {
            case 'Active':
                $units->where('is_active', 'Yes');
                break;
            case 'Inactive':
                $units->where('is_active', 'No');
                break;
            case 'recentlyCreated':
                $units->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $units->where('updated_at', '>', $lastWeek);
                break;
        }

        return $units->paginate(12)->toArray();
    }

    public function save(ProductionUnitCreateRequest $request)
    {
        $request->merge(['code' => $this->getCode()]);
        $productionUnit = $this->model->fill($request->toArray());
        $productionUnit->save();
        return $productionUnit;
    }

    /**
     * @param ProductionUnitCreateRequest $request
     * @param ProductionUnit $productionUnit
     * @return ProductionUnit
     */
    public function update(ProductionUnitCreateRequest $request, ProductionUnit $productionUnit)
    {
        $request->merge(['code' => $productionUnit->code]);
        $this->setModel($productionUnit);
        $this->model->update($request->toArray());
        return $productionUnit;
    }

    /**
     * @param ProductionUnit $productionUnit
     * @return array
     * @throws \Exception
     */
    public function delete(ProductionUnit $productionUnit): array
    {
        $productionUnit->delete();
        return ['success' => true];
    }

    /**
     * Assign staff to production unit
     * @param AssignStaffRequest $request
     * @return Model
     */
    public function assignStaff(AssignStaffRequest $request): Model
    {
        $staffIds = $request->input('staff');
        $staffIds = explode(',', $staffIds);
        $this->model->staff()->attach($staffIds);
        return $this->model;
    }

    /**
     * Search staff drop down
     * @param null $q
     * @return array
     */
    public function searchStaff($q = null): array
    {
        $assignedStaff = $this->model->staff->pluck('id')->toArray();
        /** @var Company $company */
        $company = $this->model->company;
        if (!$q) {
            $staff = $company->staff()->whereNotIn('id', $assignedStaff)->get(['id', 'short_name'])->toArray();
        } else {
            $staff = $company->staff()->whereNotIn('id', $assignedStaff)
                ->where(function ($query) use ($q) {
                    $query->where('short_name', 'LIKE', '%' . $q . '%')
                        ->orWhere('first_name', 'LIKE', '%' . $q . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $q . '%');
                })
                ->get(['id', 'short_name'])
                ->toArray();
        }
        // mapping the data
        $staff = array_map(function ($obj) {
            return ["name" => $obj['short_name'], "value" => $obj['id']];
        }, $staff);
        return ["success" => true, "results" => $staff];
    }


    /**
     * Remove staff from production unit
     * @param Staff $staff
     * @return array
     */
    public function removeStaff(Staff $staff): array
    {
        $this->model->staff()->detach($staff->id);
        return ['success' => true, 'message' => 'Staff removed successfully!'];
    }


    /**
     * Get the breadcrumbs of the production unit module
     * @param string $method
     * @param ProductionUnit|null $productionUnit
     * @return array|mixed
     */
    public function breadcrumbs(string $method, ProductionUnit $productionUnit = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Production Units'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Production Units', 'route' => 'setting.production.unit.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Production Units', 'route' => 'setting.production.unit.index'],
                ['text' => $productionUnit->name ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Production Units', 'route' => 'setting.production.unit.index'],
                ['text' => $productionUnit->name ?? ''],
                ['text' => 'Edit'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}