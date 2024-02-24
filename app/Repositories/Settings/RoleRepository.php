<?php

namespace App\Repositories\Settings;

use App\{
    Policy, Role
};
use App\Http\Requests\Setting\{
    RoleStoreRequest, RoleUpdateRequest
};
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

/**
 * Class RoleRepository
 * @package App\Repositories\Settings
 */
class RoleRepository extends BaseRepository
{
    /**
     * RoleRepository constructor.
     * @param Role|null $role
     */
    public function __construct(Role $role = null)
    {
        $this->setModel($role ?? new Role());
    }

    /**
     * Get data to data table
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['name', 'description', 'access_level'];
        $searchingColumns = ['name', 'description', 'access_level'];
        $data = $this->getTableData($request, $columns, $searchingColumns);
        /** The Current User Can do the action */
        $can = [
            'show' => can('show', $this->model),
            'edit' => can('edit', $this->model),
            'delete' => can('delete', $this->model),
        ];
        $data['data'] = array_map(function ($item) use($can) {
            $item['name'] = '<a href="' . route('setting.role.show', $item['id']) . '">' . $item['name'] . '</a>';
            $item['action'] = "<div class=\"button-group\">";
            /** The Current User Can show The role and generate role show button for index */
            if (array_get($can, 'show')) {
                $item['action'] .= actionBtn('Show', null, ['setting.role.show', [$item['id']]], ['class' => 'btn-success']);
            }
            /** The Current User Can edit The role and generate role edit button for index */
            if (array_get($can, 'edit')) {
                $item['action'] .= actionBtn('Edit', null, ['setting.role.edit', [$item['id']]]);
            }
            /** The Current User Can delete The role and generate role delete button for index */
            if (array_get($can, 'delete')) {
                $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-role']);
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
        $roles = Role::orderBy('id', 'desc')->with('users');
        if ($search) {
            $roles->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            });
        }

        switch ($filter) {
            case 'recentlyCreated':
                $roles->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $roles->where('updated_at', '>', $lastWeek);
                break;
        }

        return $roles->paginate(12)->toArray();
    }

    /**
     * Store new role
     * @param RoleStoreRequest $request
     * @return Role $role;
     */
    public function store(RoleStoreRequest $request): Role
    {
        /** @var Role $role */
        $role = $this->model->fill($request->toArray());
        $role->save();
        return $role;
    }

    /**
     * Update the role
     * @param RoleUpdateRequest $request
     * @param Role $role
     * @return Role
     */
    public function update(RoleUpdateRequest $request, Role $role): Role
    {
        $role->update($request->toArray());
        return $role;
    }

    /**
     * Update permissions for role
     * @param Request $request
     * @param Role $role | null
     * @return Role
     */
    public function updatePermissions(Request $request, Role $role)
    {
        if (!$request->input('permission')) return null;
        $role->permission = $this->transformPermissions($request->input('permission'));
        $role->save();
        return $role;
    }

    /**
     * transform input data for permission
     * @param $permissions
     * @return array
     */
    public function transformPermissions($permissions)
    {
        $return = [];
        if ($permissions != null) {
            foreach ($permissions as $key => $permission) {
                $return[$key] = array_keys($permission);
            }
        }
        return $return;
    }

    /**
     * delete the role
     * @param Role $role
     * @return array
     * @throws \Exception
     */
    public function delete(Role $role): array
    {
        if ($role->is_deletable == 'No') {
            return ['success' => false, 'message' => 'This role is default role. You can\'t delete this role.'];
        }
        if ($role->users->count() > 0) {
            return ['success' => false, 'message' => 'This role is associated with users. \n Please disassociate the role from users and then try delete.'];
        }
        $role->delete();
        return ['success' => true, 'message' => 'Role deleted successfully!'];
    }

    /**
     * Get Available polices
     * @return array
     */
    public function getPolices()
    {
        $policyGroups = getPolicyGroups();
        $models = [];
        foreach ($policyGroups as $policyGroup) {
            $key = snake_case($policyGroup['group_name'] ?? '');
            if (!isset($models[$key])) {
                $models[$key] = [
                    'group_name' => $policyGroup['group_name'] ?? '',
                    'policies' => []
                ];
            }
            if (isset($policyGroup['polices'])) {
                foreach ($policyGroup['polices'] as $policy) {
                    $policyClassName = str_replace('.php', '', $policy);
                    $policyNamespace = $policyGroup['name_space'] ?? '';
                    $policy = $policyNamespace . '\\' . $policyClassName;
                    $policyObject = new $policy();
                    $policyModel = $policyObject->model;
                    $modelName = snake_case(class_basename($policyModel), ' ');
                    $policyName = snake_case(class_basename($policy), ' ');
                    array_push($models[$key]['policies'], [
                        'model' => $policyModel,
                        'model_name' => ucfirst($modelName),
                        'policy' => $policy,
                        'policy_name' => ucfirst($policyName)
                    ]);
                }
            }
        }
        return $models;
    }

    /**
     * Get the breadcrumbs of the user module
     * @param string $method
     * @param Role|null $role
     * @return array|mixed
     */
    public function breadcrumbs(Role $role = null, string $method = null): array
    {
        if (!$method) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
            $method = $backtrace[1]['function'] ?? null;
        }
        $base = [
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Settings', 'route' => 'setting.index'],
        ];
        $breadcrumbs = [
            'index' => array_merge($base, [
                ['text' => 'Roles'],
            ]),
            'create' => array_merge($base, [
                ['text' => 'Roles', 'route' => 'setting.role.index'],
                ['text' => 'Create']
            ]),
            'show' => array_merge($base, [
                ['text' => 'Roles', 'route' => 'setting.role.index'],
                ['text' => $role->name ?? ''],
            ]),
            'edit' => array_merge($base, [
                ['text' => 'Roles', 'route' => 'setting.role.index'],
                ['text' => $role->name ?? ''],
                ['text' => 'Edit'],
            ])
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}