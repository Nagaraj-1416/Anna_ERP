<?php

namespace App\Repositories\Sales;

use App\CustomerCredit;
use App\Http\Requests\Sales\CustomerCreditRequest;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomerCreditRepository
 * @package App\Repositories\Sales
 */
class CustomerCreditRepository extends BaseRepository
{

    /**
     * CustomerCreditRepository constructor.
     * @param CustomerCredit|null $credit
     */
    public function __construct(CustomerCredit $credit = null)
    {
        $this->setModel($credit ?? new CustomerCredit());
        $this->setCodePrefix('CCN');
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $userId = \request()->input('userId');
        $customerId = \request()->input('customerId');
        $lastWeek = carbon()->subWeek();
        $credits = CustomerCredit::whereIn('company_id', userCompanyIds(loggedUser()))
            ->orderBy('created_at', 'desc')->with('customer');
        if ($search) {
            $credits->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', '%' . $search . '%')
                    ->orWhere('date', 'LIKE', '%' . $search . '%')
                    ->orWhere('status', 'LIKE', '%' . $search . '%')
                    ->orWhere(function ($query) use ($search) {
                        $query->whereHas('customer', function ($q) use ($search) {
                            $q->where('display_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('mobile', 'LIKE', '%' . $search . '%');
                        });
                    });
            });
        }

        switch ($filter) {
            case 'Open':
                $credits->where('status', 'Open');
                break;
            case 'Closed':
                $credits->where('status', 'Closed');
                break;
            case 'Canceled':
                $credits->where('status', 'Canceled');
                break;
            case 'recentlyCreated':
                $credits->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $credits->where('updated_at', '>', $lastWeek);
                break;
        }

        if ($userId) {
            $credits->where('prepared_by', $userId);
        }

        if ($customerId) {
            $credits->where('customer_id', $customerId);
        }
        return $credits->paginate(12)->toArray();
    }

    /**
     * @param CustomerCreditRequest $request
     * @return Model
     */
    public function save(CustomerCreditRequest $request)
    {
        $request->merge(['code' => $this->getCode()]);
        $request->merge(['prepared_by' => auth()->id()]);
        $request->merge(['company_id' => 1]);
        $credit = $this->model->fill($request->toArray());
        $credit->save();
        return $credit;
    }

    /**
     * @param CustomerCreditRequest $request
     * @param CustomerCredit $credit
     * @return CustomerCredit
     */
    public function update(CustomerCreditRequest $request, CustomerCredit $credit)
    {
        $request->merge(['code' => $credit->code]);
        $this->setModel($credit);
        $this->model->update($request->toArray());
        return $credit;
    }

    /**
     * @param string $method
     * @param CustomerCredit|null $credit
     * @return array
     */
    public function breadcrumbs(string $method, CustomerCredit $credit = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Credit'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Credits', 'route' => 'sales.credit.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Credits', 'route' => 'sales.credit.index'],
                ['text' => $credit->code ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Credits', 'route' => 'sales.credit.index'],
                ['text' => $credit->code ?? ''],
                ['text' => 'Edit'],
            ],
            'print' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Credits', 'route' => 'sales.credit.index'],
                ['text' => $credit->code ?? ''],
                ['text' => 'Print'],
            ],
            'clone' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Credits', 'route' => 'sales.credit.index'],
                ['text' => $credit->code ?? ''],
                ['text' => 'Clone'],
            ],
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}