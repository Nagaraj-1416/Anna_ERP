<?php

namespace App\Http\Controllers\Setting;

use App\Http\Requests\Setting\{
    AssignStaffRequest, StoreCreateRequest
};
use App\Repositories\Settings\StoreRepository;
use App\{Company, Staff, Store};
use App\Http\Controllers\Controller;

use Illuminate\Http\{
    JsonResponse, RedirectResponse, Request
};
use Illuminate\View\View;
use function Symfony\Component\Debug\Tests\testHeader;

class StoreController extends Controller
{
    /**
     * @var StoreRepository
     */
    protected $store;

    /**
     * StoreController constructor.
     * @param StoreRepository $store
     */
    public function __construct(StoreRepository $store)
    {
        $this->store = $store;
    }

    /**
     * Load index view of store
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        if (\request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $stores = $this->store->grid();
            return response()->json($stores);
        }
        $this->authorize('index', $this->store->getModel());
        //if (\request()->ajax()) {
        //    $stores = $this->store->grid();
        //    return response()->json($stores);
        //}
        $breadcrumb = $this->store->breadcrumbs('index');
        return view('settings.store.index', compact('breadcrumb'));
    }

    /**
     * Generate data for store index data table
     * @param Request $request
     * @return array
     */
    public function dataTableData(Request $request): array
    {
        if (\request()->ajax()) {
            return $this->store->dataTable($request);
        }
    }

    /**
     * Load store create view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('create', $this->store->getModel());
        $breadcrumb = $this->store->breadcrumbs('create');
        return view('settings.store.create', compact('breadcrumb'));
    }

    /**
     * Store new store
     * @param StoreCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreCreateRequest $request)//: RedirectResponse
    {
        //\Log::info('Form submitted with data:', $request->all());
        if ( \request()->header('User-Agent') == 'Postman') {
            $store=$this->store->save($request);
            return response()->json($store);
        }
        $this->authorize('store', $this->store->getModel());
        $this->store->save($request);
        alert()->success('Store created successfully', 'Success')->persistent();
        return redirect()->route('setting.store.index');
    }

    /**
     * Load show view of store
     * @param Store $store
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Store $store)//: View
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            return response()->json($store);
        }
        $this->authorize('show', $this->store->getModel());
        $breadcrumb = $this->store->breadcrumbs('show', $store);
        return view('settings.store.show', compact('breadcrumb', 'store'));
    }

    /**
     * Load edit view of store
     * @param Store $store
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Store $store)//: View
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            $store->company_id = $store->company ? $store->company->id : '';
            return response()->json($store);
        }
        $this->authorize('edit', $this->store->getModel());
        $breadcrumb = $this->store->breadcrumbs('edit', $store);
        $store->company_id = $store->company ? $store->company->id : '';
        return view('settings.store.edit', compact('breadcrumb', 'store'));
    }

    /**
     * Update store data
     * @param StoreCreateRequest $request
     * @param Store $store
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(StoreCreateRequest $request, Store $store)//: RedirectResponse
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $store=$this->store->update($request, $store);
            return response()->json($store);
        }
        $this->authorize('update', $this->store->getModel());
        $this->store->update($request, $store);
        alert()->success('Store updated successfully', 'Success')->persistent();
        return redirect()->route('setting.store.index');
    }

    /**
     * Delete Store
     * @param Store $store
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(Store $store): JsonResponse
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $response = $this->store->delete($store);
            return response()->json($response);
        }
        $this->authorize('delete', $this->store->getModel());
        $response = $this->store->delete($store);
        return response()->json($response);
    }

    /**
     * assign staff to store
     * @param AssignStaffRequest $request
     * @param Store $store
     * @return RedirectResponse
     */
    public function assignStaff(AssignStaffRequest $request, Store $store)//: RedirectResponse
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $this->store->setModel($store);
            $this->store->assignStaff($request);
            return response()->json('Staff assigned successfully');
        }
        $this->store->setModel($store);
        $this->store->assignStaff($request);
        alert()->success('Staff assigned successfully', 'Success')->persistent();
        return redirect()->back();
    }

    /**
     * Remove staff from store
     * @param Store $store
     * @param Staff $staff
     * @return JsonResponse
     */
    public function removeStaff(Store $store, Staff $staff): JsonResponse
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $this->store->setModel($store);
            $response = $this->store->removeStaff($staff);
            return response()->json($response);
        }
        $this->store->setModel($store);
        $response = $this->store->removeStaff($staff);
        return response()->json($response);
    }

    /**
     * Search staff from store
     * @param Store $store
     * @param null $q
     * @return JsonResponse
     */
    public function searchStaff(Store $store, $q = null): JsonResponse
    {
        $this->store->setModel($store);
        $response = $this->store->searchStaff($q);
        return response()->json($response);
    }

    /**
     * Search store for drop down
     * @param null $q
     * @return JsonResponse
     */
    public function search($q = null)
    {
        $response = $this->store->search($q, 'name', ['name'], ['is_active' => 'No']);
        return response()->json($response);
    }

    public function searchByCompany(Company $company, $q = null)
    {
        if ($q == null) {
            $stores = $company->stores()->get(['id', 'name', 'code'])->toArray();
        } else {
            $stores = $company->stores()->where('name', 'LIKE', '%' . $q . '%')->get()->toArray();
        }
        $stores = array_map(function ($obj) {
            return ["name" => $obj['name'] . ' (' . $obj['code'] . ')', "value" => $obj['id']];
        }, $stores);
        return response()->json(["success" => true, "results" => $stores]);
    }
}
