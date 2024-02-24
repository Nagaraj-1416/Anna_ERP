<?php

namespace App\Http\Controllers\Purchase;

use App\Account;
use App\Company;
use App\Http\Controllers\Controller;

use App\Http\Requests\Purchase\SupplierOpeningStoreRequest;
use App\Http\Requests\Purchase\SupplierStoreRequest;
use App\OpeningBalanceReference;
use App\Repositories\Purchase\SupplierRepository;
use App\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;
use function Symfony\Component\Debug\Tests\testHeader;

class SupplierController extends Controller
{
    /**
     * @var SupplierRepository
     */
    protected $supplier;
    protected $logoPath;

    /**
     * SupplierController constructor.
     * @param SupplierRepository $supplier
     */
    public function __construct(SupplierRepository $supplier)
    {
        $this->supplier = $supplier;
        $this->logoPath = $supplier->getLogoPath();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', $this->supplier->getModel());
        $breadcrumb = $this->supplier->breadcrumbs('index');
        if (request()->ajax()) {
            $suppliers = $this->supplier->getSuppliers();
            return response()->json($suppliers);
        }
        return view('purchases.supplier.index', compact('breadcrumb'));
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function dataTableData(Request $request)
    {
        $this->authorize('index', $this->supplier->getModel());
        if (\request()->ajax()) {
            return $this->supplier->dataTable($request);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->supplier->getModel());
        $breadcrumb = $this->supplier->breadcrumbs('create');
        return view('purchases.supplier.create', compact('breadcrumb'));
    }

    /**
     * @param SupplierStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(SupplierStoreRequest $request)
    {
        $this->authorize('store', $this->supplier->getModel());
        $supplier = $this->supplier->save($request);
        alert()->success('Supplier created successfully', 'Success')->persistent();
        return redirect()->route('purchase.supplier.show', [$supplier]);
    }

    /**
     * @param Supplier $supplier
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Supplier $supplier)
    {
        $this->authorize('show', $this->supplier->getModel());
        $breadcrumb = $this->supplier->breadcrumbs('show', $supplier);
        $address = $supplier->addresses->first();
        $contacts = $supplier->contactPersons;
        return view('purchases.supplier.show', compact('breadcrumb', 'supplier', 'address', 'contacts'));
    }

    /**
     * @param Supplier $supplier
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Supplier $supplier)
    {
        $this->authorize('edit', $this->supplier->getModel());
        $breadcrumb = $this->supplier->breadcrumbs('edit', $supplier);
        $address = $supplier->addresses->first();
        $supplier->street_one = $address ? $address->street_one : '';
        $supplier->street_two = $address ? $address->street_two : '';
        $supplier->city = $address ? $address->city : '';
        $supplier->province = $address ? $address->province : '';
        $supplier->postal_code = $address ? $address->postal_code : '';
        $supplier->country_id = $address ? $address->country_id : '';
        return view('purchases.supplier.edit', compact('breadcrumb', 'supplier', 'address'));
    }

    /**
     * @param SupplierStoreRequest $request
     * @param Supplier $supplier
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(SupplierStoreRequest $request, Supplier $supplier)
    {
        $this->authorize('update', $this->supplier->getModel());
        $supplier = $this->supplier->update($request, $supplier);
        alert()->success('Supplier updated successfully', 'Success')->persistent();
        return redirect()->route('purchase.supplier.show', [$supplier]);
    }

    /**
     * @param Supplier $supplier
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(Supplier $supplier): JsonResponse
    {
        $this->authorize('delete', $this->supplier->getModel());
        $response = $this->supplier->delete($supplier);
        return response()->json($response);
    }

    /**
     * Search
     * @param null $q
     * @return JsonResponse
     */
    public function search($q = null): JsonResponse
    {
        $response = $this->supplier->search($q, 'display_name', ['first_name', 'last_name', 'full_name', 'display_name'], ['is_active' => ['No']]);
        return response()->json($response);
    }

    /**
     * @param Supplier $supplier
     * @return mixed
     */
    public function getLogo(Supplier $supplier)
    {
        if ($supplier->getAttribute('supplier_logo')) {
            $imagePath = Storage::get($this->logoPath . $supplier->getAttribute('supplier_logo'));
        } else {
            $imagePath = Storage::get('data/default.png');
        }
        return response($imagePath)->header('Content-Type', 'image/jpg');
    }

    /**
     * @param Supplier $supplier
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function statement(Supplier $supplier)
    {
        $this->authorize('statement', $this->supplier->getModel());
        $breadcrumb = $this->supplier->breadcrumbs('statement', $supplier);
        $address = $supplier->addresses->first();
        $contacts = $supplier->contactPersons;

        $company = $supplier->company;
        $companyAddress = $company->addresses()->first();

        $orders = $supplier->orders;
        $bills = $supplier->bills;
        $payments = $supplier->payments;
        $credits = $supplier->credits;
        $dueBills = [];
        getDueCollection($bills, $dueBills);
        return view('purchases.supplier.statement.index',
            compact('breadcrumb', 'supplier', 'address', 'contacts', 'company', 'companyAddress', 'orders',
                'bills', 'payments', 'credits', 'dueBills'));
    }

    /**
     * @param Supplier $supplier
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function exportStatement(Supplier $supplier)
    {
        $this->authorize('statement', $this->supplier->getModel());
        $address = $supplier->addresses->first();

        $company = $supplier->company;
        $companyAddress = $company->addresses()->first();

        $orders = $supplier->orders;
        $bills = $supplier->bills;
        $payments = $supplier->payments;
        $credits = $supplier->credits;

        $data = [];
        $data['company'] = $company;
        $data['companyAddress'] = $companyAddress;
        $data['supplier'] = $supplier;
        $data['address'] = $address;
        $data['orders'] = $orders;
        $data['bills'] = $bills;
        $data['payments'] = $payments;
        $data['credits'] = $credits;

        $pdf = PDF::loadView('purchases.supplier.statement.export', $data);
        return $pdf->download(env('APP_NAME') . ' - Supplier Statement (' . $supplier->code . ')' . '.pdf');
    }

    public function storeOpening(Supplier $supplier, SupplierOpeningStoreRequest $request)
    {
        $account = Account::where('accountable_id', $supplier->id)->where('accountable_type', 'App\Supplier')->first();

        if($account){
            $account->setAttribute('opening_balance', $request->input('opening'));
            $account->setAttribute('opening_balance_at', $request->input('opening_at'));
            $account->setAttribute('opening_balance_type', $request->input('balance_type'));
            $account->save();
        }

        $supplier->setAttribute('opening_balance', $request->input('opening'));
        $supplier->setAttribute('opening_balance_at', $request->input('opening_at'));
        $supplier->setAttribute('opening_balance_type', $request->input('balance_type'));
        $supplier->save();

        foreach ($request->input('references') as $reference){
            $referenceModel = new OpeningBalanceReference();
            $referenceModel->setAttribute('bill_no', $reference['bill_no'] ?? null);
            $referenceModel->setAttribute('bill_date', $reference['bill_date'] ?? null);
            $referenceModel->setAttribute('bill_amount', $reference['bill_amount'] ?? null);
            $referenceModel->setAttribute('bill_due', $reference['bill_due'] ?? null);
            $referenceModel->setAttribute('bill_due_age', $reference['bill_due_age'] ?? null);
            $referenceModel->setAttribute('supplier_id', $supplier->id ?? null);
            $referenceModel->setAttribute('date', now()->toDateString());
            $referenceModel->setAttribute('updated_by', auth()->id());
            $referenceModel->save();
        }
        return response()->json(['success' => true]);
    }

    public function searchByType($type, $q = null)
    {
        if($type == 'Store') {
            $suppliers = Supplier::whereIn('supplierable_type', ['App\ProductionUnit', 'App\Store']);
        }else if($type == 'Shop') {
            $suppliers = Supplier::where('supplierable_type', 'App\Store');
        }else{
            $suppliers = Supplier::whereIn('company_id', userCompanyIds(loggedUser()));
        }

        if ($q == null) {
            $suppliers = $suppliers->get(['id', 'display_name', 'code'])->toArray();
        } else {
            $suppliers = $suppliers->where('display_name', 'LIKE', '%' . $q . '%')->get()->toArray();
        }
        $suppliers = array_map(function ($obj) {
            return ["name" => $obj['display_name'], "value" => $obj['id']];
        }, $suppliers);
        return response()->json(["success" => true, "results" => $suppliers]);
    }


}
