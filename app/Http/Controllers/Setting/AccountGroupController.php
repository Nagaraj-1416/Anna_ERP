<?php

namespace App\Http\Controllers\Setting;

use App\AccountCategory;
use App\AccountGroup;
use App\AccountType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\AccountGroupStoreRequest;
use App\Repositories\Settings\AccountGroupRepository;
use Illuminate\Http\JsonResponse;

class AccountGroupController extends Controller
{
    /** @var AccountGroupRepository */
    protected $group;

    /**
     * BrandController constructor.
     * @param AccountGroupRepository $group
     */
    public function __construct(AccountGroupRepository $group)
    {
        $this->group = $group;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', $this->group->getModel());
        $breadcrumb = $this->group->breadcrumbs();
        if (\request()->ajax()) {
            $stores = $this->group->grid();
            return response()->json($stores);
        }
        return view('settings.account-group.index', compact('breadcrumb'));
    }

    /**
     * @param AccountGroup $accountGroup
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(AccountGroup $accountGroup)
    {
        $this->authorize('index', $this->group->getModel());
        $breadcrumb = $this->group->breadcrumbs($accountGroup);
        return view('settings.account-group.show', compact('breadcrumb', 'accountGroup'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('index', $this->group->getModel());
        $breadcrumb = $this->group->breadcrumbs();
        return view('settings.account-group.create', compact('breadcrumb'));
    }

    /**
     * Store the brand
     * @param AccountGroupStoreRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(AccountGroupStoreRequest $request)
    {
        $this->authorize('store', $this->group->getModel());
        $this->group->storeItem($request->toArray());
        return redirect()->route('setting.account.group.index');
    }

    /**
     * @param AccountGroup $accountGroup
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(AccountGroup $accountGroup)
    {
        $this->authorize('edit', $this->group->getModel());
        $breadcrumb = $this->group->breadcrumbs($accountGroup);
        return view('settings.account-group.edit', compact('breadcrumb', 'accountGroup'));
    }

    /**
     * Update the brand
     * @param AccountGroup $accountGroup
     * @param AccountGroupStoreRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(AccountGroup $accountGroup, AccountGroupStoreRequest $request)
    {
        $this->authorize('store', $this->group->getModel());
        $this->group->updateItem($accountGroup->id, $request->toArray());
        return redirect()->route('setting.account.group.index');
    }

    public function delete(AccountGroup $accountGroup)
    {
        return $this->group->delete($accountGroup);
    }

    /**
     * Search account group for drop down
     * @param null $q
     * @return JsonResponse
     */
    public function search($q = null)
    {
        $response = $this->group->search($q, 'name', ['name'], ['is_active' => ['No']]);
        return response()->json($response);
    }

    public function searchByType(AccountType $type, $q = null)
    {
        /** @var AccountCategory $category */
        $category = $type->category;
        return $this->searchByCategory($category, $q);
    }

    public function searchByCategory(AccountCategory $category, $q = null)
    {
        $response = $this->group->search($q, 'name', ['name'], ['is_active' => 'No'], [['category_id', $category->id]]);
        return response()->json($response);
    }
}