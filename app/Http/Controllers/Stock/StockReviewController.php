<?php

namespace App\Http\Controllers\Stock;

use App\Http\Requests\Stock\ReviewStockStoreRequest;
use App\Repositories\Stock\StockReviewRepository;
use App\Http\Controllers\Controller;
use App\Stock;
use App\StockReview;
use App\Store;

/**
 * Class StockReviewController
 * @package App\Http\Controllers\Stock
 */
class StockReviewController extends Controller
{
    /**
     * @var StockReviewRepository
     */
    public $review;

    /**
     * StockReviewController constructor.
     * @param StockReviewRepository $review
     */
    public function __construct(StockReviewRepository $review)
    {
        $this->review = $review;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            $data = $this->review->index();
            return response()->json($data);
        }
        $breadcrumb = $this->review->breadcrumbs('index');
        return view('stock.review.index', compact('breadcrumb', 'query'));
    }

    /**
     * @param Store $store
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Store $store)
    {
        $breadcrumb = $this->review->breadcrumbs('create');
        $stocks = Stock::where('store_id', $store->id)->get();
        /*$stocks = $stocks->reject(function (Stock $stock) {
            return $stock->available_stock <= 0;
        });*/
        $staff = $store->staff()->pluck('short_name', 'id');
        return view('stock.review.create', compact('breadcrumb', 'store', 'stocks', 'staff'));
    }

    /**
     * @param ReviewStockStoreRequest $request
     * @param Store $store
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ReviewStockStoreRequest $request, Store $store)
    {
        $this->review->reviewStock($store, $request);
        alert()->success('Stock reviewed successfully', 'Success')->persistent();
        return redirect()->route('stock.review.index');
    }

    /**
     * @param StockReview $review
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(StockReview $review)
    {
        $breadcrumb = $this->review->breadcrumbs('show');
        return view('stock.review.show', compact('breadcrumb', 'review'));
    }

    /**
     * @param StockReview $review
     * @return array
     */
    public function approve(StockReview $review)
    {
        return $this->review->approve($review);
    }

    /**
     * @param StockReview $review
     * @return array
     */
    public function delete(StockReview $review)
    {
        return $this->review->delete($review);
    }

}
