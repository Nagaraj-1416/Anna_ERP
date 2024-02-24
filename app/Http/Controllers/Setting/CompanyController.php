<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\{
    AssignStaffRequest, CompanyStoreRequest
};
use App\Repositories\Settings\CompanyRepository;
use App\{
    Staff, Company
};
use Illuminate\Http\{
    JsonResponse, RedirectResponse, Request
};
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Class CompanyController
 * @package App\Http\Controllers\Setting
 */
class CompanyController extends Controller
{
    /**
     * @var CompanyRepository
     */
    protected $company;
    protected $logoPath;

    /**
     * CompanyController constructor.
     * @param CompanyRepository $company
     */
    public function __construct(CompanyRepository $company)
    {
        $this->company = $company;
        $this->logoPath = $company->getLogoPath();
    }

    /**
     * Company index
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        if (\request()->ajax()|| \request()->header('User-Agent') == 'Postman') {
            $companies = $this->company->grid();
            return response()->json($companies);
        }
        //$this->authorize('index', $this->company->getModel());
        $breadcrumb = $this->company->breadcrumbs('index');
        return view('settings.company.index', compact('breadcrumb'));
    }

    /**
     * Handle the index page data table data
     * @param Request $request
     * @return array
     */
    public function dataTableData(Request $request): array
    {
        if (\request()->ajax()){
            return $this->company->dataTable($request);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('create', $this->company->getModel());
        $breadcrumb = $this->company->breadcrumbs('create');
        return view('settings.company.create', compact('breadcrumb'));
    }

    /**
     * @param CompanyStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CompanyStoreRequest $request): RedirectResponse
    {
        //  \Log::info('Form submitted with data:', $request->all());
          if ( \request()->header('User-Agent') == 'Postman') {
            $company = $this->company->save($request);
            return response()->json($company);
        }
        $this->authorize('store', $this->company->getModel());
        $this->company->save($request);
        alert()->success('Company created successfully', 'Success')->persistent();
        return redirect()->route('setting.company.index');
    }

    /**
     * @param Company $company
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Company $company): View
    {
        $this->authorize('show', $this->company->getModel());
        $breadcrumb = $this->company->breadcrumbs('show', $company);
        $address = $company->addresses->first();
        return view('settings.company.show', compact('breadcrumb', 'company', 'address'));
    }

    /**
     * @param Company $company
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Company $company): View
    {
        $this->authorize('edit', $this->company->getModel());
        $breadcrumb = $this->company->breadcrumbs('edit');
        $address = $company->addresses->first();
        $company->street_one = $address ? $address->street_one : '';
        $company->street_two = $address ? $address->street_two : '';
        $company->city = $address ? $address->city : '';
        $company->province = $address ? $address->province : '';
        $company->postal_code = $address ? $address->postal_code : '';
        $company->country_id = $address ? $address->country_id : '';
        return view('settings.company.edit', compact('breadcrumb', 'company', 'address'));
    }

    /**
     * @param CompanyStoreRequest $request
     * @param Company $company
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CompanyStoreRequest $request, Company $company): RedirectResponse
    {
        $this->authorize('update', $this->company->getModel());
        $this->company->update($request, $company);
        alert()->success('Company updated successfully', 'Success')->persistent();
        return redirect()->route('setting.company.index');
    }

    /**
     * @param Company $company
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(Company $company): JsonResponse
    {
        $this->authorize('delete', $this->company->getModel());
        $response = $this->company->delete($company);
        return response()->json($response);
    }

    /**
     * assign staffs to company
     * @param AssignStaffRequest $request
     * @param Company $company
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignStaff(AssignStaffRequest $request, Company $company): RedirectResponse
    {
        $this->company->setModel($company);
        $this->company->assignStaff($request);
        alert()->success('Staff assigned successfully', 'Success')->persistent();
        return redirect()->back();
    }

    /**
     * Searching staff for assign staff to company
     * @param Company $company
     * @param null $q
     * @return JsonResponse
     */
    public function searchStaff(Company $company, $q = null): JsonResponse
    {
        $this->company->setModel($company);
        $response = $this->company->searchStaff($q);
        return response()->json($response);
    }

    /**
     * @param null $q
     * @return JsonResponse
     */
    public function search($q = null){
        $response = $this->company->search($q, 'name', ['name', 'code'], ['is_active' => 'No']);
        return response()->json($response);
    }

    /**
     * Remove staff from company
     * @param Company $company
     * @param Staff $staff
     * @return JsonResponse
     */
    public function removeStaff(Company $company, Staff $staff): JsonResponse
    {
        $this->company->setModel($company);
        $response = $this->company->removeStaff($staff);
        return response()->json($response);
    }

    /**
     * @param Company $company
     * @return mixed
     */
    public function getLogo(Company $company)
    {
        if($company->getAttribute('company_logo')){
            $imagePath = Storage::get($this->logoPath . $company->getAttribute('company_logo'));
        }else{
            $imagePath = Storage::get('data/default.jpg');
        }
        return response($imagePath)->header('Content-Type',  'image/jpg');
    }
}
