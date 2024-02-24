<?php

namespace App\Repositories\Settings;

use App\Company;
use App\Http\Requests\Setting\AssignStaffRequest;
use App\Http\Requests\Setting\DepartmentStoreRequest;
use App\Repositories\BaseRepository;
use App\Department;
use App\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class CompanyRepository
 * @package App\Repositories\Settings
 */
class DepartmentRepository extends BaseRepository
{
    /**
     * DepartmentRepository constructor.
     * @param Department|null $department
     */
    public function __construct(Department $department = null)
    {
        $this->setModel($department ?? new Department());
        $this->setCodePrefix('DEP');
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
            $item['code'] = '<a href="' . route('setting.department.show', $item['id']) . '">' . $item['code'] . '</a>';
            $item['action'] = "<div class=\"button-group\">";
            if (can('show', $this->getModel())) {
                $item['action'] .= actionBtn('Show', null, ['setting.department.show', [$item['id']]], ['class' => 'btn-success']);
            }
            if (can('edit', $this->getModel())) {
                $item['action'] .= actionBtn('Edit', null, ['setting.department.edit', [$item['id']]]);
            }
            if (can('delete', $this->getModel())) {
                $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-department']);
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
        $departments = Department::orderBy('created_at', 'desc')->with('company', 'staff');
        if ($search) {
            $departments->where(function ($q) use ($search) {
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
                $departments->where('is_active', 'Yes');
                break;
            case 'Inactive':
                $departments->where('is_active', 'No');
                break;
            case 'recentlyCreated':
                $departments->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $departments->where('updated_at', '>', $lastWeek);
                break;
        }

        return $departments->paginate(12)->toArray();
    }

    public function save(DepartmentStoreRequest $request)
    {
        $request->merge(['code' => $this->getCode()]);
        $department = $this->model->fill($request->toArray());
        $department->save();
        return $department;
    }

    /**
     * @param DepartmentStoreRequest $request
     * @param Department $department
     * @return Department
     */
    public function update(DepartmentStoreRequest $request, Department $department)
    {
        $request->merge(['code' => $department->code]);
        $this->setModel($department);
        $this->model->update($request->toArray());
        return $department;
    }

    /**
     * @param Department $department
     * @return array
     * @throws \Exception
     */
    public function delete(Department $department): array
    {
        $department->delete();
        return ['success' => true];
    }

    /**
     * Assign staff to department
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
     * Remove staff from department
     * @param Staff $staff
     * @return array
     */
    public function removeStaff(Staff $staff): array
    {
        $this->model->staff()->detach($staff->id);
        return ['success' => true, 'message' => 'Staff removed successfully!'];
    }

    /**
     * Get the breadcrumbs of the department module
     * @param string $method
     * @param Department|null $department
     * @return array|mixed
     */
    public function breadcrumbs(string $method, Department $department = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Departments'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Departments', 'route' => 'setting.department.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Departments', 'route' => 'setting.department.index'],
                ['text' => $department->name ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Departments', 'route' => 'setting.department.index'],
                ['text' => $department->name ?? ''],
                ['text' => 'Edit'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}