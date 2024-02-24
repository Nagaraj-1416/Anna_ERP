<?php

namespace App\Http\Controllers\Finance;

use App\ChequeInHand;
use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\TransferRequest;
use App\Repositories\Finance\TransferRepository;
use App\Transfer;
use Illuminate\Support\Facades\Storage;

class TransferController extends Controller
{
    protected $receiptPath = 'deposited-receipts/';

    /**
     * @var TransferRepository
     */
    protected $transfer;

    /**
     * TransferController constructor.
     * @param TransferRepository $transfer
     */
    public function __construct(TransferRepository $transfer)
    {
        $this->transfer = $transfer;
    }

    public function index()
    {
        $breadcrumb = $this->transfer->breadcrumbs('index');
        $trans = $this->transfer->index();
        if (\request()->ajax()) {
            return response()->json($trans);
        }
        return view('finance.transfer.index', compact('breadcrumb'));
    }

    public function report()
    {
        $breadcrumb = $this->transfer->breadcrumbs('report');
        $trans = $this->transfer->report();
        if (\request()->ajax()) {
            return response()->json($trans);
        }
        return view('finance.transfer.report', compact('breadcrumb'));
    }

    /**
     * Create new trans
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $breadcrumb = $this->transfer->breadcrumbs('index');
        $type = request()->input(['type'], null);
        $cheques = groupByCallbackForCheque(
            ChequeInHand::where('is_transferred', 'No')
                ->whereIn('company_id', userCompanyIds(loggedUser()))
                ->with('bank', 'chequeable', 'chequeable.invoice', 'chequeable.invoice.customer')
                ->get()
        );

        $grandTotal = ChequeInHand::where('is_transferred', 'No')->whereIn('company_id', userCompanyIds(loggedUser()))->sum('amount');

        return view('finance.transfer.create', compact('breadcrumb', 'type', 'cheques', 'grandTotal'));
    }

    public function shopCreate()
    {
        $breadcrumb = $this->transfer->breadcrumbs('index');
        $type = request()->input(['type'], null);
        $cheques = collect();
        $grandTotal = 0;
        return view('finance.transfer.shop.create', compact('breadcrumb', 'type', 'cheques', 'grandTotal'));
    }

    /**
     * @param TransferRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TransferRequest $request)
    {
        $this->transfer->save($request);
        alert()->success('Transfer record created successfully!', 'Success')->persistent();
        return redirect()->route('dashboard');
    }

    /**
     * @param Transfer $transfer
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Transfer $transfer)
    {
        $transfer->load('transferBy', 'senderCompany', 'receiverCompany', 'receivedBy', 'transaction', 'items');
        $breadcrumb = $this->transfer->breadcrumbs('show');
        return view('finance.transfer.show', compact('breadcrumb', 'transfer'));
    }

    /**
     * @param Transfer $transfer
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(Transfer $transfer)
    {
        $data = $this->transfer->approve($transfer);
        return response()->json($data);
    }

    /**
     * @param Transfer $transfer
     * @return \Illuminate\Http\JsonResponse
     */
    public function decline(Transfer $transfer)
    {
        $data = $this->transfer->decline($transfer);
        return response()->json($data);
    }

    /**
     * @param Transfer $transfer
     * @return \Illuminate\Http\JsonResponse
     */
    public function statusUpdate(Transfer $transfer)
    {
        $request = request();
        $this->validate($request, [
            'received_amount' => 'required|numeric|max:' . $transfer->getAttribute('amount')
        ]);

        $this->transfer->statusUpdate($transfer);
        alert()->success('Transfer status updated successfully!', 'Success')->persistent();
        return redirect()->route('finance.transfer.show', $transfer);
    }

    public function uploadReceipt(Transfer $transfer)
    {
        $request = request();
        $this->validate($request, [
            'deposited_receipt' => 'required'
        ]);

        $depositedReceipt = $request->file('deposited_receipt');
        if ($depositedReceipt) {
            $logoType = $depositedReceipt->getClientOriginalExtension();
            $logoName = $transfer->getAttribute('type').'-'.$transfer->getAttribute('amount').'-'.$transfer->getAttribute('transfer_by') . '.' . $logoType;
            Storage::put($this->receiptPath . $logoName, file_get_contents($depositedReceipt));

            /** update company logo name to row item */
            $transfer->setAttribute('deposited_receipt', $logoName);
            $transfer->setAttribute('receipt_uploaded_on', carbon()->now()->toDateTimeString());
            $transfer->setAttribute('receipt_uploaded_by', auth()->id());
            $transfer->setAttribute('status', 'Pending');
            $transfer->save();
        }

        alert()->success('Deposited receipt uploaded successfully!', 'Success')->persistent();
        return redirect()->route('finance.transfer.show', $transfer);
    }

    public function getReceipt(Transfer $transfer)
    {
        if($transfer->getAttribute('deposited_receipt')){
            $imagePath = Storage::get($this->receiptPath . $transfer->getAttribute('deposited_receipt'));
        }else{
            $imagePath = Storage::get('data/default.jpg');
        }
        return response($imagePath)->header('Content-Type',  'image/jpg');
    }

}
