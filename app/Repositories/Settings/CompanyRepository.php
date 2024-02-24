<?php

namespace App\Repositories\Settings;

use App\Http\Requests\Setting\{
    AssignStaffRequest, CompanyStoreRequest
};
use App\Rep;
use App\Repositories\BaseRepository;
use App\{
    Company, Address, Department, Staff, Store
};
use App\Repositories\Finance\AccountRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class CompanyRepository
 * @package App\Repositories\Settings
 */
class CompanyRepository extends BaseRepository
{
    protected $logoPath = 'company-logos/';
    protected $account;

    /**
     * CompanyRepository constructor.
     * @param Company|null $company
     * @param AccountRepository $account
     */
    public function __construct(AccountRepository $account, Company $company = null)
    {
        $this->setModel($company ?? new Company());
        $this->setCodePrefix('COM');

        $this->account = $account;
    }

    /**
     * Get data to data table
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['code', 'name', 'phone', 'fax', 'mobile', 'email', 'business_location', 'is_active'];
        $searchingColumns = ['code', 'name', 'phone', 'fax', 'mobile', 'email', 'business_location', 'is_active'];
        $data = $this->getTableData($request, $columns, $searchingColumns);
        $data['data'] = array_map(function ($item) {
            $item['code'] = '<a href="' . route('setting.company.show', $item['id']) . '">' . $item['code'] . '</a>';
            $item['action'] = "<div class=\"button-group\">";
            if (can('show', $this->getModel())) {
                $item['action'] .= actionBtn('Show', null, ['setting.company.show', [$item['id']]], ['class' => 'btn-success']);
            }
            if (can('edit', $this->getModel())) {
                $item['action'] .= actionBtn('Edit', null, ['setting.company.edit', [$item['id']]]);
            }
            if (can('delete', $this->getModel())) {
                $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-company']);
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
        $companies = Company::orderBy('id', 'desc')->with('staff');
        if ($search) {
            $companies->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', '%' . $search . '%')
                    ->orWhere('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('phone', 'LIKE', '%' . $search . '%')
                    ->orWhere('fax', 'LIKE', '%' . $search . '%')
                    ->orWhere('mobile', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }

        switch ($filter) {
            case 'Active':
                $companies->where('is_active', 'Yes');
                break;
            case 'Inactive':
                $companies->where('is_active', 'No');
                break;
            case 'recentlyCreated':
                $companies->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $companies->where('updated_at', '>', $lastWeek);
                break;
        }

        return $companies->paginate(12)->toArray();
    }

    /**
     * Store new company
     * @param CompanyStoreRequest $request
     * @return Company
     */
    public function save(CompanyStoreRequest $request): Company
    {
        /** @var Company $company */
        $request->merge(['code' => $this->getCode()]);
        $company = $this->model->fill($request->toArray());
        $company->save();

        /** associate address */
        $addressable = $this->transformAddress($request);
        if (count($addressable) > 0) {
            $company->addresses()->saveMany($addressable);
        }

        /** upload company logo to storage - if logo attached only */
        $logoFile = $request->file('logo_file');
        if ($logoFile) {
            $logoType = $logoFile->getClientOriginalExtension();
            $logoName = $company->getAttribute('code') . '.' . $logoType;
            Storage::put($this->logoPath . $logoName, file_get_contents($logoFile));

            /** update company logo name to row item */
            $company->setAttribute('company_logo', $logoName);
            $company->save();
        }

        /** create Cash and CIH account for company */
        if($company){
            $this->account->createCompanyCashAccount($company);
            $this->account->createCompanyChequeAccount($company);
        }

        return $company;
    }

    /**
     * Update the company
     * @param CompanyStoreRequest $request
     * @param Company $company
     * @return Company
     */
    public function update(CompanyStoreRequest $request, Company $company): Company
    {
        $request->merge(['code' => $company->code]);
        $this->setModel($company);
        $this->model->update($request->toArray());

        /** updated associated address */
        $address = $company->addresses->first();
        if ($address) {
            $address->update($request->toArray());
        } else {
            /** associate address */
            $addressable = $this->transformAddress($request);
            if (count($addressable) > 0) {
                $company->addresses()->saveMany($addressable);
            }
        }

        /** upload company logo to storage - if logo attached only */
        $logoFile = $request->file('logo_file');
        if ($logoFile) {
            /** remove already available logo if new logo attached */
            Storage::delete($this->logoPath . $company->getAttribute('company_logo'));
            $company->setAttribute('company_logo', null);
            $company->save();

            /** upload the new logo to storage and update raw data item */
            $logoType = $logoFile->getClientOriginalExtension();
            $logoName = $company->getAttribute('code') . '.' . $logoType;
            Storage::put($this->logoPath . $logoName, file_get_contents($logoFile));

            /** update company logo name to row item */
            $company->setAttribute('company_logo', $logoName);
            $company->save();
        }
        return $company;
    }

    /**
     * transform address
     * @param $request
     * @return array
     */
    private function transformAddress($request): array
    {
        $addressable = [];
        $data = [];
        $data['street_one'] = $request->input('street_one');
        $data['street_two'] = $request->input('street_two');
        $data['city'] = $request->input('city');
        $data['province'] = $request->input('province');
        $data['postal_code'] = $request->input('postal_code');
        $data['country_id'] = $request->input('country_id');
        $addressable[] = new Address($data);
        return $addressable;
    }

    /**
     * @param Company $company
     * @return array
     * @throws \Exception
     */
    public function delete(Company $company): array
    {
        $company->delete();
        return ['success' => true];
    }

    /**
     * assign staffs to company
     * @param AssignStaffRequest $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function assignStaff(AssignStaffRequest $request): Model
    {
        $staffIds = $request->input('staff');
        $staffIds = explode(',', $staffIds);
        $this->model->staff()->attach($staffIds);

        /** updated rep staff company id  */
        $staffs = Staff::whereIn('id', $staffIds)->get();
        if($staffs){
            $staffs->each(function (Staff $staff) {
                if($staff->is_sales_rep == 'Yes'){
                    $rep = Rep::where('staff_id', $staff->id)->first();
                    $rep->company_id = $this->model->id;
                    $rep->save();

                    if($rep){
                        $this->account->createRepCashAccount($rep);
                        $this->account->createRepChequeAccount($rep);
                    }
                }
                /** create a staff account */
                $this->account->createStaffAccount($staff);
            });
        }
        return $this->model;
    }

    /**
     * search staff for company
     * @param null $q
     * @return array
     */
    public function searchStaff($q = null): array
    {
        $assignedStaff = $this->model->staff->pluck('id')->toArray();
        if (!$q) {
            $staff = Staff::whereNotIn('id', $assignedStaff)->get(['id', 'short_name'])->toArray();
        } else {
            $staff = Staff::whereNotIn('id', $assignedStaff)
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
     * Remove staff from company
     * @param Staff $staff
     * @return array
     */
    public function removeStaff(Staff $staff): array
    {
        // Remove staff from department
        /** @var Department $departments */
        $departments = $this->model->departments;
        /** @var DepartmentRepository $departmentRepo */
        $departmentRepo = new DepartmentRepository();
        foreach ($departments as $department) {
            $departmentRepo->setModel($department);
            $departmentRepo->removeStaff($staff);
        }

        // Remove staff from stores
        /** @var Store $store */
        $stores = $this->model->stores;
        /** @var StoreRepository $storeRepo */
        $storeRepo = new StoreRepository();
        foreach ($stores as $store) {
            $storeRepo->setModel($store);
            $storeRepo->removeStaff($staff);
        }

        // remove staff from company
        $this->model->staff()->detach($staff->id);
        return ['success' => true, 'message' => 'Staff removed successfully!'];
    }

    /**
     * @return string
     */
    public function getLogoPath()
    {
        return $this->logoPath;
    }

    /**
     * Get the breadcrumbs of the company module
     * @param string $method
     * @param Company|null $company
     * @return array|mixed
     */
    public function breadcrumbs(string $method, Company $company = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Companies'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Companies', 'route' => 'setting.company.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Companies', 'route' => 'setting.company.index'],
                ['text' => $company->name ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Companies', 'route' => 'setting.company.index'],
                ['text' => $company->name ?? ''],
                ['text' => 'Edit'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}