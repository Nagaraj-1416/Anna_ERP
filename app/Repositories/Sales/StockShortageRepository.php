<?php

namespace App\Repositories\Sales;

use App\Account;
use App\Rep;
use App\Repositories\BaseRepository;
use App\StockShortage;
use App\StockShortageItem;
use Illuminate\Http\Request;

/**
 * Class StockShortageRepository
 * @package App\Repositories\Sales
 */
class StockShortageRepository extends BaseRepository
{
    /**
     * StockShortageRepository constructor.
     * @param StockShortage|null $stockShortage
     */
    public function __construct(StockShortage $stockShortage = null)
    {
        $this->setModel($stockShortage ?? new StockShortage());
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $filter = \request()->input('filter');
        $lastWeek = carbon()->subWeek();
        $stocks = StockShortage::whereIn('company_id', userCompanyIds(loggedUser()))
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

    public function approve(Request $request, StockShortage $stock, StockShortageItem $stockItem)
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
        $stock->setAttribute('amount', $stock->items()->where('status', 'Approved')->sum('amount'));
        $stock->save();

        /** record transaction */
        /** debit account - Rep Commission Account  */
        $debitAccount = Account::where('prefix', 'Commission')
            ->where('accountable_id', $stock->getAttribute('rep_id'))
            ->where('accountable_type', 'App\Rep')->first();

        /** credit account - Van Stocks Shortage */
        $creditAccount = Account::where('prefix', 'VanGoodsShortage')
            ->where('accountable_id', $stock->getAttribute('company_id'))
            ->where('accountable_type', 'App\Company')->first();

        recordTransaction($stockItem, $debitAccount, $creditAccount, [
            'date' => $stock->getAttribute('date'),
            'type' => 'Deposit',
            'amount' => $stockItem->getAttribute('amount'),
            'auto_narration' => 'The shortage stock of '.$stockItem->getAttribute('amount').' was identified during the sales',
            'manual_narration' => 'The shortage amount of '.$stockItem->getAttribute('amount').' was identified during the sales',
            'tx_type_id' => 56,
            'company_id' => $stock->getAttribute('company_id'),
        ], 'StockShortage');

        return ['success' => true];
    }

    public function reject(StockShortage $stock, StockShortageItem $stockItem)
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