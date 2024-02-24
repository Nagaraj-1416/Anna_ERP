<?php

namespace App\Http\Controllers\Sales;

use App\Account;
use App\Company;
use App\Customer;
use App\DailySaleCustomer;
use App\Exports\CustomerExport;
use App\Http\Controllers\Controller;

use App\Http\Requests\Sales\CustomerOpeningStoreRequest;
use App\Http\Requests\Sales\CustomerStoreRequest;
use App\Http\Resources\Sales\CustomerResourceCollection;
use App\Repositories\Sales\CustomerRepository;
use App\Route;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class CustomerController extends Controller
{
    /**
     * @var CustomerRepository
     */
    protected $customer;
    protected $logoPath;

    /**
     * CustomerController constructor.
     * @param CustomerRepository $customer
     */
    public function __construct(CustomerRepository $customer)
    {
        $this->customer = $customer;
        $this->logoPath = $customer->getLogoPath();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', $this->customer->getModel());
        $breadcrumb = $this->customer->breadcrumbs('index');
        if (\request()->ajax()) {
            $customers = $this->customer->getCustomers();
            return response()->json($customers);
        }
        return view('sales.customer.index', compact('breadcrumb'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function dataTableData(Request $request)
    {
        if (\request()->ajax()) {
            return $this->customer->dataTable($request);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->customer->getModel());
        $breadcrumb = $this->customer->breadcrumbs('create');
        return view('sales.customer.create', compact('breadcrumb'));
    }

    /**
     * @param CustomerStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CustomerStoreRequest $request)
    {
        $this->authorize('store', $this->customer->getModel());
        $customer = $this->customer->save($request);
        if ($request->ajax()) {
            return response()->json($customer->toArray());
        }
        alert()->success('Customer created successfully', 'Success')->persistent();
        return redirect()->route('sales.customer.show', [$customer]);
    }

    /**
     * @param Customer $customer
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Customer $customer)
    {
        $this->authorize('show', $this->customer->getModel());
        $breadcrumb = $this->customer->breadcrumbs('show', $customer);
        $address = $customer->addresses->first();
        $contacts = $customer->contactPersons;

        /** sales returns */
        $returns = $customer->returns;
        $returns = $returns->map(function ($return) {
            $return->return_amount = $return->resolutions()->sum('amount');
            $return->no_of_items = $return->items()->count();
            $return->allocation_id = $return->allocation->id;
            $return->allocation_code = $return->allocation->code;
            if ($return->day_type = 'Single') {
                $return->allocation_range = $return->allocation->from_date;
            } else {
                $return->allocation_range = $return->allocation->from_date . ' to ' . $return->allocation->to_date;
            }
            return $return;
        });

        /** sales visit marks */
        $visits = DailySaleCustomer::where('customer_id', $customer->id)->get();;
        $visits = $visits->map(function ($visit) {
            if($visit->dailySale){
                $visit->allocation_id = $visit->dailySale->id;
                $visit->allocation_code = $visit->dailySale->code;
                if ($visit->dailySale->day_type = 'Single') {
                    $visit->allocation_range = $visit->dailySale->from_date;
                } else {
                    $visit->allocation_range = $visit->dailySale->from_date . ' to ' . $visit->dailySale->to_date;
                }
                $visit->visitedAt = date("F j, Y, g:i a", strtotime($visit->updated_at));
                $visit->createdAt = date("F j, Y, g:i a", strtotime($visit->created_at));
                $visit->rep = $visit->dailySale && $visit->dailySale->rep ? $visit->dailySale->rep->name : '';
            }
            return $visit;
        });

        return view('sales.customer.show', compact('breadcrumb', 'customer', 'address', 'contacts', 'returns', 'visits'));
    }

    /**
     * @param Customer $customer
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Customer $customer)
    {
        $this->authorize('edit', $this->customer->getModel());
        $breadcrumb = $this->customer->breadcrumbs('edit', $customer);
        $address = $customer->addresses->first();
        $customer->street_one = $address ? $address->street_one : '';
        $customer->street_two = $address ? $address->street_two : '';
        $customer->city = $address ? $address->city : '';
        $customer->province = $address ? $address->province : '';
        $customer->postal_code = $address ? $address->postal_code : '';
        $customer->country_id = $address ? $address->country_id : '';
        return view('sales.customer.edit', compact('breadcrumb', 'customer', 'address'));
    }

    /**
     * @param CustomerStoreRequest $request
     * @param Customer $customer
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CustomerStoreRequest $request, Customer $customer)
    {
        $this->authorize('update', $this->customer->getModel());
        $customer = $this->customer->update($request, $customer);
        alert()->success('Customer updated successfully', 'Success')->persistent();
        return redirect()->route('sales.customer.show', [$customer]);
    }

    /**
     * @param Customer $customer
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(Customer $customer): JsonResponse
    {
        $this->authorize('delete', $this->customer->getModel());
        $response = $this->customer->delete($customer);
        return response()->json($response);
    }

    /**
     * @param Customer $customer
     * @return mixed
     */
    public function getLogo(Customer $customer)
    {
        if ($customer->getAttribute('customer_logo')) {
            $imagePath = Storage::get($this->logoPath . $customer->getAttribute('customer_logo'));
        } else {
            $imagePath = Storage::get('data/default.png');
        }
        return response($imagePath)->header('Content-Type', 'image/jpg');
    }

    /**
     * @param null $q
     * @return JsonResponse
     */
    public function search($q = null): JsonResponse
    {
        $response = $this->customer->search($q, 'display_name', ['first_name', 'last_name', 'full_name', 'display_name'], ['is_active' => ['No']]);
        return response()->json($response);
    }

    /**
     * @param Company $company
     * @param null $q
     * @return JsonResponse
     */
    public function searchByCompany(Company $company, $q = null)
    {
        if ($q == null) {
            $customers = $company->customers()->get(['id', 'display_name', 'code'])->toArray();
        } else {
            $customers = $company->customers()->where('display_name', 'LIKE', '%' . $q . '%')->get()->toArray();
        }
        $customers = array_map(function ($obj) {
            return ["name" => $obj['display_name'] . ' (' . $obj['code'] . ')', "value" => $obj['id']];
        }, $customers);
        return response()->json(["success" => true, "results" => $customers]);
    }

    public function searchByRoute(Route $route, $q = null)
    {
        if ($q == null) {
            $customers = $route->customers()->get(['id', 'display_name', 'code'])->toArray();
        } else {
            $customers = $route->customers()->where('display_name', 'LIKE', '%' . $q . '%')->get()->toArray();
        }
        $customers = array_map(function ($obj) {
            return ["name" => $obj['display_name'] . ' (' . $obj['code'] . ')', "value" => $obj['id']];
        }, $customers);
        return response()->json(["success" => true, "results" => $customers]);
    }

    /**
     * @param Customer $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addLocation(Customer $customer)
    {
        $request = \request();
        $customer->gps_long = $request->input('lng');
        $customer->gps_lat = $request->input('lat');
        if ($request->input('route_id')) {
            $customer->route_id = $request->input('route_id');
        }
        $customer->save();
        alert()->success('Customer location successfully updated', 'Success')->autoclose(2000);
        return redirect()->back();
    }

    public function removeLocation(Customer $customer)
    {
        $customer->gps_long = null;
        $customer->gps_lat = null;
        $customer->save();
        return response()->json(['success' => true]);
    }

    /**
     * @param Customer $customer
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function statement(Customer $customer)
    {
        $this->authorize('statement', $this->customer->getModel());
        $breadcrumb = $this->customer->breadcrumbs('statement', $customer);
        $address = $customer->addresses->first();
        $contacts = $customer->contactPersons;

        $company = $customer->company;
        $companyAddress = $company->addresses()->first();

        $orders = $customer->orders->where('status', '!=', 'Canceled');
        $invoices = $customer->invoices->where('status', '!=', 'Canceled');
        $invoicesDue = $customer->invoices->where('status', '!=', 'Canceled');
        $payments = $customer->payments->where('status', '!=', 'Canceled');
        $estimates = $customer->estimates;
        $credits = $customer->credits;
        $dueInvoices = [];
        getDueCollection($invoicesDue, $dueInvoices);


        return view('sales.customer.statement.index',
            compact('breadcrumb', 'customer', 'address', 'contacts', 'company', 'companyAddress', 'orders',
                'invoices', 'payments', 'estimates', 'credits', 'dueInvoices'));
    }

    /**
     * @param Customer $customer
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function exportStatement(Customer $customer)
    {
        $this->authorize('statement', $this->customer->getModel());
        $address = $customer->addresses->first();

        $company = $customer->company;
        $companyAddress = $company->addresses()->first();

        $orders = $customer->orders;
        $invoices = $customer->invoices;
        $payments = $customer->payments;
        $estimates = $customer->estimates;
        $credits = $customer->credits;

        $data = [];
        $data['company'] = $company;
        $data['companyAddress'] = $companyAddress;
        $data['customer'] = $customer;
        $data['address'] = $address;
        $data['orders'] = $orders;
        $data['invoices'] = $invoices;
        $data['payments'] = $payments;
        $data['estimates'] = $estimates;
        $data['credits'] = $credits;

        $pdf = PDF::loadView('sales.customer.statement.export', $data);
        return $pdf->download(env('APP_NAME') . ' - Customer Statement (' . $customer->code . ')' . '.pdf');
    }

    /**
     * @return mixed
     */
    public function export()
    {
        if (\request()->input('type') == 'excel') {
            return $this->excelDownload();
        }
        $customers = Customer::with(['addresses', 'route', 'location'])->get();
        $data = [];
        $data['customers'] = $customers;
        ini_set("pcre.backtrack_limit", "2000000");
        ini_set('memory_limit', '256M');
        $pdf = PDF::loadView('sales.customer.export', $data);
        return $pdf->download(env('APP_NAME') . ' - Customers.pdf');
    }

    public function excelDownload()
    {
        return Excel::download(new CustomerExport(), env('APP_NAME') . ' - Customers.xlsx', 'Xlsx');
    }

    public function changeRoute()
    {
        $breadcrumb = $this->customer->breadcrumbs('change-route');
        $page = \request()->input('page');
        if (\request()->ajax()) {
            $customers = Customer::with(['route' => function ($q) {
                $q->select(['name', 'id']);
            }, 'location' => function ($q) {
                $q->select(['name', 'id', 'code']);
            }])->without('invoices')->select(['id', 'display_name', 'tamil_name', 'route_id', 'location_id'])
                ->paginate(100);
            return new CustomerResourceCollection($customers);
        }
        return view('sales.customer.change-route', compact('breadcrumb', 'page'));
    }

    public function updateRoute()
    {
        $request = request();
        $request->validate([
            'customers.*.changedRouteLocationId' => 'required',
            'customers.*.changedRouteId' => 'required'
        ]);
        $customers = $request->input('customers');
        foreach ($customers as $customer) {
            $oldCustomer = Customer::find(array_get($customer, 'id'));
            if (!$oldCustomer) continue;
            $oldCustomer->route_id = array_get($customer, 'changedRouteId');
            $oldCustomer->location_id = array_get($customer, 'changedRouteLocationId');
            $oldCustomer->save();
        }

        return response()->json(['success' => true]);
    }

    /**
     * @param Customer $customer
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createOpening(Customer $customer)
    {
        $breadcrumb = $this->customer->breadcrumbs('opening', $customer);
        return view('sales.customer.opening.create', compact('breadcrumb', 'customer'));
    }

    /**
     * @param Customer $customer
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editOpening(Customer $customer)
    {
        $customer->load('openingReferences.order.products');
        $openingData = [];
        $openingData['opening'] = $customer->getAttribute('opening_balance');
        $openingData['opening_at'] = $customer->getAttribute('opening_balance_at');
        $openingData['balance_type'] = $customer->getAttribute('opening_balance_type');
        $openingData['references'] = [];

        foreach ($customer->openingReferences as $reference){
            $order = $reference->order;
            if($order){
                $products = $order->products;
                $invoice = $order->invoices->first() ?? null;
                $ref = [
                    'invoice_id' => $invoice ? $invoice->id : null,
                    'order_id' => $order->id,
                    'reference_id' => $reference->getAttribute('id'),
                    'payment_id' => $invoice && $invoice->payments->first() ? $invoice->payments->first()->id : null,
                    'reference_no' => $reference->getAttribute('reference_no'),
                    'invoice_no' => $reference->getAttribute('invoice_no'),
                    'invoice_date' => $reference->getAttribute('invoice_date'),
                    'invoice_amount' => $reference->getAttribute('invoice_amount'),
                    'invoice_due' => (float) $reference->getAttribute('invoice_due'),
                    'invoice_due_age' => $reference->getAttribute('invoice_due_age'),
                    'products' => []
                ];
                foreach ($products as $product){
                    $productItem = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $product->pivot->quantity,
                        'rate' => $product->pivot->rate,
                        'total' => $product->pivot->amount
                    ];
                    array_push($ref['products'], $productItem);
                }
                array_push($openingData['references'], $ref);
            }
        }
        $breadcrumb = $this->customer->breadcrumbs('editOpening', $customer);
        return view('sales.customer.opening.edit', compact('breadcrumb', 'customer', 'openingData'));
    }

    /**
     * @param CustomerOpeningStoreRequest $request
     * @param Customer $customer
     * @return JsonResponse
     */
    public function storeOpening(CustomerOpeningStoreRequest $request, Customer $customer)
    {
        return $this->customer->saveOpening($request, $customer);
    }

    /**
     * @param CustomerOpeningStoreRequest $request
     * @param Customer $customer
     * @return JsonResponse
     */
    public function updateOpening(CustomerOpeningStoreRequest $request, Customer $customer)
    {
        return $this->customer->updateOpening($request, $customer);
    }

    public function ledger(Customer $customer)
    {
        $breadcrumb = $this->customer->breadcrumbs('ledger', $customer);

        if (request()->ajax()) {
            $request = request();
            $fromDate = $request->input('fromDate');
            $toDate = $request->input('toDate');

            $runningBalance = customerLedger2($customer, carbon($fromDate), carbon($toDate));

            $data = [];
            $data['trans'] = $runningBalance['trans'];
            $data['balances'] = $runningBalance;

            return response()->json($data);
        }
        return view('sales.customer.ledger', compact('breadcrumb', 'customer'));
    }
}
