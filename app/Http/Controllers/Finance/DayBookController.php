<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Repositories\Finance\DayBookRepository;

class DayBookController extends Controller
{
    /**
     * @var DayBookRepository
     */
    public $dayBook;

    /**
     * DayBookController constructor.
     * @param DayBookRepository $dayBook
     */
    public function __construct(DayBookRepository $dayBook)
    {
        $this->dayBook = $dayBook;
    }

    public function byCompany()
    {
        $breadcrumb = $this->breadcrumbs('by-company');
        if (request()->ajax()) {
            $data = $this->dayBook->byCompany();
            return response()->json($data);
        }
        return view('finance.day-book.company.index', compact('breadcrumb'));
    }

    public function byRep()
    {
        $breadcrumb = $this->breadcrumbs('by-rep');
        if (request()->ajax()) {
            $data = $this->dayBook->byRep();
            return response()->json($data);
        }
        return view('finance.day-book.rep.index', compact('breadcrumb'));
    }

    public function breadcrumbs(string $method): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Day Book'],
            ],
            'by-rep' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Day Book - Rep'],
            ],
            'by-company' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Day Book - Company'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}
