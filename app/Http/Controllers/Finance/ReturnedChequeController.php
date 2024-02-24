<?php

namespace App\Http\Controllers\Finance;

use App\Account;
use App\ChequeInHand;
use App\ChequePayment;
use App\DailySale;
use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\ReturnedChequePaymentStoreRequest;
use App\Repositories\Finance\ReturnedChequesRepository;
use App\Repositories\Sales\ChequePaymentRepository;
use Illuminate\Http\Request;

class ReturnedChequeController extends Controller
{
    /**
     * @var ReturnedChequesRepository
     */
    protected $cheque;
    protected $payment;

    /**
     * ReturnedChequeController constructor.
     * @param ReturnedChequesRepository $cheque
     * @param ChequePaymentRepository $payment
     */
    public function __construct(ReturnedChequesRepository $cheque, ChequePaymentRepository $payment)
    {
        $this->cheque = $cheque;
        $this->payment = $payment;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $breadcrumb = $this->cheque->breadcrumbs('index');

        if (\request()->ajax()) {
            $cheques = $this->cheque->grid();
            return response()->json($cheques);
        }
        return view('finance.banking.returned-cheques.index', compact('breadcrumb'));
    }

    public function show($chequeKey)
    {
        [$chequeNo, $bankId] = chequeKeyToArray($chequeKey, true);
        $chequeQuery = chequeKeyToArray($chequeKey, 'query');
        $breadcrumb = $this->cheque->breadcrumbs('show');
        $cheques = ChequeInHand::where($chequeQuery)
            ->with('bank', 'chequeable', 'chequeable.invoice', 'chequeable.invoice.customer')
            ->get();
        $payments = ChequePayment::where('cheque', $chequeNo)->where('bank_id', $bankId)->get();
        $chequeData = getChequeDataByNo($cheques->first());
        $chequeAmount = $chequeData['eachTotal'];
        $settledAmount = ChequePayment::where('cheque', $chequeNo)
            ->where('bank_id', $bankId)->where('status', 'Paid')->sum('payment');

        $balance = ($chequeAmount - $settledAmount);
        return view('finance.banking.returned-cheques.show',
            compact(
                'breadcrumb',
                'chequeKey',
                'chequeNo',
                'bankId',
                'chequeData',
                'cheques',
                'payments',
                'settledAmount',
                'balance'
            )
        );
    }

    public function createPayment($chequeKey)
    {
        $breadcrumb = $this->cheque->breadcrumbs('create-payment');
        $chequeQuery = chequeKeyToArray($chequeKey, 'query');

        $allocations = DailySale::where('company_id', getChequeDataByNo($chequeKey)['companyId'])
            ->with('route', 'rep')->orderBy('id', 'decs')->get();
        $chequeData = ChequeInHand::where($chequeQuery)
            ->with('bank', 'chequeable', 'chequeable.invoice', 'chequeable.invoice.customer')
            ->get();

        return view('finance/banking/returned-cheques/payment/create',
            compact('breadcrumb', 'chequeKey', 'chequeData', 'allocations')
        );
    }

    /**
     * @param ReturnedChequePaymentStoreRequest $request
     * @param $cheque
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePayment(ReturnedChequePaymentStoreRequest $request, $chequeKey)
    {
        $this->payment->save($request, $chequeKey);
        alert()->success('Payment created successfully!', 'Success')->persistent();
        return redirect()->route('finance.return.cheques.show', $chequeKey);
    }

    public function cancelPayment($chequeKey, ChequePayment $payment)
    {
        $this->payment->cancel($chequeKey, $payment);
        alert()->success('Cheque payment canceled successfully', 'Success')->persistent();
        return redirect()->route('finance.return.cheques.show', $chequeKey);
    }

}
