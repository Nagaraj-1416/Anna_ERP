<?php

namespace App\Http\Controllers\Setting;

use App\Http\Requests\Setting\{
    RoleStoreRequest, RoleUpdateRequest
};
use App\Repositories\Settings\RoleRepository;
use App\Role;
use Illuminate\Http\{
    JsonResponse, RedirectResponse, Request
};
use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Class RoleController
 * @package App\Http\Controllers\Setting
 */
class RoleController extends Controller
{
    /**
     * @var RoleRepository $role
     */
    protected $role;

    public function __construct(RoleRepository $role)
    {
        $this->role = $role;
    }

    /**
     * Load the role index view
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', $this->role->getModel());
        $breadcrumb = $this->role->breadcrumbs();
        if (\request()->ajax()) {
            $stores = $this->role->grid();
            return response()->json($stores);
        }
        return view('settings.role.index', compact('breadcrumb'));
    }

    /**
     * Get role data table data
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function dataTableData(Request $request): JsonResponse
    {
        $this->authorize('index', $this->role->getModel());
        if (\request()->ajax()) {
            return response()->json($this->role->dataTable($request));
        }
    }

    /**
     * Load show view of role
     * @param Role $role
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Role $role): View
    {
        $this->authorize('show', $role);
        $polices = $this->role->getPolices();
        $breadcrumb = $this->role->breadcrumbs($role);
        $users = $role->users;
        return view('settings.role.show', compact('breadcrumb', 'role', 'polices', 'users'));
    }

    /**
     * Load create role view
     * @return \Illuminate\Contracts\View\Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('create', $this->role->getModel());
        $polices = $this->role->getPolices();
        $breadcrumb = $this->role->breadcrumbs();
        return view('settings.role.create', compact('breadcrumb', 'polices'));
    }

    /**
     * Store new role
     * @param RoleStoreRequest $request
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(RoleStoreRequest $request): RedirectResponse
    {
        $this->authorize('store', $this->role->getModel());
        $role = $this->role->store($request);
        $this->role->updatePermissions($request, $role);
        alert()->success('Role created successfully', 'Success')->persistent();
        return redirect()->route('setting.role.index');
    }

    /**
     * Load role edit view
     * @param Role $role
     * @return \Illuminate\Contracts\View\Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Role $role): View
    {
        $this->authorize('edit', $role);
        $polices = $this->role->getPolices();
        $breadcrumb = $this->role->breadcrumbs($role);
        return view('settings.role.edit', compact('breadcrumb', 'role', 'polices'));
    }

    /**
     * update the role
     * @param RoleUpdateRequest $request
     * @param Role $role
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(RoleUpdateRequest $request, Role $role): RedirectResponse
    {
        $this->authorize('update', $role);
        $this->role->update($request, $role);
        $this->role->updatePermissions($request, $role);
        alert()->success('Role updated successfully', 'Success')->persistent();
        return redirect()->route('setting.role.index');
    }

    /**
     * Delete the role
     * @param Role $role
     * @return JsonResponse
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Role $role): JsonResponse
    {
        $this->authorize('delete', $role);
        $response = $this->role->delete($role);
        return response()->json($response);
    }
}
