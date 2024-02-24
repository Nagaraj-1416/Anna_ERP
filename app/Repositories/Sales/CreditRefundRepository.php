<?php

namespace App\Repositories\Sales;

use App\CustomerCredit;
use App\CustomerCreditRefund;
use App\Http\Requests\Sales\CreditRefundRequest;
use App\Repositories\BaseRepository;

/**
 * Class CreditRefundRepository
 * @package App\Repositories\Sales
 */
class CreditRefundRepository extends BaseRepository
{

    /**
     * CreditRefundRepository constructor.
     * @param CustomerCreditRefund|null $refund
     */
    public function __construct(CustomerCreditRefund $refund = null)
    {
        $this->setModel($refund ?? new CustomerCreditRefund());
        $this->setCodePrefix('CRF');
    }

    /**
     * @param CreditRefundRequest $request
     * @param CustomerCredit $credit
     * @return array
     */
    public function save(CreditRefundRequest $request, CustomerCredit $credit)
    {
        $request->merge(['credit_id' => $credit->id]);
        $refund = $this->model->fill($request->toArray());
        $refund->save();
        return $refund->toArray();
    }

    /**
     * @param string $method
     * @param CustomerCredit|null $credit
     * @return array
     */
    public function breadcrumbs(string $method, CustomerCredit $credit = null): array
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