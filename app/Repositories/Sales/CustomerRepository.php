<?php

namespace App\Repositories\Sales;

use App\Company;
use App\DailySaleCustomer;
use App\Repositories\BaseRepository;
use App\Repositories\Finance\AccountRepository;
use App\Repositories\General\ContactPersonRepository;
use App\Route;
use App\Store;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\{Account, Address, Customer, Invoice, InvoicePayment, OpeningBalanceReference, Rep, SalesOrder, User};

/**
 * Class CustomerRepository
 * @package App\Repositories\Sales
 */
class CustomerRepository extends BaseRepository
{
    protected $logoPath = 'customer-logos/';
    protected $contactPerson;
    protected $account;
    protected $order;
    protected $invoice;
    protected $payment;

    /**
     * CustomerRepository constructor.
     * @param Customer|null $customer
     * @param ContactPersonRepository $contactPerson
     * @param AccountRepository $account
     * @param OrderRepository $order
     * @param InvoiceRepository $invoice
     * @param PaymentRepository $payment
     */
    public function __construct(
        Customer $customer = null,
        ContactPersonRepository $contactPerson,
        AccountRepository $account,
        OrderRepository $order,
        InvoiceRepository $invoice,
        PaymentRepository $payment
    )
    {
        $this->setModel($customer ?? new Customer());
        $this->setCodePrefix('CUS');
        $this->contactPerson = $contactPerson;
        $this->account = $account;
        $this->order = $order;
        $this->invoice = $invoice;
        $this->payment = $payment;
    }

    /**
     * Get data to data table
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['code', 'salutation', 'first_name', 'last_name', 'full_name', 'display_name', 'phone', 'fax', 'mobile', 'email',
            'website', 'type', 'gps_lat', 'gps_long', 'notes', 'is_active'];
        $searchingColumns = ['code', 'salutation', 'first_name', 'last_name', 'full_name', 'display_name', 'phone', 'fax', 'mobile', 'email',
            'website', 'type', 'gps_lat', 'gps_long', 'notes', 'is_active'];
        $data = $this->getTableData($request, $columns, $searchingColumns);
        $data['data'] = array_map(function ($item) {
            $item['code'] = '<a href="' . route('sales.customer.show', $item['id']) . '">' . $item['code'] . '</a>';
            $item['action'] = "<div class=\"button-group\">";
            $item['action'] .= actionBtn('Show', null, ['sales.customer.show', [$item['id']]], ['class' => 'btn-success']);
            $item['action'] .= actionBtn('Edit', null, ['sales.customer.edit', [$item['id']]]);
            $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-customer']);
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    /**
     * Get auth customers
     * @param User|null $user
     * @return Collection
     */
    public function index(User $user = null): Collection
    {
        if (!$user) {
            $user = auth()->user();
        }

        if ($user->isRepUser()) {
            $staff = $user->staffs->first();
            if ($staff && $staff->rep) {
                $routesId = $staff->rep->routes->pluck('id')->toArray();
                return Customer::whereIn('route_id', $routesId)->get();
            }
            return collect();
        }

        return Customer::all();
    }

    /**
     * @param User|null $user
     * @return Collection
     */
    public function todayIndex(User $user = null): Collection
    {
        if (!$user) {
            $user = auth()->user();
        }
        $customers = collect();
        if ($user->isRepUser()) {
            $staff = $user->staffs->first();
            if ($staff && $staff->rep) {
                $customers = getAllAllocatedCustomers();
            }
        } else {
            $customers = Customer::with(['route', 'location', 'company', 'contactPersons', 'addresses.country'])->get();
        }
        return $customers->transform(function ($item) {
            $total = ordersOutStanding($item->orders);
            $item->balance_cl = max(($item->cl_amount - $total), 0);
            $item->current_cl_days = debtorBalanceAgeAnalysis($item);
            $item->outstanding = cusOutstanding($item);
            $item->outstanding_orders = cusOutstandingOrders($item);
            $item->not_realized_cheque = cusNotRealizedCheque($item);
            return $item;
        });
    }

    /**
     * @param Request $request
     * @return Customer | Model
     */
    public function save(Request $request)
    {
        $request->merge(['code' => $this->getCode()]);
        $request->merge(['type' => 'External']);

        /** get route */
        $route = Route::find($request->input('route_id'));

        $fullName = $request->input('salutation') . ' ' . $request->input('first_name')
            . ' ' . $request->input('last_name');
        $request->merge(['full_name' => $fullName]);
        $request->merge(['company_id' => $route ? $route->company_id : env('DEFAULT_COMPANY_ID')]);

        if (!$request->input('is_active')) {
            $request->merge(['is_active' => 'Yes']);
        }

        $customer = $this->model->fill($request->toArray());
        $customer->save();

        /** associate address */
        $addressable = $this->transformAddress($request);
        if (count($addressable) > 0) {
            $customer->addresses()->saveMany($addressable);
        }

        /** upload customer logo to storage - if logo attached only */
        $logoFile = $request->file('logo_file');
        if ($logoFile) {
            $logoType = $logoFile->getClientOriginalExtension();
            $logoName = $customer->getAttribute('code') . '.' . $logoType;
            Storage::put($this->logoPath . $logoName, file_get_contents($logoFile));

            /** update customer logo name to row item */
            $customer->setAttribute('customer_logo', $logoName);
            $customer->save();
        }

        if ($request->input('contact_persons') && is_array($request->input('contact_persons'))) {
            $this->contactPerson->storeFromArray($request->input('contact_persons'), $customer);
        }
        $customer->outstanding = cusOutstanding($customer);

        /** create a chart of account */
        /*if ($customer) {
            $this->account->createCustomerAccount($customer);
        }*/

        /** THIS IS FOR MOBILE - Allocation created customer to an allocation */
        if($request->input('daily_sale_id')) {
            $dailySaleCustomer = new DailySaleCustomer();
            $dailySaleCustomer->setAttribute('daily_sale_id', $request->input('daily_sale_id'));
            $dailySaleCustomer->setAttribute('customer_id', $customer->getAttribute('id'));
            $dailySaleCustomer->setAttribute('added_stage', 'Later');
            $dailySaleCustomer->save();
        }

        return $customer->load('route', 'location', 'company', 'contactPersons', 'addresses.country');
    }

    public function saveCashSalesCustomer(Request $request)
    {
        $request->merge(['code' => $this->getCode()]);
        $request->merge(['type' => 'External']);
        $request->merge(['category' => 'Shop']);
        $company = userCompany(auth()->user());
        $request->merge(['company_id' => $company->id]);
        $customer = $this->model->fill($request->toArray());
        $customer->save();
        return $customer;
    }

    /**
     * @param Request $request
     * @param Customer $customer
     * @return Customer
     */
    public function update(Request $request, Customer $customer)
    {
        $request->merge(['code' => $customer->code]);
        $request->merge(['type' => $customer->type]);

        $this->setModel($customer);

        if (!$request->input('salutation')) {
            $request->merge(['salutation' => $customer->salutation]);
        }

        if (!$request->input('first_name')) {
            $request->merge(['first_name' => $customer->first_name]);
        }

        if (!$request->input('last_name')) {
            $request->merge(['last_name' => $customer->last_name]);
        }
        $fullName = $request->input('salutation') . ' ' . $request->input('first_name')
            . ' ' . $request->input('last_name');
        $request->merge(['full_name' => $fullName]);

        $this->model->update($request->toArray());

        /** updated associated address */
        $address = $customer->addresses->first();
        if ($address) {
            $address->update($request->toArray());
        } else {
            /** associate address */
            $addressable = $this->transformAddress($request);
            if (count($addressable) > 0) {
                $customer->addresses()->saveMany($addressable);
            }
        }

        /** upload customer logo to storage - if logo attached only */
        $logoFile = $request->file('logo_file');
        if ($logoFile) {
            /** remove already available logo if new logo attached */
            /*Storage::delete($this->logoPath . $customer->getAttribute('customer_logo'));
            $customer->setAttribute('customer_logo', null);
            $customer->save();*/

            /** upload the new logo to storage and update raw data item */
            $logoType = $logoFile->getClientOriginalExtension();
            $logoName = $customer->getAttribute('code') . '.' . $logoType;
            Storage::put($this->logoPath . $logoName, file_get_contents($logoFile));

            /** update customer logo name to row item */
            $customer->setAttribute('customer_logo', $logoName);
            $customer->save();
        }
        $customer->outstanding = cusOutstanding($customer);
        return $customer->load('route', 'location', 'company', 'contactPersons', 'addresses.country');
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
     * @param Customer $customer
     * @return array
     */
    public function delete(Customer $customer): array
    {
        try {
            $customer->delete();
            return ['success' => true, 'message' => 'deleted success'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'deleted failed'];
        }
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
     * @param Customer|null $customer
     * @return array|mixed
     */
    public function breadcrumbs(string $method, Customer $customer = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Customers'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Customers', 'route' => 'sales.customer.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Customers', 'route' => 'sales.customer.index'],
                ['text' => $customer->display_name ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Customers', 'route' => 'sales.customer.index'],
                ['text' => $customer->display_name ?? ''],
                ['text' => 'Edit'],
            ],
            'statement' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Customers', 'route' => 'sales.customer.index'],
                ['text' => $customer->display_name ?? ''],
            ],
            'change-route' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Customers', 'route' => 'sales.customer.index'],
                ['text' => 'Customers & Routes'],
            ],
            'opening' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Customers', 'route' => 'sales.customer.index'],
                ['text' => $customer->display_name ?? '', 'route' => 'sales.customer.show', 'parameters' => [$customer->id ?? null]],
                ['text' => 'Add Opening Balance'],
            ],
            'editOpening' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Customers', 'route' => 'sales.customer.index'],
                ['text' => $customer->display_name ?? '', 'route' => 'sales.customer.show', 'parameters' => [$customer->id ?? null]],
                ['text' => 'Edit Opening Balance'],
            ],
            'ledger' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Customers', 'route' => 'sales.customer.index'],
                ['text' => $customer->display_name ?? '', 'route' => 'sales.customer.show', 'parameters' => [$customer->id ?? null]],
                ['text' => 'Ledger'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    public function getCustomers()
    {
        $customers = Customer::whereIn('company_id', userCompanyIds(loggedUser()))
            ->with('route')->orderBy('created_at', 'DESC');
        $lastWeek = carbon()->subWeek();
        $filter = request()->input('filter');
        $search = request()->input('search');
        $id = request()->input('salesRepId');
        $route = request()->input('routeId');
        $displayName = request()->input('displayName');
        if ($filter == 'active') {
            $customers = $customers->where('is_active', 'Yes');
        } else if ($filter == 'inActive') {
            $customers = $customers->where('is_active', 'No');
        } else if ($filter == 'located') {
            $customers = $customers->where('gps_lat', '!=', null);
        } else if ($filter == 'notLocated') {
            $customers = $customers->where('gps_lat', null);
        } else if ($filter == 'recentlyCreated') {
            $customers = $customers->where('created_at', '>', $lastWeek);
        } else if ($filter == 'recentlyModified') {
            $customers = $customers->where('updated_at', '>', $lastWeek);;
        } else if ($filter == 'Top10') {
            $orders = DB::table('sales_orders')
                ->select('customer_id', DB::raw('SUM(total) as total_sales'))
                ->groupBy('customer_id')->orderBy('total_sales', 'DESC')
                ->take(10)->get()->pluck('customer_id')->toArray();
            $customers = $customers->whereIn('id', $orders);
        }

        if ($search) {
            $customers->where('code', 'LIKE', '%' . $search . '%')
                ->orWhere('first_name', 'LIKE', '%' . $search . '%')
                ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                ->orWhere('full_name', 'LIKE', '%' . $search . '%')
                ->orWhere('display_name', 'LIKE', '%' . $search . '%')
                ->orWhere('phone', 'LIKE', '%' . $search . '%')
                ->orWhere('fax', 'LIKE', '%' . $search . '%')
                ->orWhere('mobile', 'LIKE', '%' . $search . '%')
                ->orWhere('email', 'LIKE', '%' . $search . '%')
                ->orwhere(function ($query) use ($search) {
                    $query->whereHas('route', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                    });
                })
                ->orwhere(function ($query) use ($search) {
                    $query->whereHas('company', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                    });
                });
        }
        if ($displayName) {
            $customers->where('display_name', 'LIKE', $displayName . '%');
        }
        if ($id) {
            $rep = Rep::find($id);
            if ($rep && $rep->routes()->first() && $routeId = $rep->routes()->first()->id) {
                $customers->where('route_id', $routeId);
            }
        }
        if ($route) {
            $customers->where(function ($query) use ($search, $route) {
                $query->whereHas('route', function ($q) use ($search, $route) {
                    $q->where('route_id', $route);
                });
            });
        }
        $customers = $customers->paginate(12)->toArray();
        return $customers;
    }

    /**
     * @param Request $request
     * @param Customer $customer
     * @return array
     */
    public function notVisit(Request $request, Customer $customer)
    {
        $allocations = getRepAllocation()->first();
        $dailySalesCustomers = $allocations ? $allocations->customers : null;
        $dailySalesCustomer = $dailySalesCustomers->where('customer_id', $customer->id)->first();
        if (!$dailySalesCustomer) {
            return ['success' => false, 'message' => "doesn't allocate " . $customer->display_name . " for you today."];
        }
        $dailySalesCustomer->update([
            'reason' => $request->input('reason'),
            'gps_lat' => $request->input('gps_lat'),
            'gps_long' => $request->input('gps_long'),
            'is_visited' => $request->input('is_visited', 'No'),
        ]);
        return ['success' => true, 'message' => 'updated'];
    }

    public function updateLocation(Request $request, Customer $customer)
    {
        $customer->setAttribute('gps_lat', $request->input('gps_lat'));
        $customer->setAttribute('gps_long', $request->input('gps_long'));
        $customer->save();
        return $customer;
    }

    /**
     * @param Request $request
     * @param Customer $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveOpening(Request $request, Customer $customer)
    {
        $account = Account::where('accountable_id', $customer->id)->where('accountable_type', 'App\Customer')->first();

        if ($account) {
            $account->setAttribute('opening_balance', $request->input('opening'));
            $account->setAttribute('opening_balance_at', $request->input('opening_at'));
            $account->setAttribute('opening_balance_type', $request->input('balance_type'));
            $account->save();
        }

        $customer->setAttribute('opening_balance', $request->input('opening'));
        $customer->setAttribute('opening_balance_at', $request->input('opening_at'));
        $customer->setAttribute('opening_balance_type', $request->input('balance_type'));
        $customer->save();

        foreach ($request->input('references') as $reference) {
            $referenceModel = new OpeningBalanceReference();
            $referenceModel->setAttribute('invoice_no', $reference['invoice_no'] ?? null);
            $referenceModel->setAttribute('invoice_date', $reference['invoice_date'] ?? null);
            $referenceModel->setAttribute('invoice_amount', $reference['invoice_amount'] ?? null);
            $referenceModel->setAttribute('invoice_due', $reference['invoice_due'] ?? null);
            $referenceModel->setAttribute('invoice_due_age', $reference['invoice_due_age'] ?? null);
            $referenceModel->setAttribute('customer_id', $customer->id ?? null);
            $referenceModel->setAttribute('date', now()->toDateString());
            $referenceModel->setAttribute('updated_by', auth()->id());

            $order = $this->createOpeningOrder($customer, $reference);

            $referenceModel->setAttribute('order_id', $order->id);
            $referenceModel->save();
        }
        return response()->json(['success' => true]);
    }


    public function updateOpening(Request $request, Customer $customer)
    {
        $account = Account::where('accountable_id', $customer->id)->where('accountable_type', 'App\Customer')->first();

        if ($account) {
            $account->setAttribute('opening_balance', $request->input('opening'));
            $account->setAttribute('opening_balance_at', $request->input('opening_at'));
            $account->setAttribute('opening_balance_type', $request->input('balance_type'));
            $account->save();
        }

        $customer->setAttribute('opening_balance', $request->input('opening'));
        $customer->setAttribute('opening_balance_at', $request->input('opening_at'));
        $customer->setAttribute('opening_balance_type', $request->input('balance_type'));
        $customer->save();

        foreach ($request->input('references') as $reference) {
            $referenceModel = new OpeningBalanceReference();
            if (isset($reference['reference_id']) && $reference['reference_id']) {
                $referenceModel = OpeningBalanceReference::find($reference['reference_id']);
            }
            $referenceModel->setAttribute('invoice_no', $reference['invoice_no'] ?? null);
            $referenceModel->setAttribute('invoice_date', $reference['invoice_date'] ?? null);
            $referenceModel->setAttribute('invoice_amount', $reference['invoice_amount'] ?? null);
            $referenceModel->setAttribute('invoice_due', $reference['invoice_due'] ?? null);
            $referenceModel->setAttribute('invoice_due_age', $reference['invoice_due_age'] ?? null);
            $referenceModel->setAttribute('customer_id', $customer->id ?? null);
            $referenceModel->setAttribute('date', now()->toDateString());
            $referenceModel->setAttribute('updated_by', auth()->id());
            $order = $this->createOpeningOrder($customer, $reference);
            $referenceModel->setAttribute('order_id', $order->id);
            $referenceModel->save();
        }
        return response()->json(['success' => true]);
    }

    /**
     * @param SalesOrder $order
     * @param array $products
     * @return array
     */
    public function mapProducts(SalesOrder $order, array $products)
    {
        $mappedProducts = [];
        foreach ($products as $product) {
            if (!$product['product_id']) continue;
            $mappedProduct = [
                'sales_order_id' => $order->id ?? null,
                'price_book_id' => null,
                'unit_type_id' => null,
                'product_id' => $product['product_id'] ?? null,
                'store_id' => null,
                'quantity' => $product['quantity'] ?? null,
                'rate' => $product['rate'],
                'discount_type' => 'Amount',
                'discount_rate' => 0,
                'discount' => 0,
                'amount' => $product['total'] ?? 0,
                'status' => 'Pending',
                'notes' => 'Added on opening balance',
            ];
            array_push($mappedProducts, $mappedProduct);
        }
        return $mappedProducts;
    }

    /**
     * @param Customer $customer
     * @param $reference
     * @return SalesOrder
     */
    public function createOpeningOrder(Customer $customer, $reference)
    {
        $products = $reference['products'] ?? [];

        $company = userCompany();

        if (isset($reference['order_id']) && $reference['order_id']) {

            /** @var SalesOrder $order */
            $order = SalesOrder::find($reference['order_id']);
            $order->setAttribute('order_no', $order->getAttribute('order_no'));
            $order->setAttribute('ref', $order->getAttribute('ref'));
            $order->setAttribute('prepared_by', $order->getAttribute('prepared_by'));
            $order->setAttribute('company_id', $order->getAttribute('company_id'));

        } else {

            $order = new SalesOrder();
            $order->setAttribute('order_no', $this->order->getCode());
            $order->setAttribute('ref', $this->order->generateRef());
            $order->setAttribute('prepared_by', auth()->id());
            $order->setAttribute('company_id', $customer->company_id ?? null);

        }

        $order->setAttribute('order_date', $reference['invoice_date'] ?? 0.00);
        $order->setAttribute('sub_total', $reference['invoice_amount'] ?? 0.00);
        $order->setAttribute('total', $reference['invoice_amount'] ?? 0.00);
        $order->setAttribute('is_opining', 'Yes');
        $order->setAttribute('discount_type', 'Amount');
        $order->setAttribute('discount_rate', 0);
        $order->setAttribute('discount', 0);
        $order->setAttribute('adjustment', 0);
        $order->setAttribute('order_type', 'Direct');
        $order->setAttribute('delivery_date', $reference['invoice_date'] ?? 0.00);
        $order->setAttribute('sales_category', 'Office');
        $order->setAttribute('customer_id', $customer->id);
        $order->setAttribute('route_id', $customer->route_id);
        $order->setAttribute('is_invoiced', 'Yes');
        $order->setAttribute('status', 'Open');
        $order->setAttribute('delivery_status', 'Delivered');

        if (($reference['invoice_due'] ?? 0) > 0) {
            $order->setAttribute('is_credit_sales', 'Yes');
        }

        $order->save();

        $products = $this->mapProducts($order, $products);
        if (isset($reference['order_id']) && $reference['order_id']) {
            $order->products()->detach();
        }
        $order->products()->attach($products);
        $this->createOpeningInvoice($order, $reference);
        return $order->refresh();
    }

    /**
     * @param SalesOrder $order
     * @param array $reference
     * @return Invoice
     */
    public function createOpeningInvoice(SalesOrder $order, array $reference)
    {
        if (isset($reference['invoice_id']) && $reference['invoice_id']) {
            /** @var Invoice $invoice */
            $invoice = Invoice::find($reference['invoice_id']);
            $invoice->setAttribute('invoice_no', $invoice->getAttribute('invoice_no'));
            $invoice->setAttribute('ref', $invoice->getAttribute('ref'));
            $invoice->setAttribute('prepared_by', $invoice->getAttribute('prepared_by'));
        } else {
            $invoice = new Invoice();
            $invoice->setAttribute('invoice_no', $this->invoice->getCode());
            $invoice->setAttribute('ref', $this->invoice->generateRef());
            $invoice->setAttribute('prepared_by', auth()->id());
        }

        $invoice->setAttribute('sales_order_id', $order->getAttribute('id'));
        $invoice->setAttribute('invoice_date', $order->getAttribute('order_date'));
        $invoice->setAttribute('due_date', $order->getAttribute('order_date'));
        $invoice->setAttribute('customer_id', $order->getAttribute('customer_id'));
        $invoice->setAttribute('business_type_id', $order->getAttribute('business_type_id'));
        $invoice->setAttribute('company_id', $order->getAttribute('company_id'));
        $invoice->setAttribute('notes', 'Created on opening adding');
        $invoice->setAttribute('sales_location_id', $order->getAttribute('sales_location_id'));
        $invoice->setAttribute('amount', $order->getAttribute('total'));
        $invoice->setAttribute('customer_id', $order->getAttribute('customer_id'));
        $invoice->setAttribute('company_id', $order->getAttribute('company_id'));
        $invoice->setAttribute('route_id', $order->getAttribute('route_id'));
        $invoice->setAttribute('is_opening', 'Yes');
        $amount = ($reference['invoice_amount'] ?? 0) - ($reference['invoice_due'] ?? 0);

        if (($reference['invoice_due'] ?? 0) == 0) {
            $invoice->setAttribute('status', 'Paid');

            /** update order status if due amount is Zero */
            $order->setAttribute('status', 'Closed');
            $order->save();
        } else {
            $invoice->setAttribute('status', 'Partially Paid');
        }
        $invoice->save();
        if ($amount > 0) {
            $this->createOpeningPayment($invoice, $reference, $amount);
        }
        return $invoice->refresh();
    }

    /**
     * @param Invoice $invoice
     * @param $reference
     * @param $amount
     * @return InvoicePayment
     */
    public function createOpeningPayment(Invoice $invoice, $reference, $amount)
    {
        if (isset($reference['payment_id']) && $reference['payment_id']) {
            $payment = InvoicePayment::find($reference['payment_id']);
        } else {
            $payment = new InvoicePayment();
        }

        $payment->setAttribute('prepared_by', $invoice->getAttribute('prepared_by'));
        $payment->setAttribute('invoice_id', $invoice->getAttribute('id'));
        $payment->setAttribute('sales_order_id', $invoice->getAttribute('sales_order_id'));
        $payment->setAttribute('customer_id', $invoice->getAttribute('customer_id'));
        $payment->setAttribute('business_type_id', $invoice->getAttribute('business_type_id'));
        $payment->setAttribute('company_id', $invoice->getAttribute('company_id'));
        $payment->setAttribute('notes', 'Created on opening adding');
        $payment->setAttribute('sales_location_id', $invoice->getAttribute('sales_location_id'));
        $payment->setAttribute('payment_date', $invoice->getAttribute('invoice_date'));
        $payment->setAttribute('payment', $amount);
        $payment->setAttribute('payment_mode', 'Cash');
        $payment->setAttribute('payment_type', 'Advanced');
        $payment->setAttribute('status', 'Paid');

        $payment->setAttribute('route_id', $invoice->getAttribute('route_id'));
        $payment->setAttribute('is_opening', 'Yes');

        $payment->save();
        return $payment->refresh();
    }

    public function internalCustomer(Company $company)
    {
        $customer = new Customer();
        $customer->setAttribute('code', $this->getCode());
        $customer->setAttribute('first_name', $company->name);
        $customer->setAttribute('full_name', $company->name);
        $customer->setAttribute('display_name', $company->name);
        $customer->setAttribute('phone', $company->phone);
        $customer->setAttribute('fax', $company->fax);
        $customer->setAttribute('mobile', $company->mobile);
        $customer->setAttribute('email', $company->email);
        $customer->setAttribute('website', $company->website);
        $customer->setAttribute('type', 'Internal');
        $customer->setAttribute('notes', 'Internal Customer Account');
        $customer->setAttribute('company_id', $company->id);
        $customer->save();
        return $customer;
    }

    public function internalCustomerStore(Store $store)
    {
        $customer = new Customer();
        $customer->setAttribute('code', $this->getCode());
        $customer->setAttribute('first_name', $store->name);
        $customer->setAttribute('full_name', $store->name);
        $customer->setAttribute('display_name', $store->name);
        $customer->setAttribute('phone', $store->phone);
        $customer->setAttribute('fax', $store->fax);
        $customer->setAttribute('mobile', $store->mobile);
        $customer->setAttribute('email', $store->email);
        $customer->setAttribute('type', 'Internal');
        $customer->setAttribute('notes', 'Internal Customer Account for Store');
        $customer->setAttribute('company_id', $store->company_id);
        $customer->save();
        return $customer;
    }

}
