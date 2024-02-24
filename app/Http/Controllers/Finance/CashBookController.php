<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Repositories\Finance\CashBookRepository;

class CashBookController extends Controller
{
    /**
     * @var CashBookRepository
     */
    public $cashBook;

    /**
     * DayBookController constructor.
     * @param CashBookRepository $cashBook
     */
    public function __construct(CashBookRepository $cashBook)
    {
        $this->cashBook = $cashBook;
    }

    public function byCompany()
    {
        $breadcrumb = $this->breadcrumbs('by-company');
        if (request()->ajax()) {
            $data = $this->cashBook->byCompany();
            return response()->json($data);
        }
        return view('finance.cash-book.company.index', compact('breadcrumb'));
    }

    public function byRep()
    {
        $breadcrumb = $this->breadcrumbs('by-rep');
        if (request()->ajax()) {
            $data = $this->cashBook->byRep();
            return response()->json($data);
        }
        return view('finance.cash-book.rep.index', compact('breadcrumb'));
    }

    public function breadcrumbs(string $method): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Cash Book'],
            ],
            'by-rep' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Cash Book - Rep'],
            ],
            'by-company' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Cash Book - Company'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}
