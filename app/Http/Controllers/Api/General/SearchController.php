<?php

namespace App\Http\Controllers\API\General;

use App\Bank;
use App\Country;
use App\ExpenseType;
use App\Http\Controllers\Api\ApiController;
use App\Repositories\Finance\AccountRepository;
use App\Repositories\Purchase\SupplierRepository;
use App\Repositories\Sales\CustomerRepository;
use App\Repositories\Settings\{
    BusinessTypeRepository, PriceBookRepository, ProductRepository, RepRepository, RouteRepository, StoreRepository
};
use App\Route;
use App\UnitType;

/**
 * Class UserController
 * @package App\Http\Controllers\API\General
 */
class SearchController extends ApiController
{
    protected $businessType;
    protected $supplier;
    protected $product;
    protected $store;
    protected $route;
    protected $customer;
    protected $rep;
    protected $priceBook;
    protected $account;

    /**
     * SearchController constructor.
     * @param BusinessTypeRepository $businessType
     * @param SupplierRepository $supplier
     * @param ProductRepository $product
     * @param StoreRepository $store
     * @param RouteRepository $route
     * @param CustomerRepository $customer
     * @param RepRepository $rep
     * @param PriceBookRepository $priceBook
     * @param AccountRepository $account
     */
    public function __construct(
        BusinessTypeRepository $businessType,
        SupplierRepository $supplier,
        ProductRepository $product,
        StoreRepository $store,
        RouteRepository $route,
        CustomerRepository $customer,
        RepRepository $rep,
        PriceBookRepository $priceBook,
        AccountRepository $account
    )
    {
        $this->businessType = $businessType;
        $this->supplier = $supplier;
        $this->product = $product;
        $this->store = $store;
        $this->route = $route;
        $this->customer = $customer;
        $this->rep = $rep;
        $this->priceBook = $priceBook;
        $this->account = $account;
    }

    /**
     * Search business type
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function businessType($q = null)
    {
        $response = $this->businessType->search($q, 'name', ['name'], ['is_active' => ['No']]);
        return response()->json($response);
    }

    /**
     * Search supplier
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function supplier($q = null)
    {
        $response = $this->supplier->search($q, 'display_name', [
            'first_name',
            'last_name',
            'full_name',
            'display_name'
        ], ['is_active' => ['No']]);
        return response()->json($response);
    }

    /**
     * Search products
     * @param null $q
     * @param string $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function product($q = null, $type = 'All')
    {
        if ($type != 'All') {
            $response = $this->product->search($q, 'name', ['name'],
                ['is_active' => 'No'],
                [['type', $type]]);
        } else {
            $response = $this->product->search($q, 'name', ['name'], ['is_active' => 'No']);
        }
        return response()->json($response);
    }

    /**
     * Search store
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($q = null)
    {
        $company = userCompany(auth()->user());
        $companyId = $company->id ?? null;
        $response = $this->store->search($q, 'name', ['name'], ['is_active' => 'No'], ['company_id' => $companyId]);
        return response()->json($response);
    }

    /**
     * search the routes
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function route($q = null)
    {
        $company = userCompany(auth()->user());
        $companyId = $company->id ?? null;
        $response = $this->route->search($q, 'name', ['name', 'code'], [], [['company_id', $companyId]]);
        return response()->json($response);
    }

    /**
     * @param Route $route
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function routeLocation(Route $route, $q = null)
    {
        if ($q == null) {
            $locations = $route->locations()->get(['id', 'name', 'code'])->toArray();
        } else {
            $locations = $route->locations()->where('name', 'LIKE', '%' . $q . '%')
                ->get()->toArray();
        }
        $locations = array_map(function ($obj) {
            return ["name" => $obj['name'] . ' (' . $obj['code'] . ')', "value" => $obj['id']];
        }, $locations);
        return response()->json(["success" => true, "results" => $locations]);
    }

    /**
     * salutation for dropdown
     * @return \Illuminate\Http\JsonResponse
     */
    public function salutation()
    {
        $salutation = salutationDropDown();
        $salutation = array_map(function ($obj) {
            return ["name" => $obj, "value" => $obj];
        }, array_values($salutation));
        return response()->json(["success" => true, "results" => $salutation]);
    }

    /**
     * Search country
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function country($q = null)
    {
        if ($q == null) {
            $locations = Country::get(['id', 'name'])->toArray();
        } else {
            $locations = Country::where('name', 'LIKE', '%' . $q . '%')
                ->orWhere('capital', 'LIKE', '%' . $q . '%')->get()->toArray();
        }
        $locations = array_map(function ($obj) {
            return ["name" => $obj['name'], "value" => $obj['id']];
        }, $locations);
        return response()->json(["success" => true, "results" => $locations]);
    }

    /**
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function customer($q = null)
    {
        $company = userCompany(auth()->user());
        $companyId = $company->id ?? null;
        $response = $this->customer->search($q, 'display_name', [
            'first_name',
            'last_name',
            'full_name',
            'display_name'
        ], ['is_active' => 'No'], ['company_id' => $companyId]);
        return response()->json($response);
    }

    /**
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function rep($q = null)
    {
        $company = userCompany(auth()->user());
        $companyId = $company->id ?? null;
        $response = $this->rep->search($q, 'name', ['name'], ['is_active' => ['No']], ['company_id' => $companyId]);
        return response()->json($response);
    }

    /**
     * Search unit type search
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function unitType($q = null)
    {
        if ($q == null) {
            $unitTypes = UnitType::get(['id', 'name'])->toArray();
        } else {
            $unitTypes = UnitType::where('name', 'LIKE', '%' . $q . '%')
                ->orWhere('code', 'LIKE', '%' . $q . '%')->get()->toArray();
        }
        $unitTypes = array_map(function ($obj) {
            return ["name" => $obj['name'], "value" => $obj['id']];
        }, $unitTypes);
        return response()->json(["success" => true, "results" => $unitTypes]);
    }

    /**
     * Search price book
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function priceBook($q = null)
    {
        $company = userCompany(auth()->user());
        $companyId = $company->id ?? null;
        $response = $this->priceBook->search($q, 'name', ['name', 'code'], ['is_active' => ['No']], ['company_id' => $companyId]);
        return response()->json($response);
    }

    /**
     * Search bank
     * @param null $q
     * @return array
     */
    public function bank($q = null)
    {
        if (!$q) {
            $banks = Bank::get(['id', 'name'])->toArray();
        } else {
            $banks = Bank::where('name', 'LIKE', '%' . $q . '%')->get(['id', 'name'])->toArray();
        }
        $banks = array_map(function ($bank) {
            return ['name' => $bank['name'], 'value' => $bank['id']];
        }, $banks);
        return ["success" => true, "results" => $banks];
    }

    /**
     * search expense type
     * @param null $q
     * @return array
     */
    public function expenseType($q = null)
    {
        if (!$q) {
            $expenseType = ExpenseType::where('is_active', 'Yes')->where('is_mobile_enabled', 'Yes')->get(['id', 'name'])->toArray();
        } else {
            $expenseType = ExpenseType::where('is_active', 'Yes')->where('is_mobile_enabled', 'Yes')
                ->where('name', 'LIKE', '%' . $q . '%')->get(['id', 'name'])->toArray();
        }
        $expenseType = array_map(function ($bank) {
            $type = $this->getExpenseTypeLegend($bank['id']);
            return ['name' => $bank['name'], 'value' => $bank['id'], 'type' => $type];
        }, $expenseType);
        return ["success" => true, "results" => $expenseType];
    }

    protected function getExpenseTypeLegend($typeId)
    {
        switch ($typeId){
            case mileageTypeId();
                $legend = 'mileage';
                break;
            case fuelTypeId();
                $legend = 'fuel';
                break;
            case allowanceTypeId();
                $legend = 'allowance';
                break;
            default;
                $legend = 'general';
        }
        return $legend;
    }

    /**
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function depositedTo($q = null)
    {
        $response = $this->account->searchDepositToAccount($q);
        return response()->json($response);
    }
}
