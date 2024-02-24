<?php

namespace App\Repositories\Settings;

use App\Http\Requests\Setting\{
    PriceBookStoreRequest
};
use App\Product;
use App\Repositories\BaseRepository;
use App\{Price, PriceBook, PriceHistory, PriceHistoryItem, ProductionUnit, Rep, SalesLocation, Store};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class PriceBookRepository
 * @package App\Repositories\Settings
 */
class PriceBookRepository extends BaseRepository
{
    /**
     * PriceBookRepository constructor.
     * @param PriceBook|null $priceBook
     */
    public function __construct(PriceBook $priceBook = null)
    {
        $this->setModel($priceBook ?? new PriceBook());
        $this->setCodePrefix('PB');
    }

    /**
     * Get data to data table
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['code', 'name', 'type', 'notes', 'is_active', 'prepared_by', 'company_id'];
        $searchingColumns = ['code', 'name', 'type', 'notes', 'is_active', 'prepared_by', 'company_id'];
        $relationColumns = [
            'company' => [
                [
                    'column' => 'name', 'as' => 'company_name'
                ]
            ]
        ];
        $data = $this->getTableData($request, $columns, $searchingColumns, $relationColumns);
        $data['data'] = array_map(function ($item) {
            $item['code'] = '<a href="' . route('setting.price.book.show', $item['id']) . '">' . $item['code'] . '</a>';
            $item['action'] = "<div class=\"button-group\">";
            $item['action'] .= actionBtn('Show', null, ['setting.price.book.show', [$item['id']]], ['class' => 'btn-success']);
            $item['action'] .= actionBtn('Edit', null, ['setting.price.book.edit', [$item['id']]]);
            $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-pb']);
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    public function grid()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $lastWeek = carbon()->subWeek();
        $priceBooks = PriceBook::orderBy('id', 'desc')->with('company', 'preparedBy', 'relatedTo');
        if ($search) {
            $priceBooks->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', '%' . $search . '%')
                    ->orWhere('name', 'LIKE', '%' . $search . '%');
            });
        }

        switch ($filter) {
            case 'Active':
                $priceBooks->where('is_active', 'Yes');
                break;
            case 'Inactive':
                $priceBooks->where('is_active', 'No');
                break;
            case 'recentlyCreated':
                $priceBooks->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $priceBooks->where('updated_at', '>', $lastWeek);
                break;
        }

        return $priceBooks->paginate(12)->toArray();
    }

    public function comparision()
    {
        $search = \request()->input('search');
        $products = Product::where('type', 'Finished Good')->with('prices');
        if ($search) {
            $products->where('code', 'LIKE', '%' . $search . '%')
                ->orWhere('name', 'LIKE', '%' . $search . '%')
                ->orWhere('type', 'LIKE', '%' . $search . '%');
        }
        return $products->paginate(12)->toArray();
    }

    /**
     * @param PriceBookStoreRequest $request
     * @return Model
     */
    public function save(PriceBookStoreRequest $request): Model
    {
        $request->merge(['code' => $this->getCode()]);
        $request->merge(['prepared_by' => auth()->id()]);

        $request->merge(['related_to_type' =>  $this->getRelatedModal($request->input('category'))]);
        $request->merge(['related_to_id' =>  $request->input('related_to')]);

        if (!$request->input('type')){
            $request->merge(['type' =>  'Selling Price']);
        }
        $priceBook = $this->model->fill($request->toArray());
        $priceBook->save();
        $this->savePrices($request, $priceBook);
        return $priceBook;
    }

    public function saveClone(PriceBookStoreRequest $request): Model
    {
        $request->merge(['code' => $this->getCode()]);
        $request->merge(['prepared_by' => auth()->id()]);

        $request->merge(['related_to_type' =>  $this->getRelatedModal($request->input('category'))]);
        $request->merge(['related_to_id' =>  $request->input('related_to')]);

        if (!$request->input('type')){
            $request->merge(['type' =>  'Selling Price']);
        }
        $priceBook = $this->model->fill($request->toArray());
        $priceBook->save();
        $this->clonePrices($request, $priceBook);
        return $priceBook;
    }

    /**
     * @param PriceBookStoreRequest $request
     * @param PriceBook $priceBook
     * @return PriceBook
     */
    public function update(PriceBookStoreRequest $request, PriceBook $priceBook): PriceBook
    {
        /** save old values */
        $this->saveOldPrices($request, $priceBook);

        $request->merge(['code' => $priceBook->code]);
        $request->merge(['prepared_by' => $priceBook->prepared_by]);

        $request->merge(['related_to_type' =>  $this->getRelatedModal($request->input('category'))]);
        $request->merge(['related_to_id' =>  $request->input('related_to')]);

        if (!$request->input('type')){
            $request->merge(['type' =>  'Selling Price']);
        }

        $this->setModel($priceBook);
        $this->model->update($request->toArray());
        $this->savePrices($request, $priceBook);
        return $priceBook;
    }

    /**
     * @param PriceBook $priceBook
     * @return array
     * @throws \Exception
     */
    public function delete(PriceBook $priceBook): array
    {
        $priceBook->delete();
        return ['success' => true];
    }

    /**
     * Search price book items
     * @param null $q
     * @return array
     */
    public function searchItem($q = null): array
    {
        if (!$q) {
            $items = PriceBook::get(['id', 'name'])->toArray();
        } else {
            $items = PriceBook::where('name', 'LIKE', '%' . $q . '%')->get(['id', 'name'])->toArray();
        }
        $items = array_map(function ($obj) {
            return ["name" => $obj['name'], "value" => $obj['id']];
        }, $items);
        return ["success" => true, "results" => $items];
    }

    /**
     * Search price book items base on location
     * @param SalesLocation $location
     * @param null $q
     * @return array
     */
    public function searchItemByLocation(SalesLocation $location, $q = null): array
    {
        $company = userCompany();
        if (!$q) {
            $items = PriceBook::whereCompanyId($company ? $company->id : null)
                ->whereCategory('Shop Selling Price')
                ->whereRelatedToId($location->id)
                ->whereRelatedToType('App\SalesLocation')
                ->get(['id', 'name'])->toArray();
        } else {
            $items = PriceBook::whereCompanyId($company ? $company->id : null)
                ->whereCategory('Shop Selling Price')
                ->whereRelatedToId($location->id)
                ->whereRelatedToType('App\SalesLocation')
            ->where('name', 'LIKE', '%' . $q . '%')->get(['id', 'name'])->toArray();
        }
        $items = array_map(function ($obj) {
            return ["name" => $obj['name'], "value" => $obj['id']];
        }, $items);
        return ["success" => true, "results" => $items];
    }

    /**
     * Search price book items base on van location
     * @param SalesLocation $location
     * @param null $q
     * @return array
     */
    public function searchItemByVanLocation(SalesLocation $location, $q = null): array
    {
        $company = userCompany();
        if (!$q) {
            $items = PriceBook::whereCompanyId($company ? $company->id : null)
                ->whereCategory('Van Selling Price')
                ->whereRelatedToId($location->id)
                ->whereRelatedToType('App\SalesLocation')
                ->get(['id', 'name'])->toArray();
        } else {
            $items = PriceBook::whereCompanyId($company ? $company->id : null)
                ->whereCategory('Van Selling Price')
                ->whereRelatedToId($location->id)
                ->whereRelatedToType('App\SalesLocation')
                ->where('name', 'LIKE', '%' . $q . '%')->get(['id', 'name'])->toArray();
        }
        $items = array_map(function ($obj) {
            return ["name" => $obj['name'], "value" => $obj['id']];
        }, $items);
        return ["success" => true, "results" => $items];
    }

    /**
     * Search price book items base rep
     * @param Rep $rep
     * @param null $q
     * @return array
     */
    public function searchItemByRep(Rep $rep, $q = null): array
    {
        $company = userCompany();
        if (!$q) {
            $items = PriceBook::whereCompanyId($company ? $company->id : null)
                ->whereCategory('Van Selling Price')
                ->whereRelatedToId($rep->id)
                ->whereRelatedToType('App\Rep')
                ->get(['id', 'name'])->toArray();
        } else {
            $items = PriceBook::whereCompanyId($company ? $company->id : null)
                ->whereCategory('Van Selling Price')
                ->whereRelatedToId($rep->id)
                ->whereRelatedToType('App\Rep')
                ->where('name', 'LIKE', '%' . $q . '%')->get(['id', 'name'])->toArray();
        }
        $items = array_map(function ($obj) {
            return ["name" => $obj['name'], "value" => $obj['id']];
        }, $items);
        return ["success" => true, "results" => $items];
    }

    /**
     * @param string $method
     * @param PriceBook|null $priceBook
     * @return array
     */
    public function breadcrumbs(string $method, PriceBook $priceBook = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Price Books'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Price Books', 'route' => 'setting.price.book.index'],
                ['text' => 'Create'],
            ],
            'clone' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Price Books', 'route' => 'setting.price.book.index'],
                ['text' => 'Clone'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Price Books', 'route' => 'setting.price.book.index'],
                ['text' => $priceBook->name ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Price Books', 'route' => 'setting.price.book.index'],
                ['text' => $priceBook->name ?? ''],
                ['text' => 'Edit'],
            ],
            'comparison' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Price Books', 'route' => 'setting.price.book.index'],
                ['text' => 'Comparison'],
            ],
            'history' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Price Books', 'route' => 'setting.price.book.index'],
                ['text' => 'History'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    public function saveOldPrices($request, $priceBook)
    {
        $priceHistory = new PriceHistory();
        $priceHistory->date = carbon()->now()->toDateTimeString();
        $priceHistory->updated_by = auth()->id();
        $priceHistory->price_book_id = $priceBook->id;;
        $priceHistory->save();

        $products = $request->input('products');
        $rangeStart = $request->input('range_start_from');
        $rangeEnd = $request->input('range_end_to');
        $amount = $request->input('amount');
        $ids = $request->input('ids');
        foreach ($products as $key => $item) {
            $priceHistoryItem = new PriceHistoryItem();

            $priceHistoryItem->range_start_from = $rangeStart[$key];
            $priceHistoryItem->range_end_to = $rangeEnd[$key];
            $priceHistoryItem->price = $amount[$key];
            $priceHistoryItem->product_id = $products[$key];
            $priceHistoryItem->price_book_id = $priceBook->id;
            $priceHistoryItem->price_history_id = $priceHistory->id;
            $priceHistoryItem->save();
        }
    }

    public function savePrices($request, $priceBook)
    {
        $products = $request->input('products');
        $rangeStart = $request->input('range_start_from');
        $rangeEnd = $request->input('range_end_to');
        $amount = $request->input('amount');
        $ids = $request->input('ids');
        $oldIds = $priceBook->prices->pluck('id')->toArray();
        $deletedPriceIds = array_diff($oldIds, $ids);
        if ($deletedPriceIds) {
            Price::whereIn('id', $deletedPriceIds)->delete();
        }
        foreach ($products as $key => $item) {
            $price = new Price();
            $oldPrice = Price::find($ids[$key]);
            if ($oldPrice) {
                $price = $oldPrice;
            }
            $price->range_start_from = $rangeStart[$key];
            $price->range_end_to = $rangeEnd[$key];
            $price->price = $amount[$key];
            $price->product_id = $products[$key];
            $price->price_book_id = $priceBook->id;
            $price->save();
        }
    }

    public function clonePrices($request, $priceBook)
    {
        $products = $request->input('products');
        $rangeStart = $request->input('range_start_from');
        $rangeEnd = $request->input('range_end_to');
        $amount = $request->input('amount');
        foreach ($products as $key => $item) {
            $price = new Price();
            $price->range_start_from = $rangeStart[$key];
            $price->range_end_to = $rangeEnd[$key];
            $price->price = $amount[$key];
            $price->product_id = $products[$key];
            $price->price_book_id = $priceBook->id;
            $price->save();
        }
    }

    /**
     * @param $related
     * @return string
     */
    public function getRelatedModal($related)
    {
        if ($related == 'Production To Store') {
            return get_class(new ProductionUnit());
        } else if ($related == 'Store To Store') {
            return get_class(new Store());
        } else if ($related == 'Store To Shop') {
            return get_class(new Store());
        } else if ($related == 'Shop Selling Price') {
            return get_class(new SalesLocation());
        } else if ($related == 'Van Selling Price') {
            return get_class(new Rep());
        }
        return '';
    }

    /**
     * @param PriceBook $priceBook
     * @return array
     */
    public function getEditData(PriceBook $priceBook)
    {
        $prices = $priceBook->prices()->get();
        $prices = $prices->sortBy('product.name');
        $data = [];
        $data['products'] = [];
        $data['product_name'] = [];
        $data['amount'] = [];
        $data['range_start_from'] = [];
        $data['range_end_to'] = [];
        $data['ids'] = [];
        foreach ($prices as $price) {
            if ($price->product) {
                array_push($data['products'], $price->product->id);
                array_push($data['product_name'], $price->product->name);
            }
            array_push($data['amount'], $price->price);
            array_push($data['range_start_from'], $price->range_start_from);
            array_push($data['range_end_to'], $price->range_end_to);
            array_push($data['ids'], $price->id);
        }
        return $data;
    }
}
