<?php

namespace App\Http\Controllers\Sales;

use App\Http\Requests\Sales\CustomerCreditInvoiceRequest;
use App\Repositories\Sales\CustomerCreditInvoiceRepository;
use App\CustomerCredit;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class CustomerCreditInvoiceController extends Controller
{
    protected $payment;

    /**
     * CustomerCreditInvoiceController constructor.
     * @param CustomerCreditInvoiceRepository $payment
     */
    public function __construct(CustomerCreditInvoiceRepository $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @param CustomerCredit $credit
     * @param CustomerCreditInvoiceRequest $request
     * @return JsonResponse
     */
    public function savePayment(CustomerCredit $credit, CustomerCreditInvoiceRequest $request)
    {
        $payment = $this->payment->savePayment($credit, $request);
        return response()->json($payment);
    }
}
