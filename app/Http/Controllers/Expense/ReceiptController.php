<?php

namespace App\Http\Controllers\Expense;

use App\Account;
use App\BusinessType;
use App\ChequeInHand;
use App\Expense;
use App\ExpenseCheque;
use App\ExpenseItem;
use App\ExpensePayment;
use App\Http\Requests\Expense\ExpenseReceiptStoreRequest;
use App\Http\Requests\Expense\ExpenseReceiptUpdateRequest;
use PDF;
use App\Repositories\Expense\ReceiptRepository;
use App\Http\Controllers\Controller;
use App\Transaction;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    /** @var ReceiptRepository */
    protected $receipt;

    /**
     * ReceiptController constructor.
     * @param ReceiptRepository $receipt
     */
    public function __construct(ReceiptRepository $receipt)
    {
        $this->receipt = $receipt;
    }

    /**
     * load receipt index
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', $this->receipt->getModel());
        $breadcrumb = $this->receipt->breadcrumbs();
        if (request()->ajax()) {
            $receipts = $this->receipt->index();
            return response()->json($receipts);
        }
        return view('expense.receipt.index', compact('breadcrumb'));
    }

    /**
     * @param Expense $expense
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Expense $expense)
    {
        $this->authorize('show', $this->receipt->getModel());
        $breadcrumb = $this->receipt->breadcrumbs($expense);
        $company = $expense->company;
        $companyAddress = $company ? $company->addresses()->first() : null;
        $expCheques = ExpenseCheque::where('expense_id', $expense->id)->pluck('cheque_in_hand_id');
        $cheques = groupByCallbackForCheque(ChequeInHand::whereIn('id', $expCheques)->get());
        $chequesAmount = ChequeInHand::whereIn('id', $expCheques)->sum('amount');

        $paymentIds = ExpensePayment::where('expense_id', $expense->id)->pluck('id');
        $totalPayments = ExpensePayment::where('expense_id', $expense->id)->sum('payment');
        $trans = Transaction::where('transactionable_id', $expense->id)
            ->where('transactionable_type', 'App\Expense')->get();

        return view('expense.receipt.show', compact('totalPayments', 'trans', 'breadcrumb', 'chequesAmount', 'cheques', 'expense', 'company', 'companyAddress'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->receipt->getModel());
        $breadcrumb = $this->receipt->breadcrumbs();
        return view('expense.receipt.create', compact('breadcrumb'));
    }

    /**
     * @param ExpenseReceiptStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(ExpenseReceiptStoreRequest $request)
    {
        $this->authorize('store', $this->receipt->getModel());
        $expense = $this->receipt->store($request);
        alert()->success('Expense payment created successfully', 'Success')->persistent();
        return redirect()->route('expense.receipt.show', [$expense]);
    }

    /**
     * @param Expense $expense
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Expense $expense)
    {
        $this->authorize('edit', $this->receipt->getModel());
        $breadcrumb = $this->receipt->breadcrumbs($expense);
        $expense = $this->receipt->edit($expense);
        return view('expense.receipt.edit', compact('breadcrumb', 'expense'));
    }

    /**
     * @param Expense $expense
     * @param ExpenseReceiptUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Expense $expense, ExpenseReceiptUpdateRequest $request)
    {
        $this->authorize('update', $this->receipt->getModel());
        $expense = $this->receipt->update($expense, $request);
        alert()->success('Expense payment updated successfully', 'Success')->persistent();
        return redirect()->route('expense.receipt.show', [$expense]);
    }

    public function approve(Expense $expense)
    {
        $this->receipt->setModel($expense);
        $this->receipt->approve($expense);
        alert()->success('Expense related transaction generated successfully', 'Success')->persistent();
        return redirect()->route('expense.receipt.show', [$expense]);
    }

    public function delete(Expense $expense)
    {
        $this->receipt->setModel($expense);
        return $this->receipt->delete();
    }

    /**
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($q = null)
    {
        $result = $this->receipt->search($q, 'expense_no', [], [], [['report_id', null]]);
        return response()->json($result);
    }

    /**
     * @param BusinessType $businessType
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchByBusinessType(BusinessType $businessType, $q = null)
    {
        $result = $this->receipt->search($q, 'expense_no', [], [], [['report_id', null], ['business_type_id', $businessType->id]]);
        return response()->json($result);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExpenses()
    {
        $ids = explode(',', request('ids'));
        if (!$ids || !is_array($ids)) return response()->json([]);
        $expenses = Expense::whereIn('id', $ids)->with('category')->get();
        return response()->json($expenses->toArray());
    }

    public function addItem(Expense $expense)
    {
        $this->authorize('edit', $this->receipt->getModel());
        $breadcrumb = $this->receipt->breadcrumbs($expense);

        return view('expense.receipt.add-items', compact('breadcrumb', 'expense'));
    }

    public function storeItem(Expense $expense, Request $request)
    {
        $request->validate([
            'item' => 'required',
            'expiry_date' => 'required'
        ]);

        $item = new ExpenseItem();
        $item->setAttribute('expense_id', $expense->id);
        $item->setAttribute('item', $request->input('item'));
        $item->setAttribute('expiry_date', $request->input('expiry_date'));
        $item->setAttribute('notes', $request->input('notes'));
        $item->save();

        alert()->success('Item added successfully', 'Success')->persistent();
        return redirect()->route('expense.receipt.show', [$expense]);
    }

    public function addPayment(Expense $expense, $mode)
    {
        $fromDate = carbon()->now()->startOfMonth()->toDateString();
        $toDate = carbon()->now()->endOfMonth()->toDateString();

        $availableCheques = groupByCallbackForCheque(ChequeInHand::whereBetween('cheque_date', [$fromDate, $toDate])
            ->where('status', 'Not Realised')
            ->where('company_id', $expense->company_id)
            ->get());

        $grandTotal = ChequeInHand::whereBetween('cheque_date', [$fromDate, $toDate])
            ->where('status', 'Not Realised')
            ->where('company_id', $expense->company_id)->sum('amount');

        $breadcrumb = $this->receipt->breadcrumbs($expense);
        if($mode == 'Cash'){
            return view('expense.receipt.payments.cash', compact('breadcrumb', 'expense', 'mode'));
        }else if($mode == 'Bank'){
            return view('expense.receipt.payments.bank', compact('breadcrumb', 'expense', 'mode'));
        }else if($mode == 'OwnCheque'){
            return view('expense.receipt.payments.own-cheque', compact('breadcrumb', 'expense', 'mode'));
        }else if($mode == 'ThirdPartyCheque'){
            return view('expense.receipt.payments.third-party-cheque', compact('grandTotal', 'availableCheques', 'breadcrumb', 'expense', 'mode'));
        }
    }

    public function storePayment(Expense $expense, $mode, Request $request)
    {
        $request->validate([
            'payment' => 'required',
            'notes' => 'required'
        ]);

        if($mode == 'Cash')
        {
            $this->receipt->storeCashPayment($expense, $request);
        }

        if($mode == 'Bank')
        {
            $request->validate([
                'paid_through' => 'required'
            ]);
            $this->receipt->storeBankPayment($expense, $request);
        }

        if($mode == 'OwnCheque')
        {
            $request->validate([
                'cheque_no' => 'required',
                'cheque_date' => 'required',
                'cc_bank_id' => 'required',
                'paid_through' => 'required'
            ]);
            $this->receipt->storeOwnChequePayment($expense, $request);
        }

        if($mode == 'ThirdPartyCheque')
        {
            $this->receipt->storeThirdPartyChequePayment($expense, $request);
        }

        alert()->success('Payment added successfully', 'Success')->persistent();
        return redirect()->route('expense.receipt.show', [$expense]);
    }

    public function deletePayment(Expense $expense, ExpensePayment $expensePayment)
    {
        $transaction = $expensePayment->transaction;
        if($transaction){
            $transaction->records()->delete();
            $transaction->delete();
        }

        if($expensePayment->payment_mode == 'Third Party Cheque'){
            $cheques = $expensePayment->cheques;
            foreach ($cheques as $cheque){
                $cheque->delete();
            }
        }

        $expensePayment->delete();

        return ['success' => true, 'message' => 'Payment deleted success'];
    }

    public function export(Expense $expense, $type = 'PDF')
    {
        if ($type == 'PDF') {
            $this->pdfExport($expense);
        }
    }

    /**
     * @param $expense
     * @return mixed
     */
    public function pdfExport($expense)
    {
        $company = $expense->company;
        $companyAddress = $company->addresses()->first();
        $payments = $expense->payments;
        $expCheques = ExpenseCheque::where('expense_id', $expense->id)->pluck('cheque_in_hand_id');
        $cheques = groupByCallbackForCheque(ChequeInHand::whereIn('id', $expCheques)->get());
        $chequesAmount = ChequeInHand::whereIn('id', $expCheques)->sum('amount');

        $data = [];
        $data['expense'] = $expense;
        $data['company'] = $company;
        $data['companyAddress'] = $companyAddress;
        $data['payments'] = $payments;
        $data['cheques'] = $cheques;
        $data['chequesAmount'] = $chequesAmount;

        $pdf = PDF::loadView('expense.receipt.export', $data);
        return $pdf->download(env('APP_NAME') . ' - Expense Payment(' . $expense->expense_no . ')' . '.pdf');
    }

    public function printView(Expense $expense)
    {
        $company = $expense->company;
        $companyAddress = $company->addresses()->first();
        $payments = $expense->payments;
        $expCheques = ExpenseCheque::where('expense_id', $expense->id)->pluck('cheque_in_hand_id');
        $cheques = groupByCallbackForCheque(ChequeInHand::whereIn('id', $expCheques)->get());
        $chequesAmount = ChequeInHand::whereIn('id', $expCheques)->sum('amount');

        $breadcrumb = $this->receipt->breadcrumbs($expense);
        return view('expense.receipt.print', compact('breadcrumb', 'expense', 'company', 'companyAddress',
            'payments', 'cheques', 'chequesAmount'));
    }

}
