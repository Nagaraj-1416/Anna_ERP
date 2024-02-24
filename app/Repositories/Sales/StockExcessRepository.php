<?php

namespace App\Repositories\Sales;

use App\Account;
use App\Repositories\BaseRepository;
use App\StockExcess;
use App\StockExcessItem;
use Illuminate\Http\Request;

/**
 * Class StockExcessRepository
 * @package App\Repositories\Sales
 */
class StockExcessRepository extends BaseRepository
{
    /**
     * StockExcessRepository constructor.
     * @param StockExcess|null $stockExcess
     */
    public function __construct(StockExcess $stockExcess = null)
    {
        $this->setModel($stockExcess ?? new StockExcess());
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $filter = \request()->input('filter');
        $lastWeek = carbon()->subWeek();
        $stocks = StockExcess::whereIn('company_id', userCompanyIds(loggedUser()))
            ->with('route', 'rep', 'dailySale', 'company', 'items')
            ->orderBy('id', 'desc');

        if ($filter) {
            switch ($filter) {
                case 'recentlyCreated':
                    $stocks->where('created_at', '>', $lastWeek);
                    break;
                case 'recentlyModified':
                    $stocks->where('updated_at', '>', $lastWeek);
                    break;
            }
        }
        return $stocks->paginate(20)->toArray();
    }

    public function approve(Request $request, StockExcess $stock, StockExcessItem $stockItem)
    {
        $itemQty = $request->input('item_qty');
        $itemAmount = ($itemQty * $stockItem->getAttribute('rate'));

        $stockItem->setAttribute('qty', $itemQty);
        $stockItem->setAttribute('amount', $itemAmount);
        $stockItem->setAttribute('status', 'Approved');
        $stockItem->setAttribute('approved_by', auth()->id());
        $stockItem->setAttribute('approved_on', carbon()->now()->toDateTimeString());
        $stockItem->save();

        /** update stock amount */
        $stock->setAttribute('amount', $stock->items()
            ->whereIn('status', ['Drafted', 'Approved'])->sum('amount'));
        $stock->save();

        /** debit account - Van Stocks Shortage  */
        $debitAccount = Account::where('prefix', 'VanGoodsShortage')
            ->where('accountable_id', $stock->getAttribute('company_id'))
            ->where('accountable_type', 'App\Company')->first();

        /** credit account - Van Goods Excess */
        $creditAccount = Account::where('prefix', 'VanGoodsExcess')
            ->where('accountable_id', $stock->getAttribute('company_id'))
            ->where('accountable_type', 'App\Company')->first();

        recordTransaction($stockItem, $debitAccount, $creditAccount, [
            'date' => $stock->getAttribute('date'),
            'type' => 'Deposit',
            'amount' => $stockItem->getAttribute('amount'),
            'auto_narration' => 'The excess stock of '.$stockItem->getAttribute('amount').' was identified during the sales',
            'manual_narration' => 'The excess amount of '.$stockItem->getAttribute('amount').' was identified during the sales',
            'tx_type_id' => 57,
            'company_id' => $stock->getAttribute('company_id'),
        ], 'StockExcess');

        return ['success' => true];
    }

    public function reject(StockExcess $stock, StockExcessItem $stockItem)
    {
        $itemAmount = $stockItem->getAttribute('amount');

        $stockItem->setAttribute('status', 'Rejected');
        $stockItem->setAttribute('approved_by', auth()->id());
        $stockItem->setAttribute('approved_on', carbon()->now()->toDateTimeString());
        $stockItem->save();

        /** update stock amount */
        $deduct = ($stock->getAttribute('amount') - $itemAmount);
        $stock->setAttribute('amount', $deduct);
        $stock->save();

        return ['success' => true];
    }

}