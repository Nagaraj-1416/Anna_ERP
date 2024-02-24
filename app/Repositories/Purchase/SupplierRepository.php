<?php

namespace App\Repositories\Purchase;

use App\Address;
use App\Company;
use App\Http\Requests\Purchase\SupplierStoreRequest;
use App\ProductionUnit;
use App\Repositories\BaseRepository;
use App\Repositories\Finance\AccountRepository;
use App\Store;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class SupplierRepository
 * @package App\Repositories\Settings
 */
class SupplierRepository extends BaseRepository
{
    protected $logoPath = 'supplier-logos/';
    protected $account;

    /**
     * SupplierRepository constructor.
     * @param Supplier|null $supplier
     * @param AccountRepository $account
     */
    public function __construct(Supplier $supplier = null, AccountRepository $account)
    {
        $this->setModel($supplier ?? new Supplier());
        $this->setCodePrefix('SUP');
        $this->account = $account;
    }

    /**
     * Get data to data table
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['code', 'salutation', 'first_name', 'last_name', 'full_name', 'display_name', 'phone',
            'fax', 'mobile', 'email', 'website', 'type', 'notes', 'is_active'];
        $searchingColumns = ['code', 'salutation', 'first_name', 'last_name', 'full_name', 'display_name', 'phone',
            'fax', 'mobile', 'email', 'website', 'type', 'notes', 'is_active'];
        $data = $this->getTableData($request, $columns, $searchingColumns);
        $data['data'] = array_map(function ($item) {
            $item['code'] = '<a href="' . route('purchase.supplier.show', $item['id']) . '">' . $item['code'] . '</a>';
            $item['action'] = "<div class=\"button-group\">";
            $item['action'] .= actionBtn('Show', null, ['purchase.supplier.show', [$item['id']]], ['class' => 'btn-success']);
            $item['action'] .= actionBtn('Edit', null, ['purchase.supplier.edit', [$item['id']]]);
            $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-supplier']);
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    public function save(SupplierStoreRequest $request)
    {
        $request->merge(['code' => $this->getCode()]);
        $request->merge(['type' => 'External']);

        $fullName = $request->input('salutation') . ' ' . $request->input('first_name')
            . ' ' . $request->input('last_name');
        $request->merge(['full_name' => $fullName]);
        $request->merge(['company_id' => 1]);

        $supplier = $this->model->fill($request->toArray());
        $supplier->save();

        /** associate address */
        $addressable = $this->transformAddress($request);
        if (count($addressable) > 0) {
            $supplier->addresses()->saveMany($addressable);
        }

        /** create a chart of account */
        if($supplier){
            $this->account->createSupplierAccount($supplier);
        }

        /** upload supplier logo to storage - if logo attached only */
        $logoFile = $request->file('logo_file');
        if ($logoFile) {
            $logoType = $logoFile->getClientOriginalExtension();
            $logoName = $supplier->getAttribute('code') . '.' . $logoType;
            Storage::put($this->logoPath . $logoName, file_get_contents($logoFile));

            /** update supplier logo name to row item */
            $supplier->setAttribute('supplier_logo', $logoName);
            $supplier->save();
        }
        return $supplier;
    }

    /**
     * @param SupplierStoreRequest $request
     * @param Supplier $supplier
     * @return Supplier
     */
    public function update(SupplierStoreRequest $request, Supplier $supplier)
    {
        $request->merge(['code' => $supplier->code]);
        $request->merge(['type' => $supplier->type]);

        $this->setModel($supplier);

        $fullName = $request->input('salutation') . ' ' . $request->input('first_name')
            . ' ' . $request->input('last_name');
        $request->merge(['full_name' => $fullName]);

        $this->model->update($request->toArray());

        /** updated associated address */
        $address = $supplier->addresses->first();
        if ($address) {
            $address->update($request->toArray());
        } else {
            /** associate address */
            $addressable = $this->transformAddress($request);
            if (count($addressable) > 0) {
                $supplier->addresses()->saveMany($addressable);
            }
        }

        /** upload supplier logo to storage - if logo attached only */
        $logoFile = $request->file('logo_file');
        if ($logoFile) {
            /** remove already available logo if new logo attached */
            /*Storage::delete($this->logoPath . $supplier->getAttribute('supplier_logo'));
            $supplier->setAttribute('supplier_logo', null);
            $supplier->save();*/

            /** upload the new logo to storage and update raw data item */
            $logoType = $logoFile->getClientOriginalExtension();
            $logoName = $supplier->getAttribute('code') . '.' . $logoType;
            Storage::put($this->logoPath . $logoName, file_get_contents($logoFile));

            /** update supplier logo name to row item */
            $supplier->setAttribute('supplier_logo', $logoName);
            $supplier->save();
        }
        return $supplier;
    }

    /**
     * @param $request
     * @return array
     */
    private function transformAddress($request)
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
     * @param Supplier $supplier
     * @return array
     * @throws \Exception
     */
    public function delete(Supplier $supplier): array
    {
        $supplier->delete();
        return ['success' => true];
    }

    /**
     * @return string
     */
    public function getLogoPath()
    {
        return $this->logoPath;
    }

    /**
     * Get the breadcrumbs of the supplier module
     * @param string $method
     * @param Supplier|null $supplier
     * @return array|mixed
     */
    public function breadcrumbs(string $method, Supplier $supplier = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Suppliers'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Suppliers', 'route' => 'purchase.supplier.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Suppliers', 'route' => 'purchase.supplier.index'],
                ['text' => $supplier->full_name ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Suppliers', 'route' => 'purchase.supplier.index'],
                ['text' => $supplier->full_name ?? ''],
                ['text' => 'Edit'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    public function getSuppliers()
    {
        $suppliers = Supplier::orderBy('created_at', 'DESC');
        $lastWeek = carbon()->subWeek();
        $filter = request()->input('filter');
        $search = request()->input('search');
        if ($filter == 'active') {
            $suppliers = $suppliers->where('is_active', 'Yes');
        } else if ($filter == 'inActive') {
            $suppliers = $suppliers->where('is_active', 'No');
        } else if ($filter == 'recentlyCreated') {
            $suppliers = $suppliers->where('created_at', '>', $lastWeek);
        } else if ($filter == 'recentlyModified') {
            $suppliers = $suppliers->where('updated_at', '>', $lastWeek);
        } else if ($filter == 'top10') {
            $orders = DB::table('purchase_orders')
                ->select('supplier_id', DB::raw('SUM(total) as total_sales'))
                ->groupBy('supplier_id')->orderBy('total_sales', 'DESC')
                ->take(10)->get()->pluck('supplier_id')->toArray();
            $suppliers = $suppliers->whereIn('id', $orders);
        }
        if ($search) {
            $suppliers->where('full_name', 'LIKE', '%' . $search . '%')
                ->orWhere('code', 'LIKE', '%' . $search . '%')
                ->orWhere('first_name', 'LIKE', '%' . $search . '%')
                ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                ->orWhere('full_name', 'LIKE', '%' . $search . '%')
                ->orWhere('display_name', 'LIKE', '%' . $search . '%')
                ->orWhere('phone', 'LIKE', '%' . $search . '%')
                ->orWhere('fax', 'LIKE', '%' . $search . '%')
                ->orWhere('mobile', 'LIKE', '%' . $search . '%')
                ->orWhere('email', 'LIKE', '%' . $search . '%')
                ->orwhere(function ($query) use ($search) {
                    $query->whereHas('company', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                    });
                });
        }
        return $suppliers->paginate(12)->toArray();
    }

    public function internalSupplier(Company $company)
    {
        $supplier = new Supplier();
        $supplier->setAttribute('code', $this->getCode());
        $supplier->setAttribute('first_name', $company->name);
        $supplier->setAttribute('full_name', $company->name);
        $supplier->setAttribute('display_name', $company->name);
        $supplier->setAttribute('phone', $company->phone);
        $supplier->setAttribute('fax', $company->fax);
        $supplier->setAttribute('mobile', $company->mobile);
        $supplier->setAttribute('email', $company->email);
        $supplier->setAttribute('website', $company->website);
        $supplier->setAttribute('type', 'Internal');
        $supplier->setAttribute('notes', 'Internal Supplier Account');
        $supplier->setAttribute('company_id', $company->id);
        $supplier->setAttribute('supplierable_id', $company->id);
        $supplier->setAttribute('supplierable_type', 'App\Company');
        $supplier->save();
        return $supplier;
    }

    public function internalSupplierPu(ProductionUnit $unit)
    {
        $supplier = new Supplier();
        $supplier->setAttribute('code', $this->getCode());
        $supplier->setAttribute('first_name', $unit->name);
        $supplier->setAttribute('full_name', $unit->name);
        $supplier->setAttribute('display_name', $unit->name);
        $supplier->setAttribute('phone', $unit->phone);
        $supplier->setAttribute('fax', $unit->fax);
        $supplier->setAttribute('mobile', $unit->mobile);
        $supplier->setAttribute('email', $unit->email);
        $supplier->setAttribute('type', 'Internal');
        $supplier->setAttribute('notes', 'Internal Supplier Account for Production Unit');
        $supplier->setAttribute('company_id', $unit->company_id);
        $supplier->setAttribute('supplierable_id', $unit->id);
        $supplier->setAttribute('supplierable_type', 'App\ProductionUnit');
        $supplier->save();
        return $supplier;
    }

    public function internalSupplierStore(Store $store)
    {
        $supplier = new Supplier();
        $supplier->setAttribute('code', $this->getCode());
        $supplier->setAttribute('first_name', $store->name);
        $supplier->setAttribute('full_name', $store->name);
        $supplier->setAttribute('display_name', $store->name);
        $supplier->setAttribute('phone', $store->phone);
        $supplier->setAttribute('fax', $store->fax);
        $supplier->setAttribute('mobile', $store->mobile);
        $supplier->setAttribute('email', $store->email);
        $supplier->setAttribute('type', 'Internal');
        $supplier->setAttribute('notes', 'Internal Supplier Account for Stores');
        $supplier->setAttribute('company_id', $store->company_id);
        $supplier->setAttribute('supplierable_id', $store->id);
        $supplier->setAttribute('supplierable_type', 'App\Store');
        $supplier->save();
        return $supplier;
    }

}