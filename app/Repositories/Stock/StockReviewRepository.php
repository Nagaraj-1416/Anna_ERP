<?php

namespace App\Repositories\Stock;

use App\Company;
use App\Http\Requests\Stock\ReviewStockStoreRequest;
use App\Product;
use App\Repositories\BaseRepository;
use App\Stock;
use App\StockHistory;
use App\StockReview;
use App\StockReviewItem;
use App\Store;

/**
 * Class StockReviewRepository
 * @package App\Repositories\Stock
 */
class StockReviewRepository extends BaseRepository
{
    /**
     * @return array
     */
    public function index()
    {
        $filter = \request()->input('filter');
        $lastWeek = carbon()->subWeek();
        $reviews = StockReview::whereIn('company_id', userCompanyIds(loggedUser()))
            ->with('store', 'staff', 'company', 'preparedBy')->orderBy('id', 'desc');
        if ($filter) {
            switch ($filter) {
                case 'recentlyCreated':
                    $reviews->where('created_at', '>', $lastWeek);
                    break;
                case 'recentlyModified':
                    $reviews->where('updated_at', '>', $lastWeek);
                    break;
            }
        }
        return $reviews->paginate(12)->toArray();
    }

    /**
     * @param Store $store
     * @param ReviewStockStoreRequest $request
     */
    public function reviewStock(Store $store, ReviewStockStoreRequest $request)
    {
        $staffId = $request->input('staff_id');
        $transferNotes = $request->input('notes');
        $reviews = $request->input('reviews');
        $stockIds = array_get($reviews, 'id');
        $availableQty = array_get($reviews, 'available_qty');
        $actualQty = array_get($reviews, 'actual_qty');

        /** create stock review records */
        $review = new StockReview();
        $review->setAttribute('date', carbon()->now()->toDateString());
        $review->setAttribute('notes', $transferNotes);
        $review->setAttribute('prepared_by', auth()->id());
        $review->setAttribute('prepared_on', carbon()->now()->toDateTimeString());
        $review->setAttribute('store_id', $store->id);
        $review->setAttribute('staff_id', $staffId);
        $review->setAttribute('company_id', $store->company_id);
        $review->save();

        if ($stockIds) {
            foreach ($stockIds as $key => $val) {

                /** get stock row data which is going to update */
                $stockToReview = Stock::where('id', $key)->first();
                /*if($stockToReview) {
                    $stockToReview->available_stock = (int)array_get($actualQty, $key);
                    $stockToReview->save();
                }*/

                /** calculate excess & shortage qty */
                $excessQty = 0;
                $shortageQty = 0;
                if((int)array_get($availableQty, $key) > (int)array_get($actualQty, $key)) {
                    $shortageQty = ((int)array_get($availableQty, $key) - (int)array_get($actualQty, $key));
                }
                if((int)array_get($availableQty, $key) < (int)array_get($actualQty, $key)) {
                    $excessQty = ((int)array_get($actualQty, $key) - (int)array_get($availableQty, $key));
                }

                /** get rate & amount */
                $stockHistory = StockHistory::where('stock_id', $stockToReview->id)
                    ->where('transaction', 'In')
                    ->where('type', 'Purchase')
                    ->orderBy('id', 'desc')
                    ->first();
                if($stockHistory){
                    $rate = $stockHistory->rate;
                }else{
                    $product = Product::where('id', $stockToReview->product_id)->first();
                    $rate = $product->buying_price;
                }

                /** create review items  */
                $reviewItem = new StockReviewItem();
                $reviewItem->setAttribute('date', carbon()->now()->toDateTimeString());
                $reviewItem->setAttribute('available_qty', (int)array_get($availableQty, $key));
                $reviewItem->setAttribute('actual_qty', (int)array_get($actualQty, $key));
                $reviewItem->setAttribute('excess_qty', $excessQty);
                $reviewItem->setAttribute('shortage_qty', $shortageQty);
                $reviewItem->setAttribute('rate', $rate);
                $reviewItem->setAttribute('amount', ($rate * (int)array_get($actualQty, $key)));
                $reviewItem->setAttribute('excess_amount', ($rate * $excessQty));
                $reviewItem->setAttribute('shortage_amount', ($rate * $shortageQty));
                $reviewItem->setAttribute('product_id', $stockToReview->product_id);
                $reviewItem->setAttribute('stock_id', $stockToReview->id);
                $reviewItem->setAttribute('stock_review_id', $review->getAttribute('id'));
                $reviewItem->save();
            }
        }
    }

    public function delete(StockReview $review)
    {
        try {
            $review->items()->delete();
            $review->delete();
            return ['success' => true, 'message' => 'Stock review details is deleted successfully!'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed!'];
        }
    }

    public function approve(StockReview $review)
    {
        try {
            $reviewItems = $review->items;
            foreach ($reviewItems as $reviewItem){
                /** get stock row data which is going to update */
                $stockToReview = Stock::where('id', $reviewItem->stock_id)->first();
                if($stockToReview) {
                    $stockToReview->available_stock = $reviewItem->actual_qty;
                    $stockToReview->save();

                    /** stock history - shortage */
                    if($reviewItem->excess_qty > 0){
                        $excessHistory = new StockHistory();
                        $excessHistory->setAttribute('stock_id', $stockToReview->id);
                        $excessHistory->setAttribute('quantity', $reviewItem->excess_qty);
                        $excessHistory->setAttribute('rate', $reviewItem->rate);
                        $excessHistory->setAttribute('type', 'review');
                        $excessHistory->setAttribute('transaction', 'In');
                        $excessHistory->setAttribute('trans_date', carbon()->now()->toDateString());
                        $excessHistory->setAttribute('trans_description', 'Excess qty from stock review');
                        $excessHistory->save();
                    }

                    /** stock history - excess */
                    if($reviewItem->shortage_qty > 0){
                        $shortageHistory = new StockHistory();
                        $shortageHistory->setAttribute('stock_id', $stockToReview->id);
                        $shortageHistory->setAttribute('quantity', $reviewItem->shortage_qty);
                        $shortageHistory->setAttribute('rate', $reviewItem->rate);
                        $shortageHistory->setAttribute('type', 'review');
                        $shortageHistory->setAttribute('transaction', 'Out');
                        $shortageHistory->setAttribute('trans_date', carbon()->now()->toDateString());
                        $shortageHistory->setAttribute('trans_description', 'Shortage qty from stock review');
                        $shortageHistory->save();
                    }
                }
            }

            $review->setAttribute('status', 'Approved');
            $review->setAttribute('approved_by', auth()->id());
            $review->setAttribute('approved_on', carbon()->now()->toDateTimeString());
            $review->save();

            $shortageAmount = $review->items()->sum('shortage_amount');
            $excessAmount = $review->items()->sum('excess_amount');

            /** TODO record shortage related transaction */

            /** TODO record excess related transaction */

            return ['success' => true, 'message' => 'Stock review details is approved successfully!'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed!'];
        }
    }

    /**
     * @param string $method
     * @return array
     */
    public function breadcrumbs(string $method): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Stock Summary', 'route' => 'stock.summary.index'],
                ['text' => 'Stock Reviews'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Stock Summary', 'route' => 'stock.summary.index'],
                ['text' => 'Stock Reviews', 'route' => 'stock.review.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Stock Summary', 'route' => 'stock.summary.index'],
                ['text' => 'Stock Reviews', 'route' => 'stock.review.index'],
                ['text' => 'review Details'],
            ],
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}