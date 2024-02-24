<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Requests\Purchase\SupplierCreditBillRequest;
use App\Repositories\Purchase\SupplierCreditBillRepository;
use App\SupplierCredit;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class SupplierCreditBillController extends Controller
{
    protected $payment;

    /**
     * SupplierCreditBillController constructor.
     * @param SupplierCreditBillRepository $payment
     */
    public function __construct(SupplierCreditBillRepository $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @param SupplierCredit $credit
     * @param SupplierCreditBillRequest $request
     * @return JsonResponse
     */
    public function savePayment(SupplierCredit $credit, SupplierCreditBillRequest $request)
    {
        $payment = $this->payment->savePayment($credit, $request);
        return response()->json($payment);
    }
}
