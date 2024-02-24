<?php

namespace App\Http\Controllers\Setting;

use App\BusinessType;
use App\Http\Controllers\Controller;

use App\Http\Requests\Setting\BusinessTypeStoreRequest;
use App\Repositories\Settings\BusinessTypeRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BusinessTypeController extends Controller
{
    /**
     * @var BusinessTypeRepository
     */
    protected $businessType;

    /**
     * StoreController constructor.
     * @param BusinessTypeRepository $businessType
     */
    public function __construct(BusinessTypeRepository $businessType)
    {
        $this->businessType = $businessType;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', $this->businessType->getModel());
        if (\request()->ajax()) {
            $types = $this->businessType->grid();
            return response()->json($types);
        }
        $breadcrumb = $this->businessType->breadcrumbs('index');
        return view('settings.business-type.index', compact('breadcrumb'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function dataTableData(Request $request)
    {
        if (\request()->ajax()) {
            return $this->businessType->dataTable($request);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->businessType->getModel());
        $breadcrumb = $this->businessType->breadcrumbs('create');
        return view('settings.business-type.create', compact('breadcrumb'));
    }

    /**
     * @param BusinessTypeStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(BusinessTypeStoreRequest $request)
    {
        $this->authorize('store', $this->businessType->getModel());
        $this->businessType->save($request);
        alert()->success('Business type created successfully', 'Success')->persistent();
        return redirect()->route('setting.business.type.index');
    }

    /**
     * @param BusinessType $businessType
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(BusinessType $businessType)
    {
        $this->authorize('show', $this->businessType->getModel());
        $breadcrumb = $this->businessType->breadcrumbs('show', $businessType);
        return view('settings.business-type.show', compact('breadcrumb', 'businessType'));
    }

    /**
     * @param BusinessType $businessType
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(BusinessType $businessType)
    {
        $this->authorize('edit', $this->businessType->getModel());
        $breadcrumb = $this->businessType->breadcrumbs('edit', $businessType);
        return view('settings.business-type.edit', compact('breadcrumb', 'businessType'));
    }

    /**
     * @param BusinessTypeStoreRequest $request
     * @param BusinessType $businessType
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(BusinessTypeStoreRequest $request, BusinessType $businessType)
    {
        $this->authorize('update', $this->businessType->getModel());
        $this->businessType->update($request, $businessType);
        alert()->success('Business type updated successfully', 'Success')->persistent();
        return redirect()->route('setting.business.type.index');
    }

    /**
     * @param BusinessType $businessType
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(BusinessType $businessType): JsonResponse
    {
        $this->authorize('delete', $this->businessType->getModel());
        $response = $this->businessType->delete($businessType);
        return response()->json($response);
    }

    /**
     * Search business type for drop down
     * @param null $q
     * @return JsonResponse
     */
    public function search($q = null)
    {
        $response = $this->businessType->search($q, 'name', ['name'], ['is_active' => ['No']]);
        return response()->json($response);
    }
}
