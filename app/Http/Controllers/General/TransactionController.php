<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\TransactionRequest;
use PDF;
use App\Repositories\Finance\TransactionRepository;
use App\Transaction;

class TransactionController extends Controller
{
    /**
     * @var TransactionRepository
     */
    protected $trans;

    /**
     * TransactionController constructor.
     * @param TransactionRepository $trans
     */
    public function __construct(TransactionRepository $trans)
    {
        $this->trans = $trans;
    }

    /**
     * load index view of trans
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $breadcrumb = $this->trans->breadcrumbs('index');
        $trans = $this->trans->index();
        if (\request()->ajax()) {
            return response()->json($trans);
        }
        return view('finance.transaction.index', compact('breadcrumb'));
    }

    public function manualTrans()
    {
        $breadcrumb = $this->trans->breadcrumbs('index');
        $trans = $this->trans->manualTrans();
        if (\request()->ajax()) {
            return response()->json($trans);
        }
        return view('finance.transaction.manual.index', compact('breadcrumb'));
    }

    /**
     * Create new trans
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $breadcrumb = $this->trans->breadcrumbs('create');
        return view('finance.transaction.create', compact('breadcrumb'));
    }

    /**
     * Store new trans
     * @param TransactionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TransactionRequest $request)
    {
        $this->trans->save($request);
        alert()->success('Transaction created successfully!', 'Success')->persistent();
        return redirect()->route('finance.trans.index');
    }

    /**
     * load show view of transaction
     * @param Transaction $trans
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Transaction $trans)
    {
        $trans->load('records', 'company');
        $records = $trans->records->where('category', 'Account');
        $company = $trans->company;
        $companyAddress = $company ? $company->addresses->first() : null;
        $breadcrumb = $this->trans->breadcrumbs('show', $trans);
        return view('finance.transaction.show', compact('breadcrumb', 'trans', 'company', 'companyAddress', 'records'));
    }

    /**
     * load edit view of transaction
     * @param Transaction $trans
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Transaction $trans)
    {
        $breadcrumb = $this->trans->breadcrumbs('edit', $trans);
        return view('finance.transaction.edit', compact('breadcrumb', 'trans'));
    }

    /**
     * Update transaction
     * @param TransactionRequest $request
     * @param Transaction $trans
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TransactionRequest $request, Transaction $trans)
    {
        $this->trans->setModel($trans);
        $this->trans->update($request);
        alert()->success('Transaction created successfully!', 'Success')->persistent();
        return redirect()->route('finance.trans.show', $trans);
    }

    /**
     * Delete transaction
     * @param Transaction $trans
     * @return array
     */
    public function delete(Transaction $trans)
    {
        $this->trans->setModel($trans);
        return $this->trans->delete();
    }

    /**
     * @param Transaction $trans
     * @param string $type
     */
    public function export(Transaction $trans, $type = 'PDF')
    {
        if($type == 'PDF'){
            $this->pdfExport($trans);
        }
    }

    /**
     * @param Transaction $trans
     * @return mixed
     */
    public function pdfExport(Transaction $trans)
    {
        $company = $trans->company;
        $records = $trans->records;
        $data = [];
        $data['company'] = $company;
        $data['trans'] = $trans;
        $data['records'] = $records;
        $data['companyAddress'] = $company ? $company->addresses->first() : null;
        $pdf = PDF::loadView('finance.transaction.export', $data);
        return $pdf->download(env('APP_NAME').' - Transaction ('.$trans->code.')'. '.pdf');
    }

    /**
     * @param Transaction $trans
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function printView(Transaction $trans)
    {
        $company = $trans->company;
        $records = $trans->records;
        $companyAddress = $company ? $company->addresses->first() : null;
        $breadcrumb = $this->trans->breadcrumbs('print', $trans);
        return view('finance.transaction.print', compact('breadcrumb', 'trans', 'company', 'companyAddress', 'records'));
    }
}
