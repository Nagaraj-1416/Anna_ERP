<?php

namespace App\Http\Controllers\Setting;

use App\Http\Requests\Setting\{
    AssignProductRequest, AssignStaffRequest, SalesLocationStoreRequest
};
use App\Repositories\Settings\SalesLocationRepository;
use App\{Company, Route, SalesLocation, Staff};
use Illuminate\Http\{
    JsonResponse, RedirectResponse, Request
};
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use function Symfony\Component\Debug\Tests\testHeader;


class SalesLocationController extends Controller
{
    /**
     * @var SalesLocationRepository
     */
    protected $salesLocation;

    /**
     * SalesLocationController constructor.
     * @param SalesLocationRepository $salesLocation
     */
    public function __construct(SalesLocationRepository $salesLocation)
    {
        $this->salesLocation = $salesLocation;
    }

    /**
     * Load index view of sales location
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        if (\request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $salesLocation = $this->salesLocation->grid();
            return response()->json($salesLocation);
        }
        $this->authorize('index', $this->salesLocation->getModel());
        // if (\request()->ajax()) {
        //    $locations = $this->salesLocation->grid();
        //    return response()->json($locations);
        // }
        $breadcrumb = $this->salesLocation->breadcrumbs('index');
        return view('settings.sales-location.index', compact('breadcrumb'));
    }

    /**
     * Generate data for sales location index data table
     * @param Request $request
     * @return array
     */
    public function dataTableData(Request $request): array
    {
        if (\request()->ajax()) {
            return $this->salesLocation->dataTable($request);
        }
    }

    /**
     * Load sales location create view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('create', $this->salesLocation->getModel());
        $breadcrumb = $this->salesLocation->breadcrumbs('create');
        return view('settings.sales-location.create', compact('breadcrumb'));
    }

    /**
     * Store Sales location
     * @param SalesLocationStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(SalesLocationStoreRequest $request)//: RedirectResponse
    {
        // \Log::info('Form submitted with data:', $request->all());
         if ( \request()->header('User-Agent') == 'Postman') {
            $salesLocation=$this->salesLocation->save($request);
            return response()->json($salesLocation);
        }
        $this->authorize('store', $this->salesLocation->getModel());
        $this->salesLocation->save($request);
        alert()->success('Sales location created successfully', 'Success')->persistent();
        return redirect()->route('setting.sales.location.index');
    }

    /**
     * Load show view f sales location
     * @param SalesLocation $salesLocation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(SalesLocation $salesLocation)//: View
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            return response()->json($salesLocation);
        }
        $this->authorize('show', $this->salesLocation->getModel());
        $breadcrumb = $this->salesLocation->breadcrumbs('show', $salesLocation);
        return view('settings.sales-location.show', compact('breadcrumb', 'salesLocation'));
    }

    /**
     * Load edit view f sales location
     * @param SalesLocation $salesLocation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(SalesLocation $salesLocation)//: View
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            $salesLocation->company_id = $salesLocation->company ? $salesLocation->company->id : '';
            return response()->json($salesLocation);
        }
        $this->authorize('edit', $this->salesLocation->getModel());
        $breadcrumb = $this->salesLocation->breadcrumbs('edit', $salesLocation);
        $salesLocation->company_id = $salesLocation->company ? $salesLocation->company->id : '';
        return view('settings.sales-location.edit', compact('breadcrumb', 'salesLocation'));
    }

    /**
     * Update sales location
     * @param SalesLocationStoreRequest $request
     * @param SalesLocation $salesLocation
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(SalesLocationStoreRequest $request, SalesLocation $salesLocation)//: RedirectResponse
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $single_salesLocation=$this->salesLocation->update($request, $salesLocation);
            return response()->json($single_salesLocation);
        }
        $this->authorize('update', $this->salesLocation->getModel());
        $this->salesLocation->update($request, $salesLocation);
        alert()->success('Sales location updated successfully', 'Success')->persistent();
        return redirect()->route('setting.sales.location.index');
    }

    /**
     * Delete sales location
     * @param SalesLocation $salesLocation
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(SalesLocation $salesLocation)//: JsonResponse
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $response = $this->salesLocation->delete($salesLocation);
            return response()->json($response);
        }
        $this->authorize('delete', $this->salesLocation->getModel());
        $response = $this->salesLocation->delete($salesLocation);
        return response()->json($response);
    }

    /**
     * assign staff to sales location
     * @param AssignStaffRequest $request
     * @param SalesLocation $salesLocation
     * @return RedirectResponse
     */
    public function assignStaff(AssignStaffRequest $request, SalesLocation $salesLocation)//: RedirectResponse
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $this->salesLocation->setModel($salesLocation);
            $this->salesLocation->assignStaff($request);
            return response()->json('Staff assigned successfully');
        }
        $this->salesLocation->setModel($salesLocation);
        $this->salesLocation->assignStaff($request);
        alert()->success('Staff assigned successfully', 'Success')->persistent();
        return redirect()->back();
    }

    /**
     * Remove staff from sales location
     * @param SalesLocation $salesLocation
     * @param Staff $staff
     * @return JsonResponse
     */
    public function removeStaff(SalesLocation $salesLocation, Staff $staff): JsonResponse
    {
        $this->salesLocation->setModel($salesLocation);
        $response = $this->salesLocation->removeStaff($staff);
        return response()->json($response);
    }

    /**
     * Search staff from sales location
     * @param SalesLocation $salesLocation
     * @param null $q
     * @return JsonResponse
     */
    public function searchStaff(SalesLocation $salesLocation, $q = null): JsonResponse
    {
        $this->salesLocation->setModel($salesLocation);
        $response = $this->salesLocation->searchStaff($q);
        return response()->json($response);
    }

    /**
     * @param $type
     * @param null $q
     * @return JsonResponse
     */
    public function searchWithType($type, $q = null)
    {
        if ($type == 'Van') $type = 'Sales Van';
        $data = $this->salesLocation->search($q, 'name', ['name'], [], [['type', $type]]);
        return response()->json($data);
    }

    /**
     * @param null $q
     * @return JsonResponse
     */
    public function search($q = null)
    {

        $data = $this->salesLocation->search($q, 'name', ['name'], [],[]);
        return response()->json($q);

    }

    public function assignProduct(AssignProductRequest $request, SalesLocation $salesLocation)
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $this->salesLocation->setModel($salesLocation);
            $assign_product =  $this->salesLocation->assignProduct($request);
            return response()->json($assign_product);
        }
        $this->salesLocation->setModel($salesLocation);
        $this->salesLocation->assignProduct($request);
        alert()->success('Products assigned successfully', 'Success')->persistent();
        return redirect()->back();
    }

    public function searchByCompany(Company $company, $q = null)
    {
        if ($q == null) {
            $vans = $company->salesLocations()->get(['id', 'name', 'code'])->toArray();
        } else {
            $vans = $company->salesLocations()->where('name', 'LIKE', '%' . $q . '%')->get()->toArray();
        }
        $vans = array_map(function ($obj) {
            return ["name" => $obj['name'] . ' (' . $obj['code'] . ')', "value" => $obj['id']];
        }, $vans);
        return response()->json(["success" => true, "results" => $vans]);
    }

    public function searchVanByCompany(Company $company, $q = null)
    {
        if ($q == null) {
            $vans = $company->salesLocations()->where('type', 'Sales Van')->get(['id', 'name', 'code'])->toArray();
        } else {
            $vans = $company->salesLocations()->where('type', 'Sales Van')->where('name', 'LIKE', '%' . $q . '%')->get()->toArray();
        }
        $vans = array_map(function ($obj) {
            return ["name" => $obj['name'] . ' (' . $obj['code'] . ')', "value" => $obj['id']];
        }, $vans);
        return response()->json(["success" => true, "results" => $vans]);
    }

    public function searchShopByCompany(Company $company, $q = null)
    {
        if ($q == null) {
            $shops = $company->salesLocations()->where('type', 'Shop')->get(['id', 'name', 'code'])->toArray();
        } else {
            $shops = $company->salesLocations()->where('type', 'Shop')->where('name', 'LIKE', '%' . $q . '%')->get()->toArray();
        }
        $shops = array_map(function ($obj) {
            return ["name" => $obj['name'] . ' (' . $obj['code'] . ')', "value" => $obj['id']];
        }, $shops);
        return response()->json(["success" => true, "results" => $shops]);
    }

}
