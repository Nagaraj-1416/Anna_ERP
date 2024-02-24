<?php

namespace App\Http\Controllers\Setting;

use App\Http\Requests\Setting\{
    AssignStaffRequest, ProductionUnitCreateRequest
};
use App\Repositories\Settings\ProductionUnitRepository;
use App\Http\Controllers\Controller;
use App\{
    Staff, ProductionUnit
};
use Illuminate\Http\{
    JsonResponse, RedirectResponse
};
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductionUnitController extends Controller
{
    /**
     * @var ProductionUnitRepository
     */
    protected $productionUnit;

    /**
     * ProductionUnitController constructor.
     * @param ProductionUnitRepository $productionUnit
     */
    public function __construct(ProductionUnitRepository $productionUnit)
    {
        $this->productionUnit = $productionUnit;
    }

    /**
     * Load index view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        if (\request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $units = $this->productionUnit->grid();
            return response()->json($units);
        }
        $this->authorize('index', $this->productionUnit->getModel());
        //if (\request()->ajax()) {
        //    $units = $this->productionUnit->grid();
        //    return response()->json($units);
        //}
        $breadcrumb = $this->productionUnit->breadcrumbs('index');
        return view('settings.production-unit.index', compact('breadcrumb'));
    }

    /**
     * generate data for production unit index data table data
     * @param Request $request
     * @return array
     */
    public function dataTableData(Request $request): array
    {
        if (\request()->ajax()) {
            return $this->productionUnit->dataTable($request);
        }
    }

    /**
     * Load production unit create view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('create', $this->productionUnit->getModel());
        $breadcrumb = $this->productionUnit->breadcrumbs('create');
        return view('settings.production-unit.create', compact('breadcrumb'));
    }

    /**
     * Store the production unit
     * @param ProductionUnitCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(ProductionUnitCreateRequest $request)//: RedirectResponse
    {
        //\Log::info('Form submitted with data:', $request->all());
        if ( \request()->header('User-Agent') == 'Postman') {
            $productionUnit=$this->productionUnit->save($request);
            return response()->json($productionUnit);
        }
        $this->authorize('store', $this->productionUnit->getModel());
        $this->productionUnit->save($request);
        alert()->success('ProductionUnit created successfully', 'Success')->persistent();
        return redirect()->route('setting.production.unit.index');
    }

    /**
     * Load show view of production unit
     * @param ProductionUnit $productionUnit
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(ProductionUnit $productionUnit)//: View
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            return response()->json($productionUnit);
        }
        $this->authorize('show', $this->productionUnit->getModel());
        $breadcrumb = $this->productionUnit->breadcrumbs('show', $productionUnit);
        return view('settings.production-unit.show', compact('breadcrumb', 'productionUnit'));
    }

    /**
     * Load edit view of production unit
     * @param ProductionUnit $productionUnit
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(ProductionUnit $productionUnit)//: View
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            $productionUnit->company_id = $productionUnit->company ? $productionUnit->company->id : '';
            return response()->json($productionUnit);
        }
        $this->authorize('edit', $this->productionUnit->getModel());
        $breadcrumb = $this->productionUnit->breadcrumbs('edit', $productionUnit);
        $productionUnit->company_id = $productionUnit->company ? $productionUnit->company->id : '';
        return view('settings.production-unit.edit', compact('breadcrumb', 'productionUnit'));
    }

    /**
     * Update the production unit
     * @param ProductionUnitCreateRequest $request
     * @param ProductionUnit $productionUnit
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(ProductionUnitCreateRequest $request, ProductionUnit $productionUnit)//: RedirectResponse
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $single_production_unit=$this->productionUnit->update($request, $productionUnit);
            return response()->json($single_production_unit);
        }
        $this->authorize('update', $this->productionUnit->getModel());
        $this->productionUnit->update($request, $productionUnit);
        alert()->success('ProductionUnit updated successfully', 'Success')->persistent();
        return redirect()->route('setting.production.unit.index');
    }

    /**
     * Delete the production unit
     * @param ProductionUnit $productionUnit
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(ProductionUnit $productionUnit): JsonResponse
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $response = $this->productionUnit->delete($productionUnit);
            return response()->json($response);
        }
        $this->authorize('delete', $this->productionUnit->getModel());
        $response = $this->productionUnit->delete($productionUnit);
        return response()->json($response);
    }

    /**
     * assign staff to Production unit
     * @param AssignStaffRequest $request
     * @param ProductionUnit $productionUnit
     * @return RedirectResponse
     */
    public function assignStaff(AssignStaffRequest $request, ProductionUnit $productionUnit)//: RedirectResponse
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $this->productionUnit->setModel($productionUnit);
            $this->productionUnit->assignStaff($request);
            return response()->json('Staff assigned successfully');
        }
        $this->productionUnit->setModel($productionUnit);
        $this->productionUnit->assignStaff($request);
        alert()->success('Staff assigned successfully', 'Success')->persistent();
        return redirect()->back();
    }

    /**
     * Remove staff from production unit
     * @param ProductionUnit $productionUnit
     * @param Staff $staff
     * @return JsonResponse
     */
    public function removeStaff(ProductionUnit $productionUnit, Staff $staff): JsonResponse
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $this->productionUnit->setModel($productionUnit);
            $response = $this->productionUnit->removeStaff($staff);
            return response()->json($response);
        }
        $this->productionUnit->setModel($productionUnit);
        $response = $this->productionUnit->removeStaff($staff);
        return response()->json($response);
    }

    /**
     * Search staff from production unit
     * @param ProductionUnit $productionUnit
     * @param null $q
     * @return JsonResponse
     */
    public function searchStaff(ProductionUnit $productionUnit, $q = null): JsonResponse
    {
        $this->productionUnit->setModel($productionUnit);
        $response = $this->productionUnit->searchStaff($q);
        return response()->json($response);
    }
}
