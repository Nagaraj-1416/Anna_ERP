<?php

namespace App\Repositories\Settings;

use App\Http\Requests\Setting\{
    AssignProductRequest, AssignStaffRequest, SalesLocationStoreRequest
};
use App\Repositories\BaseRepository;
use App\{
    SalesLocation, Company, Staff
};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class SalesLocationRepository
 * @package App\Repositories\Settings
 */
class SalesLocationRepository extends BaseRepository
{
    /**
     * SalesLocationRepository constructor.
     * @param SalesLocation|null $salesLocation
     */
    public function __construct(SalesLocation $salesLocation = null)
    {
        $this->setModel($salesLocation ?? new SalesLocation());
        $this->setCodePrefix('SL');
    }

    /**
     * Get data to data table
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['code', 'name', 'phone', 'fax', 'mobile', 'email', 'notes', 'company_id', 'is_active', 'type'];
        $searchingColumns = ['code', 'name', 'phone', 'fax', 'mobile', 'email', 'notes', 'company_id', 'is_active', 'type'];
        $relationColumns = [
            'company' => [
                [
                    'column' => 'name', 'as' => 'company_name'
                ]
            ]
        ];
        $data = $this->getTableData($request, $columns, $searchingColumns, $relationColumns);
        $data['data'] = array_map(function ($item) {
            $item['code'] = '<a href="' . route('setting.sales.location.show', $item['id']) . '">' . $item['code'] . '</a>';
            $item['action'] = "<div class=\"button-group\">";
            if (can('show', $this->getModel())) {
                $item['action'] .= actionBtn('Show', null, ['setting.sales.location.show', [$item['id']]], ['class' => 'btn-success']);
            }
            if (can('edit', $this->getModel())) {
                $item['action'] .= actionBtn('Edit', null, ['setting.sales.location.edit', [$item['id']]]);
            }
            if (can('delete', $this->getModel())) {
                $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-sales-location']);
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
        $locations = SalesLocation::orderBy('created_at', 'desc')->with('company');
        if ($search) {
            $locations->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', '%' . $search . '%')
                    ->orWhere('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('phone', 'LIKE', '%' . $search . '%')
                    ->orWhere('fax', 'LIKE', '%' . $search . '%')
                    ->orWhere('mobile', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%')
                    ->orwhere(function ($query) use ($search) {
                        $query->whereHas('company', function ($q) use ($search) {
                            $q->where('name', 'LIKE', '%' . $search . '%');
                        });
                    })
                    ->orwhere(function ($query) use ($search) {
                        $query->whereHas('vehicle', function ($q) use ($search) {
                            $q->where('vehicle_no', 'LIKE', '%' . $search . '%');
                        });
                    });
            });
        }

        switch ($filter) {
            case 'Active':
                $locations->where('is_active', 'Yes');
                break;
            case 'Inactive':
                $locations->where('is_active', 'No');
                break;
            case 'recentlyCreated':
                $locations->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $locations->where('updated_at', '>', $lastWeek);
                break;
        }

        return $locations->paginate(12)->toArray();
    }

    /**
     * Store new sales location
     * @param SalesLocationStoreRequest $request
     * @return Model
     */
    public function save(SalesLocationStoreRequest $request): Model
    {
        $request->merge(['code' => $this->getCode()]);
        $salesLocation = $this->model->fill($request->toArray());
        $salesLocation->save();
        return $salesLocation;
    }

    /**
     * @param SalesLocationStoreRequest $request
     * @param SalesLocation $salesLocation
     * @return SalesLocation
     */
    public function update(SalesLocationStoreRequest $request, SalesLocation $salesLocation)
    {
        $request->merge(['code' => $salesLocation->code]);
        $this->setModel($salesLocation);
        $this->model->update($request->toArray());
        return $salesLocation;
    }

    /**
     * Delete sales location
     * @param SalesLocation $salesLocation
     * @return array
     * @throws \Exception
     */
    public function delete(SalesLocation $salesLocation): array
    {
        $salesLocation->delete();
        return ['success' => true];
    }


    /**
     * Assign staff to Sales location
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
     * Search staff for drop down
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
     * Remove staff from sales Location
     * @param Staff $staff
     * @return array
     */
    public function removeStaff(Staff $staff): array
    {
        $this->model->staff()->detach($staff->id);
        return ['success' => true, 'message' => 'Staff removed successfully!'];
    }


    /**
     * @param string $method
     * @param SalesLocation|null $salesLocation
     * @return array
     */
    public function breadcrumbs(string $method, SalesLocation $salesLocation = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Sales Locations'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Sales Locations', 'route' => 'setting.sales.location.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Sales Locations', 'route' => 'setting.sales.location.index'],
                ['text' => $salesLocation->name ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Sales Locations', 'route' => 'setting.sales.location.index'],
                ['text' => $salesLocation->name ?? ''],
                ['text' => 'Edit'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    /**
     * @param AssignProductRequest $request
     * @return Model
     */
    public function assignProduct(AssignProductRequest $request)
    {
        $products = $request->input('products');
        $this->model->products()->attach($products);
        return $this->model;
    }
}