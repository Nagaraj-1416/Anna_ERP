<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Repositories\Finance\IssuedChequeRepository;

class IssuedChequeController extends Controller
{
    /**
     * @var IssuedChequeRepository
     */
    protected $cheque;

    /**
     * IssuedChequeController constructor.
     * @param IssuedChequeRepository $cheque
     */
    public function __construct(IssuedChequeRepository $cheque)
    {
        $this->cheque = $cheque;
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
        return view('finance.banking.issued-cheques.index', compact('breadcrumb'));
    }

}
