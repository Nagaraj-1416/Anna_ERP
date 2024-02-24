<?php

namespace App\Repositories\Purchase;

use App\BillPayment;
use App\Http\Requests\Purchase\BillStoreRequest;
use App\Bill;
use App\Http\Requests\Purchase\CancelRequest;
use App\PurchaseReturn;
use App\Repositories\BaseRepository;
use App\PurchaseOrder;
use Illuminate\Http\Request;

/**
 * Class ReturnRepository
 * @package App\Repositories\Purchase
 */
class ReturnRepository extends BaseRepository
{
    /**
     * ReturnRepository constructor.
     * @param PurchaseReturn|null $return
     */
    public function __construct(PurchaseReturn $return = null)
    {
        $this->setModel($return ?? new PurchaseReturn());
    }

    /**
     * @param string $method
     * @param Bill|null $bill
     * @return array
     */
    public function breadcrumbs(string $method, Bill $bill = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Returns'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Returns', 'route' => 'purchase.return.index'],
                ['text' => 'Return Details'],
            ],
            'print' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Returns', 'route' => 'purchase.return.index'],
                ['text' => 'Print Bill'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    public function getReturns()
    {
        $filter = request()->input('filter');
        $search = request()->input('search');
        $lastWeek = carbon()->subWeek();
        $returns = PurchaseReturn::whereIn('company_id', userCompanyIds(loggedUser()))
            ->orderBy('id', 'desc')->with('productionUnit', 'company', 'items');

        switch ($filter) {
            case 'recentlyCreated':
                $returns->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $returns->where('updated_at', '>', $lastWeek);
                break;
        }

        return $returns->paginate(20)->toArray();
    }

}