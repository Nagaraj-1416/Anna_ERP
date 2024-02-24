<?php

namespace App\Repositories\Purchase;

use App\SupplierCredit;
use App\Http\Requests\Purchase\CreditRefundRequest;
use App\Repositories\BaseRepository;
use App\SupplierCreditRefund;

/**
 * Class CreditRefundRepository
 * @package App\Repositories\Sales
 */
class CreditRefundRepository extends BaseRepository
{

    /**
     * CreditRefundRepository constructor.
     * @param SupplierCreditRefund $refund
     */
    public function __construct(SupplierCreditRefund $refund = null)
    {
        $this->setModel($refund ?? new SupplierCreditRefund());
        $this->setCodePrefix('SRF');
    }

    /**
     * @param CreditRefundRequest $request
     * @param SupplierCredit $credit
     * @return array
     */
    public function save(CreditRefundRequest $request, SupplierCredit $credit)
    {
        $request->merge(['credit_id' => $credit->id]);
        $refund = $this->model->fill($request->toArray());
        $refund->save();
        return $refund->toArray();
    }

    /**
     * @param string $method
     * @param SupplierCredit|null $credit
     * @return array
     */
    public function breadcrumbs(string $method, SupplierCredit $credit = null): array
    {
        $breadcrumbs = [
            'print' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Credits', 'route' => 'sales.credit.index'],
                ['text' => $credit->code ?? ''],
                ['text' => 'Print'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}