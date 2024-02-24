<?php

namespace App\Repositories\Purchase;

use App\Http\Requests\Purchase\SupplierCreditRequest;
use App\Repositories\BaseRepository;
use App\SupplierCredit;

/**
 * Class SupplierCreditRepository
 * @package App\Repositories\Sales
 */
class SupplierCreditRepository extends BaseRepository
{

    /**
     * SupplierCreditRepository constructor.
     * @param SupplierCredit|null $credit
     */
    public function __construct(SupplierCredit $credit = null)
    {
        $this->setModel($credit ?? new SupplierCredit());
        $this->setCodePrefix('SCN');
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $userId = \request()->input('userId');
        $supplierId = \request()->input('supplierId');
        $lastWeek = carbon()->subWeek();
        $credits = SupplierCredit::orderBy('created_at', 'desc')->with('supplier');
        if ($search) {
            $credits->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', '%' . $search . '%')
                    ->orWhere('date', 'LIKE', '%' . $search . '%')
                    ->orWhere('status', 'LIKE', '%' . $search . '%')
                    ->orWhere(function ($query) use ($search) {
                        $query->whereHas('supplier', function ($q) use ($search) {
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

        if ($supplierId) {
            $credits->where('supplier_id', $supplierId);
        }
        return $credits->paginate(12)->toArray();
    }

    /**
     * @param SupplierCreditRequest $request
     * @return array
     */
    public function save(SupplierCreditRequest $request)
    {
        $request->merge(['code' => $this->getCode()]);
        $request->merge(['prepared_by' => auth()->id()]);
        $request->merge(['company_id' => 1]);
        $credit = $this->model->fill($request->toArray());
        $credit->save();
        return $credit->toArray();
    }

    /**
     * @param SupplierCreditRequest $request
     * @param SupplierCredit $credit
     * @return array
     */
    public function update(SupplierCreditRequest $request, SupplierCredit $credit)
    {
        $request->merge(['code' => $credit->code]);
        $this->setModel($credit);
        $this->model->update($request->toArray());
        return $credit->toArray();
    }

    /**
     * @param string $method
     * @param SupplierCredit|null $credit
     * @return array
     */
    public function breadcrumbs(string $method, SupplierCredit $credit = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Credit'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Credits', 'route' => 'purchase.credit.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Credits', 'route' => 'purchase.credit.index'],
                ['text' => $credit->code ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Credits', 'route' => 'purchase.credit.index'],
                ['text' => $credit->code ?? ''],
                ['text' => 'Edit'],
            ],
            'print' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Credits', 'route' => 'purchase.credit.index'],
                ['text' => $credit->code ?? ''],
                ['text' => 'Print'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}