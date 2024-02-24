<?php

namespace App\Repositories\Finance;

use App\Account;
use App\AccountCategory;
use App\AccountGroup;
use App\AccountType;
use App\Company;
use App\Customer;
use App\Http\Requests\Finance\AccountStoreRequest;
use App\ProductionUnit;
use App\Rep;
use App\Repositories\BaseRepository;
use App\Route;
use App\SalesLocation;
use App\Staff;
use App\Store;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class AccountRepository
 * @package App\Repositories\Finance
 */
class AccountRepository extends BaseRepository
{
    /**
     * AccountRepository constructor.
     * @param Account|null $Account
     */
    public function __construct(Account $Account = null)
    {
        $this->setModel($Account ?? new Account());
        $this->setCodePrefix('ACC', 'code');
    }

    public function grid()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $categoryId = \request()->input('category_id');
        $typeId = \request()->input('type_id');
        $lastWeek = carbon()->subWeek();
        $accounts = Account::orderBy('id', 'desc')
            ->with('type', 'category', 'transactions', 'group', 'company');
        if ($search) {
            $accounts->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', '%' . $search . '%')
                    ->orWhere('name', 'LIKE', '%' . $search . '%');
            });
        }
        switch ($filter) {
            case 'AssetAcc':
                $accounts->where('account_category_id', 1);
                break;
            case 'LiaAcc':
                $accounts->where('account_category_id', 2);
                break;
            case 'IncAcc':
                $accounts->where('account_category_id', 3);
                break;
            case 'ExpAcc':
                $accounts->where('account_category_id', 4);
                break;
            case 'EquAcc':
                $accounts->where('account_category_id', 5);
                break;
            case 'recentlyCreated':
                $accounts->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $accounts->where('updated_at', '>', $lastWeek);
                break;
        }
        if ($categoryId) {
            $accounts->where('account_category_id', $categoryId);
        }
        if ($typeId) {
            $accounts->where('account_type_id', $typeId);
        }
        return $accounts->paginate(15)->toArray();
    }

    /**
     * @param AccountStoreRequest $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function save(AccountStoreRequest $request)
    {
        $request->merge(['code' => $this->getCode()]);

        $accType = AccountType::where('id', $request->input('account_type_id'))->first();

        //$company = userCompany(auth()->user());

        $this->model->setAttribute('account_category_id', $accType->getAttribute('account_category_id'));

        $account = $this->model->fill($request->toArray());
        $account->save();
        return $account;
    }

    public function getGroups()
    {
        $categories = AccountCategory::where('is_active', 'Yes')->with(['groups' => function ($query) {
            $query->where('is_active', 'Yes')->with(['accounts' => function ($query) {
                $query->where('is_active', 'Yes');
            }]);
        }, 'accounts' => function ($query) {
            $query->where('group_id', null);
        }])->get();
        return $this->mapCategory($categories);
    }

    public function mapCategory($categories)
    {
        return $categories->map(function ($category) {
            $accountGroups = $this->mapGroup($category);
            $children = makeNested($accountGroups);
            $unGroupedAccounts = $this->mapAccount($category->accounts);
            $children = $children->merge($unGroupedAccounts);
            return $this->mapAmount($children, [
                'data_model' => 'AccountCategory',
                'id' => $category->id,
                'name' => $category->name,
                'code' => $category->code,
                'class' => 'table-primary',
                'children' => $children,
            ]);
        });
    }

    public function mapGroup($category)
    {
        return $category->groups->map(function ($group) {
            $children = $this->mapAccount($group->accounts);
            return $this->mapAmount($children, [
                'id' => $group->id,
                'name' => $group->name,
                'code' => $group->code,
                'parent_id' => $group->parent_id,
                'class' => 'table-success',
                'data_model' => 'AccountGroup',
                'children' => $children
            ]);
        });
    }

    public function mapAmount($accounts, $otherDate)
    {
        $data = [
            'debit' => (float)array_sum(array_pluck($accounts, 'debit')),
            'credit' => (float)array_sum(array_pluck($accounts, 'credit')),
            'balance' => (float)array_sum(array_pluck($accounts, 'balance')),
            'opening_balance' => (float)array_sum(array_pluck($accounts, 'opening_balance')),
        ];
        return array_merge($data, $otherDate);
    }

    public function mapAccount($accounts)
    {

        return $accounts->map(function ($account) {
            $balance = accBalance($account);
            return [
                'name' => $account->name,
                'code' => $account->code,
                'id' => $account->id,
                'class' => '',
                'data_model' => 'Account',
                'opening_balance' => $account->opening_balance ? $account->opening_balance : 0.00,
                'opening_balance_at' => carbon($account->opening_balance_at)->format('F j, Y'),
                'debit' => $balance['debit'] ?? 0.00,
                'credit' => $balance['credit'] ?? 0.00,
                'balance' => $balance['balance'] ?? 0.00,
            ];
        })->toArray();
    }

    public function createCustomerAccount(Customer $customer)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', $customer->getAttribute('display_name'));
        $account->setAttribute('short_name', $customer->getAttribute('display_name'));
        $account->setAttribute('accountable_id', $customer->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\Customer');
        $account->setAttribute('parent_account_id', 3); //Account Receivable
        $account->setAttribute('account_type_id', 3);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', $customer->getAttribute('company_id'));
        $account->setAttribute('opening_balance', $customer->getAttribute('opening_balance'));
        $account->setAttribute('opening_balance_at', $customer->getAttribute('opening_balance_at'));
        $account->setAttribute('opening_balance_type', $customer->getAttribute('opening_balance_type'));
        $account->setAttribute('group_id', 3); // Customers
        $account->save();
        return $account;
    }

    public function createSupplierAccount(Supplier $supplier)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', $supplier->getAttribute('display_name'));
        $account->setAttribute('short_name', $supplier->getAttribute('display_name'));
        $account->setAttribute('accountable_id', $supplier->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\Supplier');
        $account->setAttribute('parent_account_id', 8); //Account Payable
        $account->setAttribute('account_type_id', 8);
        $account->setAttribute('account_category_id', 2);
        $account->setAttribute('company_id', $supplier->getAttribute('company_id'));
        $account->setAttribute('opening_balance', $supplier->getAttribute('opening_balance'));
        $account->setAttribute('opening_balance_at', $supplier->getAttribute('opening_balance_at'));
        $account->setAttribute('opening_balance_type', $supplier->getAttribute('opening_balance_type'));
        $account->setAttribute('group_id', 9); // Suppliers
        $account->save();
        return $account;
    }

    /**
     * @param $request
     * @param $account
     * @return mixed
     */
    public function update($request, $account)
    {
        $this->setModel($account);
        $this->model->update($request->toArray());
        return $account;
    }

    public function delete()
    {

    }

    /**
     * @param null $q
     * @return array
     */
    public function searchExpenseAccount($q = null): array
    {
        $expenseCategoryIds = config('finance.expense_account_category_id', 4);
        $accounts = Account::whereIn('account_category_id', $expenseCategoryIds);
        if ($q) {
            $accounts = $accounts->where(function ($query) use ($q) {
                $query->where('name', 'LIKE', '%' . $q . '%')
                    ->orWhere('short_name', 'LIKE', '%' . $q . '%');
            });
        }
        $accounts = $accounts->get(['id', 'name'])->toArray();
        $accounts = array_map(function ($obj) {
            return ['name' => $obj['name'], 'value' => $obj['id']];
        }, $accounts);
        return ["success" => true, "results" => $accounts];
    }

    /**
     * @param null $q
     * @return array
     */
    public function searchPaidThroughAccount($q = null): array
    {
        $paidThroughTypeIds = config('finance.paid_through_account_type_id', 1);
        return $this->searchBaseType($q, $paidThroughTypeIds);
    }

    /**
     * @param null $q
     * @return array
     */
    public function searchDepositToAccount($q = null): array
    {
        $depositToTypeIds = config('finance.paid_deposit_to_account_type_id', 1);
        return $this->searchBaseType($q, $depositToTypeIds);
    }

    /**
     * @param null $q
     * @param $typeIds
     * @return array
     */
    public function searchBaseType($q = null, $typeIds)
    {
        $accounts = Account::whereIn('account_type_id', $typeIds);
        if ($q) {
            $accounts = $accounts->where(function ($query) use ($q) {
                $query->where('name', 'LIKE', '%' . $q . '%')
                    ->orWhere('short_name', 'LIKE', '%' . $q . '%');
            });
        }
        $accounts = $accounts->get(['id', 'name'])->toArray();
        $accounts = array_map(function ($obj) {
            return ['name' => $obj['name'], 'value' => $obj['id']];
        }, $accounts);
        return ["success" => true, "results" => $accounts];
    }

    /**
     * Get the breadcrumbs of the account module
     * @param string $method
     * @param Account|null $account
     * @return array|mixed
     */
    public function breadcrumbs(string $method, Account $account = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Chart Of Accounts'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Chart Of Accounts', 'route' => 'finance.account.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Chart Of Accounts', 'route' => 'finance.account.index'],
                ['text' => $account->name ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Chart Of Accounts', 'route' => 'finance.account.index'],
                ['text' => $account->name ?? ''],
                ['text' => 'Edit'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }


    public function getNavigationButtonData(Account $account, Carbon $from, Carbon $to)
    {
        return [
            'preview' => [
                'url' => route('finance.account.show', $account->id) . '?from=' . $from->copy()->subMonth()->toDateString() . '&to=' . $to->copy()->subMonth()->subDay()->toDateString(),
            ],
            'current' => [
                'url' => route('finance.account.show', $account->id),
                'label' => $from->copy()->format('jS \o\f F') . ' to  ' . $to->copy()->format('jS \o\f F')
            ],
            'next' => [
                'url' => route('finance.account.show', $account->id) . '?from=' . $from->copy()->addMonth()->toDateString() . '&to=' . $to->copy()->addMonth()->subDay()->toDateString(),
            ]
        ];
    }

    public function createCompanyCashAccount(Company $company)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', 'Cash - '.$company->getAttribute('name'));
        $account->setAttribute('short_name', $company->getAttribute('name'));
        $account->setAttribute('accountable_id', $company->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\Company');
        $account->setAttribute('parent_account_id', 1); // Cash
        $account->setAttribute('account_type_id', 1);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', $company->getAttribute('id'));
        $account->save();
        return $account;
    }

    public function createCompanyChequeAccount(Company $company)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', 'CIH - '.$company->getAttribute('name'));
        $account->setAttribute('short_name', $company->getAttribute('name'));
        $account->setAttribute('accountable_id', $company->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\Company');
        $account->setAttribute('parent_account_id', 50); // Cheques in Hand
        $account->setAttribute('account_type_id', 19);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', $company->getAttribute('id'));
        $account->save();
        return $account;
    }

    public function createRepCashAccount(Rep $rep)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('prefix', 'Cash');
        $account->setAttribute('name', 'Cash - '.$rep->getAttribute('name'));
        $account->setAttribute('short_name', $rep->getAttribute('name'));
        $account->setAttribute('accountable_id', $rep->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\Rep');
        $account->setAttribute('parent_account_id', 1); // Cash
        $account->setAttribute('account_type_id', 1);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', $rep->getAttribute('company_id'));
        $account->save();
        return $account;
    }

    public function createRepChequeAccount(Rep $rep)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('prefix', 'CIH');
        $account->setAttribute('name', 'CIH - '.$rep->getAttribute('name'));
        $account->setAttribute('short_name', $rep->getAttribute('name'));
        $account->setAttribute('accountable_id', $rep->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\Rep');
        $account->setAttribute('parent_account_id', 50); // Cheques in Hand
        $account->setAttribute('account_type_id', 19);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', $rep->getAttribute('company_id'));
        $account->save();
        return $account;
    }

    public function createShopCashAccount(SalesLocation $salesLocation)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', 'Cash - '.$salesLocation->getAttribute('name'));
        $account->setAttribute('short_name', $salesLocation->getAttribute('name'));
        $account->setAttribute('accountable_id', $salesLocation->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\SalesLocation');
        $account->setAttribute('parent_account_id', 1); // Cash
        $account->setAttribute('account_type_id', 1);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', $salesLocation->getAttribute('company_id'));
        $account->save();
        return $account;
    }

    public function createShopChequeAccount(SalesLocation $salesLocation)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', 'CIH - '.$salesLocation->getAttribute('name'));
        $account->setAttribute('short_name', $salesLocation->getAttribute('name'));
        $account->setAttribute('accountable_id', $salesLocation->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\SalesLocation');
        $account->setAttribute('parent_account_id', 50); // Cheques in Hand
        $account->setAttribute('account_type_id', 19);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', $salesLocation->getAttribute('company_id'));
        $account->save();
        return $account;
    }

    public function createStaffAccount(Staff $staff)
    {
        $company = $staff->companies()->first();
        if($company){
            $account = new Account();
            $account->setAttribute('code', $this->getCode());
            $account->setAttribute('prefix', 'Staff');
            $account->setAttribute('name', $staff->getAttribute('full_name'));
            $account->setAttribute('short_name', $staff->getAttribute('short_name'));
            $account->setAttribute('accountable_id', $staff->getAttribute('id'));
            $account->setAttribute('accountable_type', 'App\Staff');
            $account->setAttribute('account_type_id', 3);
            $account->setAttribute('account_category_id', 1);
            $account->setAttribute('company_id', $company->id);
            $account->save();
            return $account;
        }
    }

    public function createSpnAccount(SalesLocation $shop)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', 'SPN - '.$shop->getAttribute('name'));
        $account->setAttribute('short_name', $shop->getAttribute('name'));
        $account->setAttribute('accountable_id', $shop->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\SalesLocation');
        $account->setAttribute('account_type_id', 3);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', $shop->getAttribute('company_id'));
        $account->save();
        return $account;
    }

    public function createCompanySpnAccount(Company $company)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', 'SPN - '.$company->getAttribute('name'));
        $account->setAttribute('short_name', $company->getAttribute('name'));
        $account->setAttribute('accountable_id', $company->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\Company');
        $account->setAttribute('account_type_id', 3);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', $company->getAttribute('id'));
        $account->save();
        return $account;
    }

    public function createPUnitSpnAccount(ProductionUnit $unit)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', 'SPN - '.$unit->getAttribute('name'));
        $account->setAttribute('short_name', $unit->getAttribute('name'));
        $account->setAttribute('accountable_id', $unit->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\ProductionUnit');
        $account->setAttribute('account_type_id', 3);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', $unit->getAttribute('company_id'));
        $account->save();
        return $account;
    }

    public function createStoreSpnAccount(Store $store)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', 'SPN - '.$store->getAttribute('name'));
        $account->setAttribute('short_name', $store->getAttribute('name'));
        $account->setAttribute('accountable_id', $store->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\Store');
        $account->setAttribute('account_type_id', 3);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', $store->getAttribute('company_id'));
        $account->save();
        return $account;
    }

    public function createCompanyAccount(Company $company)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', $company->getAttribute('name'));
        $account->setAttribute('short_name', $company->getAttribute('name'));
        $account->setAttribute('accountable_id', $company->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\Company');
        $account->setAttribute('account_type_id', 3);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', $company->getAttribute('id'));
        $account->save();
        return $account;
    }

    public function createPUnitAccount(ProductionUnit $unit)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', $unit->getAttribute('name'));
        $account->setAttribute('short_name', $unit->getAttribute('name'));
        $account->setAttribute('accountable_id', $unit->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\ProductionUnit');
        $account->setAttribute('account_type_id', 3);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', $unit->getAttribute('company_id'));
        $account->save();
        return $account;
    }

    public function createStoreAccount(Store $store)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', $store->getAttribute('name'));
        $account->setAttribute('short_name', $store->getAttribute('name'));
        $account->setAttribute('accountable_id', $store->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\Store');
        $account->setAttribute('account_type_id', 3);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', $store->getAttribute('company_id'));
        $account->save();
        return $account;
    }

    public function createShopAccount(SalesLocation $shop)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', $shop->getAttribute('name'));
        $account->setAttribute('short_name', $shop->getAttribute('name'));
        $account->setAttribute('accountable_id', $shop->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\SalesLocation');
        $account->setAttribute('account_type_id', 3);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', $shop->getAttribute('company_id'));
        $account->save();
        return $account;
    }

    public function createPurchaseAccount()
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', 'Purchase');
        $account->setAttribute('short_name', 'Purchase');
        $account->setAttribute('is_default', 'Yes');
        $account->setAttribute('account_type_id', 3);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', 1);
        $account->save();
        return $account;
    }

    public function createPurchaseReturnAccount()
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', 'Purchase Return');
        $account->setAttribute('short_name', 'Purchase Return');
        $account->setAttribute('is_default', 'Yes');
        $account->setAttribute('account_type_id', 3);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', 1);
        $account->save();
        return $account;
    }

    public function createPurchaseCompanyAccount(Company $company)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', 'Purchase - '.$company->getAttribute('name'));
        $account->setAttribute('short_name', $company->getAttribute('name'));
        $account->setAttribute('accountable_id', $company->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\Company');
        $account->setAttribute('account_type_id', 3);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', $company->getAttribute('id'));
        $account->save();
        return $account;
    }

    public function createSalesCompanyAccount(Company $company)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', 'Sales - '.$company->getAttribute('name'));
        $account->setAttribute('short_name', $company->getAttribute('name'));
        $account->setAttribute('accountable_id', $company->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\Company');
        $account->setAttribute('account_type_id', 11);
        $account->setAttribute('account_category_id', 3);
        $account->setAttribute('company_id', $company->getAttribute('id'));
        $account->save();
        return $account;
    }

    public function createPurchasePUnitAccount(ProductionUnit $unit)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', 'Purchase - '.$unit->getAttribute('name'));
        $account->setAttribute('short_name', $unit->getAttribute('name'));
        $account->setAttribute('accountable_id', $unit->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\ProductionUnit');
        $account->setAttribute('account_type_id', 3);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', $unit->getAttribute('company_id'));
        $account->save();
        return $account;
    }

    public function createSalesPUnitAccount(ProductionUnit $unit)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', 'Sales - '.$unit->getAttribute('name'));
        $account->setAttribute('short_name', $unit->getAttribute('name'));
        $account->setAttribute('accountable_id', $unit->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\ProductionUnit');
        $account->setAttribute('account_type_id', 11);
        $account->setAttribute('account_category_id', 3);
        $account->setAttribute('company_id', $unit->getAttribute('company_id'));
        $account->save();
        return $account;
    }

    public function createPurchaseStoreAccount(Store $store)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', 'Purchase - '.$store->getAttribute('name'));
        $account->setAttribute('short_name', $store->getAttribute('name'));
        $account->setAttribute('accountable_id', $store->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\Store');
        $account->setAttribute('account_type_id', 3);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', $store->getAttribute('company_id'));
        $account->save();
        return $account;
    }

    public function createSalesStoreAccount(Store $store)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', 'Sales - '.$store->getAttribute('name'));
        $account->setAttribute('short_name', $store->getAttribute('name'));
        $account->setAttribute('accountable_id', $store->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\Store');
        $account->setAttribute('account_type_id', 11);
        $account->setAttribute('account_category_id', 3);
        $account->setAttribute('company_id', $store->getAttribute('company_id'));
        $account->save();
        return $account;
    }

    public function createPurchaseShopAccount(SalesLocation $shop)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', 'Purchase - '.$shop->getAttribute('name'));
        $account->setAttribute('short_name', $shop->getAttribute('name'));
        $account->setAttribute('accountable_id', $shop->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\SalesLocation');
        $account->setAttribute('account_type_id', 3);
        $account->setAttribute('account_category_id', 1);
        $account->setAttribute('company_id', $shop->getAttribute('company_id'));
        $account->save();
        return $account;
    }

    public function createSalesShopAccount(SalesLocation $shop)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('name', 'Sales - '.$shop->getAttribute('name'));
        $account->setAttribute('short_name', $shop->getAttribute('name'));
        $account->setAttribute('accountable_id', $shop->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\SalesLocation');
        $account->setAttribute('account_type_id', 11);
        $account->setAttribute('account_category_id', 3);
        $account->setAttribute('company_id', $shop->getAttribute('company_id'));
        $account->save();
        return $account;
    }

    public function createCompanyCommissionAccount(Company $company)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('prefix', 'Commission');
        $account->setAttribute('name', 'Commission - '.$company->getAttribute('name'));
        $account->setAttribute('short_name', $company->getAttribute('name'));
        $account->setAttribute('accountable_id', $company->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\Company');
        $account->setAttribute('account_type_id', 8);
        $account->setAttribute('account_category_id', 2);
        $account->setAttribute('company_id', $company->getAttribute('id'));
        $account->save();
        return $account;
    }

    public function createRepCommissionAccount(Rep $rep)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('prefix', 'Commission');
        $account->setAttribute('name', 'Commission - '.$rep->getAttribute('name'));
        $account->setAttribute('short_name', $rep->getAttribute('name'));
        $account->setAttribute('accountable_id', $rep->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\Rep');
        $account->setAttribute('account_type_id', 8);
        $account->setAttribute('account_category_id', 2);
        $account->setAttribute('company_id', $rep->getAttribute('company_id'));
        $account->save();
        return $account;
    }

    public function createVehicleAccount(SalesLocation $vehicle)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('prefix', 'General');
        $account->setAttribute('name', $vehicle->getAttribute('name'));
        $account->setAttribute('short_name', $vehicle->getAttribute('name'));
        $account->setAttribute('accountable_id', $vehicle->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\SalesLocation');
        $account->setAttribute('account_type_id', 16);
        $account->setAttribute('account_category_id', 4);
        $account->setAttribute('company_id', $vehicle->getAttribute('company_id'));
        $account->save();
        return $account;
    }

    public function createCompanyVanGoodsShortageAccount(Company $company)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('prefix', 'VanGoodsShortage');
        $account->setAttribute('name', 'VGS - '.$company->getAttribute('name'));
        $account->setAttribute('short_name', $company->getAttribute('name'));
        $account->setAttribute('accountable_id', $company->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\Company');
        $account->setAttribute('account_type_id', 8);
        $account->setAttribute('account_category_id', 2);
        $account->setAttribute('company_id', $company->getAttribute('id'));
        $account->save();
        return $account;
    }

    public function createCompanyVanGoodsExcessAccount(Company $company)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('prefix', 'VanGoodsExcess');
        $account->setAttribute('name', 'VGE - '.$company->getAttribute('name'));
        $account->setAttribute('short_name', $company->getAttribute('name'));
        $account->setAttribute('accountable_id', $company->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\Company');
        $account->setAttribute('account_type_id', 8);
        $account->setAttribute('account_category_id', 2);
        $account->setAttribute('company_id', $company->getAttribute('id'));
        $account->save();
        return $account;
    }

    public function createIssuedChequeAccount(Company $company)
    {
        $account = new Account();
        $account->setAttribute('code', $this->getCode());
        $account->setAttribute('prefix', 'IssuedCheque');
        $account->setAttribute('name', 'IssuedCheque - '.$company->getAttribute('name'));
        $account->setAttribute('short_name', 'IssuedCheque - '.$company->getAttribute('name'));
        $account->setAttribute('accountable_id', $company->getAttribute('id'));
        $account->setAttribute('accountable_type', 'App\Company');
        $account->setAttribute('account_type_id', 9);
        $account->setAttribute('account_category_id', 2);
        $account->setAttribute('company_id', $company->getAttribute('id'));
        $account->save();
        return $account;
    }

    public function createDriverCommissionAccount(Staff $staff)
    {
        $company = $staff->companies()->first();
        if($company){
            $account = new Account();
            $account->setAttribute('code', $this->getCode());
            $account->setAttribute('prefix', 'Commission');
            $account->setAttribute('name', 'Commission - '.$staff->getAttribute('short_name'));
            $account->setAttribute('short_name', $staff->getAttribute('short_name'));
            $account->setAttribute('accountable_id', $staff->getAttribute('id'));
            $account->setAttribute('accountable_type', 'App\Staff');
            $account->setAttribute('account_type_id', 8);
            $account->setAttribute('account_category_id', 2);
            $account->setAttribute('company_id', $company->id);
            $account->save();
            return $account;
        }
    }

    public function createLabourCommissionAccount(Staff $staff)
    {
        $company = $staff->companies()->first();
        if($company){
            $account = new Account();
            $account->setAttribute('code', $this->getCode());
            $account->setAttribute('prefix', 'Commission');
            $account->setAttribute('name', 'Commission - '.$staff->getAttribute('short_name'));
            $account->setAttribute('short_name', $staff->getAttribute('short_name'));
            $account->setAttribute('accountable_id', $staff->getAttribute('id'));
            $account->setAttribute('accountable_type', 'App\Staff');
            $account->setAttribute('account_type_id', 8);
            $account->setAttribute('account_category_id', 2);
            $account->setAttribute('company_id', $company->id);
            $account->save();
            return $account;
        }
    }

}
