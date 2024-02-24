<?php

namespace App\Repositories\Settings;

use App\Http\Requests\Setting\{
    AssignStaffRequest, StoreCreateRequest
};
use App\Repositories\BaseRepository;
use App\{ProductionUnit, Staff, Store, Company};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class StoreRepository
 * @package App\Repositories\Settings
 */
class StoreRepository extends BaseRepository
{
    /**
     * StoreRepository constructor.
     * @param Store|null $store
     */
    public function __construct(Store $store = null)
    {
        $this->setModel($store ?? new Store());
        $this->setCodePrefix('STR');
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
            $item['code'] = '<a href="' . route('setting.store.show', $item['id']) . '">' . $item['code'] . '</a>';
            $item['action'] = "<div class=\"button-group\">";
            if (can('show', $this->getModel())) {
                $item['action'] .= actionBtn('Show', null, ['setting.store.show', [$item['id']]], ['class' => 'btn-success']);
            }
            if (can('edit', $this->getModel())) {
                $item['action'] .= actionBtn('Edit', null, ['setting.store.edit', [$item['id']]]);
            }
            if (can('delete', $this->getModel())) {
                $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-store']);
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
        $stores = Store::orderBy('created_at', 'desc')->with('company', 'staff');
        if ($search) {
            $stores->where(function ($q) use ($search) {
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
                    });
            });
        }

        switch ($filter) {
            case 'Active':
                $stores->where('is_active', 'Yes');
                break;
            case 'Inactive':
                $stores->where('is_active', 'No');
                break;
            case 'recentlyCreated':
                $stores->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $stores->where('updated_at', '>', $lastWeek);
                break;
        }

        return $stores->paginate(12)->toArray();
    }

    /**
     * Store news store
     * @param StoreCreateRequest $request
     * @return Model
     */
    public function save(StoreCreateRequest $request): Model
    {
        $request->merge(['code' => $this->getCode()]);
        $store = $this->model->fill($request->toArray());
        $store->save();
        return $store;
    }

    /**
     * @param StoreCreateRequest $request
     * @param Store $store
     * @return Store
     */
    public function update(StoreCreateRequest $request, Store $store): Store
    {
        $request->merge(['code' => $store->code]);
        $this->setModel($store);
        $this->model->update($request->toArray());
        return $store;
    }

    /**
     * @param Store $store
     * @return array
     * @throws \Exception
     */
    public function delete(Store $store): array
    {
        $store->delete();
        return ['success' => true];
    }


    /**
     * Assign staff to store
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
     * Remove staff from store
     * @param Staff $staff
     * @return array
     */
    public function removeStaff(Staff $staff): array
    {
        $this->model->staff()->detach($staff->id);
        return ['success' => true, 'message' => 'Staff removed successfully!'];
    }

    public function createDamageStore(Company $company)
    {
        $store = new Store();
        $store->setAttribute('code', $this->getCode());
        $store->setAttribute('name', 'Damaged Store - '.$company->getAttribute('name'));
        $store->setAttribute('phone', $company->getAttribute('phone'));
        $store->setAttribute('fax', $company->getAttribute('fax'));
        $store->setAttribute('mobile', $company->getAttribute('mobile'));
        $store->setAttribute('email', $company->getAttribute('email'));
        $store->setAttribute('notes', 'Damaged store of '.$company->getAttribute('name'));
        $store->setAttribute('company_id', $company->getAttribute('id'));
        $store->setAttribute('type', 'Damage');
        $store->save();

        return $store;
    }

    public function createProductionStore(ProductionUnit $unit)
    {
        $store = new Store();
        $store->setAttribute('code', $this->getCode());
        $store->setAttribute('name', 'Production Store - '.$unit->getAttribute('name'));
        $store->setAttribute('phone', $unit->getAttribute('phone'));
        $store->setAttribute('fax', $unit->getAttribute('fax'));
        $store->setAttribute('mobile', $unit->getAttribute('mobile'));
        $store->setAttribute('email', $unit->getAttribute('email'));
        $store->setAttribute('notes', 'Production store of '.$unit->getAttribute('name'));
        $store->setAttribute('company_id', $unit->getAttribute('company_id'));
        $store->setAttribute('type', 'Production');
        $store->setAttribute('storeable_id', $unit->getAttribute('id'));
        $store->setAttribute('storeable_type', 'App\ProductionUnit');
        $store->save();

        return $store;
    }

    public function createReturnStore(ProductionUnit $unit)
    {
        $store = new Store();
        $store->setAttribute('code', $this->getCode());
        $store->setAttribute('name', 'Return Store - '.$unit->getAttribute('name'));
        $store->setAttribute('phone', $unit->getAttribute('phone'));
        $store->setAttribute('fax', $unit->getAttribute('fax'));
        $store->setAttribute('mobile', $unit->getAttribute('mobile'));
        $store->setAttribute('email', $unit->getAttribute('email'));
        $store->setAttribute('notes', 'Production store of '.$unit->getAttribute('name'));
        $store->setAttribute('company_id', $unit->getAttribute('company_id'));
        $store->setAttribute('type', 'Return');
        $store->setAttribute('storeable_id', $unit->getAttribute('id'));
        $store->setAttribute('storeable_type', 'App\ProductionUnit');
        $store->save();

        return $store;
    }

    /**
     * Get the breadcrumbs of the department module
     * @param string $method
     * @param Store|null $store
     * @return array|mixed
     */
    public function breadcrumbs(string $method, Store $store = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Stores'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Stores', 'route' => 'setting.store.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Stores', 'route' => 'setting.store.index'],
                ['text' => $store->name ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Stores', 'route' => 'setting.store.index'],
                ['text' => $store->name ?? ''],
                ['text' => 'Edit'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}