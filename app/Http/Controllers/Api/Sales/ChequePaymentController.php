<?php

namespace App\Http\Controllers\Api\Sales;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Sales\ChequePaymentStoreRequest;
use App\Http\Resources\InvoicePaymentResource;
use App\{ChequePayment, Http\Requests\Api\Sales\ReasonRequest, Http\Resources\ChequePaymentResource, Invoice};
use App\Repositories\Sales\ChequePaymentRepository;
use Illuminate\Http\Request;

/**
 * Class ChequePaymentController
 * @package App\Http\Controllers\Api\Sales
 */
class ChequePaymentController extends ApiController
{
    /** @var ChequePaymentRepository */
    protected $payment;

    /**
     * PaymentController constructor.
     * @param ChequePaymentRepository $payment
     */
    public function __construct(ChequePaymentRepository $payment)
    {
        $this->payment = $payment;
    }


    public function index()
    {
        return response()->json(
            array_values(
                $this->payment->apiIndex()->toArray()
            )
        );
    }

    /**
     * @param ChequePaymentStoreRequest $request
     * @param $cheque
     * @return ChequePaymentResource
     */
    public function store(ChequePaymentStoreRequest $request, $cheque)
    {
        $payment = $this->payment->save($request, $cheque, true);
        return new ChequePaymentResource($payment);
    }

    /**
     * @param ChequePaymentStoreRequest $request
     * @param ChequePayment $payment
     * @return ChequePaymentResource
     */
    public function update(ChequePaymentStoreRequest $request, ChequePayment $payment)
    {
        $payment = $this->payment->update($request, $payment, true);
        return new ChequePaymentResource($payment);
    }

    /**
     * @param ChequePayment $payment
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(ChequePayment $payment)
    {
        $response = $this->payment->delete($payment);
        return response()->json($response);
    }

    /**
     * @param ChequePayment $payment
     * @param Request $request
     * @return ChequePaymentResource
     */
    public function isPrinted(ChequePayment $payment, Request $request)
    {
        $request->validate(['is_printed' => 'required|in:"Yes","No"']);
        return new ChequePaymentResource($this->payment->isPrinted($payment));
    }

}
