<?php

namespace App\Http\Controllers\Setting;

use App\Exports\PriceBookExport;

use App\Http\Requests\Setting\{
    PriceBookStoreRequest
};
use App\Repositories\Settings\PriceBookRepository;
use App\{PriceBook, PriceHistory, Rep, SalesLocation};
use App\Http\Controllers\Controller;

use Illuminate\Http\{
    JsonResponse, RedirectResponse, Request
};
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class PriceBookController extends Controller
{
    /**
     * @var PriceBookRepository
     */
    protected $priceBook;

    /**
     * PriceBookController constructor.
     * @param PriceBookRepository $priceBook
     */
    public function __construct(PriceBookRepository $priceBook)
    {
        $this->priceBook = $priceBook;
    }

    /**
     * @return View
     */
    public function index()
    {
        if (\request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $priceBooks = $this->priceBook->grid();
            return response()->json($priceBooks);
        }
        $breadcrumb = $this->priceBook->breadcrumbs('index');
        // if (\request()->ajax()) {
        //     $priceBooks = $this->priceBook->grid();
        //     return response()->json($priceBooks);
        // }
        return view('settings.price-book.index', compact('breadcrumb'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function dataTableData(Request $request): array
    {
        if (\request()->ajax()) {
            return $this->priceBook->dataTable($request);
        }
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $breadcrumb = $this->priceBook->breadcrumbs('create');
        return view('settings.price-book.create', compact('breadcrumb'));
    }

    public function clone(PriceBook $priceBook): View
    {
        $breadcrumb = $this->priceBook->breadcrumbs('clone');
        $prices = $this->priceBook->getEditData($priceBook);
        return view('settings.price-book.clone', compact('breadcrumb', 'priceBook', 'prices'));
    }

    /**
     * @param PriceBookStoreRequest $request
     * @return RedirectResponse
     */
    public function store(PriceBookStoreRequest $request)//: RedirectResponse
    {
        
        if ( \request()->header('User-Agent') == 'Postman') {
           $product = $this->priceBook->save($request);
            return response()->json($product);
        }
        $this->priceBook->save($request);
        alert()->success('Price book created successfully', 'Success')->persistent();
        return redirect()->route('setting.price.book.index');
    }

    public function doClone(PriceBookStoreRequest $request): RedirectResponse
    {
        $this->priceBook->saveClone($request);
        alert()->success('Price book cloned successfully', 'Success')->persistent();
        return redirect()->route('setting.price.book.index');
    }

    /**
     * @param PriceBook $priceBook
     * @return View
     */
    public function show(PriceBook $priceBook)
    {
        if (\request()->ajax()) {
            $priceBook->load('prices');
            return response()->json($priceBook->toArray());
        };

        $histories = $priceBook->histories;
        $prices = $priceBook->prices()->with('product')->get();
        $prices = $prices->sortBy('product.name');

        $breadcrumb = $this->priceBook->breadcrumbs('show', $priceBook);
        return view('settings.price-book.show', compact('breadcrumb', 'priceBook', 'histories', 'prices'));
    }

    /**
     * @param PriceBook $priceBook
     * @return View
     */
    public function edit(PriceBook $priceBook): View
    {
        $prices = $this->priceBook->getEditData($priceBook);
        $breadcrumb = $this->priceBook->breadcrumbs('edit', $priceBook);
        return view('settings.price-book.edit', compact('breadcrumb', 'priceBook', 'prices'));
    }

    /**
     * @param PriceBookStoreRequest $request
     * @param PriceBook $priceBook
     * @return RedirectResponse
     */
    public function update(PriceBookStoreRequest $request, PriceBook $priceBook): RedirectResponse
    {
     
        $this->priceBook->update($request, $priceBook);
        alert()->success('Price book updated successfully', 'Success')->persistent();
        return redirect()->route('setting.price.book.index');
    }

    /**
     * @param PriceBook $priceBook
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(PriceBook $priceBook): JsonResponse
    {
        $response = $this->priceBook->delete($priceBook);
        return response()->json($response);
    }

    public function search($q = null){
        $response = $this->priceBook->searchItem($q);
        return response()->json($response);
    }

    public function searchByLocation(SalesLocation $location, $q = null){
        $response = $this->priceBook->searchItemByLocation($location, $q);
        return response()->json($response);
    }

    public function searchByVanLocation(SalesLocation $location, $q = null){
        $response = $this->priceBook->searchItemByVanLocation($location, $q);
        return response()->json($response);
    }

    public function searchByRep(Rep $rep, $q = null){
        $response = $this->priceBook->searchItemByRep($rep, $q);
        return response()->json($response);
    }

    /**
     * @return View
     */
    public function comparison()
    {
        $breadcrumb = $this->priceBook->breadcrumbs('comparison');
        if (\request()->ajax()) {
            $products = $this->priceBook->comparision();
            return response()->json($products);
        }

        $companyId = request()->input('company_id');

        return view('settings.price-book.comparison', compact('breadcrumb', 'companyId'));
    }

    public function history(PriceHistory $priceHistory)
    {
        $breadcrumb = $this->priceBook->breadcrumbs('history');
        return view('settings.price-book.history', compact('breadcrumb', 'priceHistory'));
    }

    /**
     * @param PriceBook $priceBook
     * @return mixed
     */
    public function exportBook(PriceBook $priceBook)
    {
        if (request()->input()) {
            return $this->exportBookExcel($priceBook);
        }

        $prices = $priceBook->prices()->with('product')->get();
        $prices = $prices->sortBy('product.name');

        $data = [];
        $data['prices'] = $prices;
        $data['priceBook'] = $priceBook;
        $data['related'] = $priceBook->relatedTo;
        $pdf = PDF::loadView('settings.price-book.prices', $data);
        return $pdf->download($priceBook->getAttribute('name'). '.pdf');
    }

    public function exportBookExcel(PriceBook $priceBook)
    {
        return Excel::download(new PriceBookExport($priceBook), $priceBook->getAttribute('name'). '.xlsx', 'Xlsx');
    }

}
