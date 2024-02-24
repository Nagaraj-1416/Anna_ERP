<?php

namespace App\Repositories\Sales;

use App\Http\Requests\Sales\EstimateStoreRequest;
use App\Repositories\General\DocumentRepository;
use App\{
    SalesInquiry, Store, Estimate
};
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

/**
 * Class OrderRepository
 * @package App\Repositories\Sales
 */
class EstimateRepository extends BaseRepository
{
    protected $document;

    /**
     * EstimateRepository constructor.
     * @param Estimate|null $estimate
     * @param DocumentRepository $document
     */
    public function __construct(Estimate $estimate = null, DocumentRepository $document)
    {
        $this->document = $document;
        $this->setModel($estimate ?? new Estimate());
        $this->setCodePrefix('EST', 'estimate_no');
    }

    public function save(Request $request, $isApi = false)
    {
        $estimate = $this->storeData($request, $isApi);
        $this->updateInquiry($request, $estimate);
        return $estimate;
    }

    public function storeData(Request $request, $isApi = false)
    {
        $company = userCompany(auth()->user());

        /** get listed products */
        $products = $this->mapProducts($request, $isApi);
        $productAmounts = array_pluck($products, 'amount');
        $productAmount = array_sum($productAmounts);

        if (!$request->input('discount_type')) {
            $request->merge(['discount_type' => 'Amount']);
        }

        if (!$request->input('discount_rate')) {
            $request->merge(['discount_rate' => 0]);
        }

        /** get given discount */
        $discount = 0;
        if ($request->input('discount_type') == 'Percentage') {
            if ($request->input('discount_type') > 0) {
                $discount = $productAmount * ($request->input('discount_rate') / 100);
            }
        } else {
            $discount = $request->input('discount_rate');
        }

        if (!$this->model->getAttribute('estimate_no')) {
            $this->model->setAttribute('estimate_no', $this->getCode());
        }
        $this->model->setAttribute('estimate_date', $request->input('estimate_date'));
        $this->model->setAttribute('expiry_date', $request->input('expiry_date'));

        $this->model->setAttribute('terms', $request->input('terms'));
        $this->model->setAttribute('notes', $request->input('notes'));

        /** set subtotal, discount, adjustment and total */
        $this->model->setAttribute('sub_total', $productAmount);
        $this->model->setAttribute('discount_type', $request->input('discount_type'));
        $this->model->setAttribute('discount_rate', $request->input('discount_rate'));
        $this->model->setAttribute('discount', $discount);

        $adjustment = $request->input('adjustment') ? $request->input('adjustment') : 0;
        $this->model->setAttribute('adjustment', $adjustment);

        $totalInput = $request->input('total') ? $request->input('total') : 0;
        $totalAmount = ($totalInput - $discount) + $totalInput;
        $this->model->setAttribute('total', $totalAmount);

        $this->model->setAttribute('prepared_by', auth()->id());
        $this->model->setAttribute('rep_id', $request->input('rep_id'));
        $this->model->setAttribute('customer_id', $request->input('customer_id'));
        $this->model->setAttribute('business_type_id', $request->input('business_type_id'));
        $this->model->setAttribute('company_id', $company->id);
        $this->model->save();

        /** attach products to order */
        $products = $this->mapProducts($request, $isApi);
        $this->model->products()->attach($products);

        /** upload support documents */
        $files = $request->file('files');
        if ($files) {
            foreach ($files as $file) {
                $this->document->setDocumentable($this->model);
                $this->document->save($file);
            }
        }
        return $this->model;
    }

    /**
     * @param Request $request
     * @param bool $isApi
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(Request $request, $isApi = false)
    {
        $this->model->products()->detach();
        return $this->storeData($request, $isApi);
    }

    /**
     * @param Estimate $estimate
     * @return array
     */
    public function delete(Estimate $estimate): array
    {
        try {
            $estimate->delete();
            return ['success' => true, 'message' => 'Deleted success'];
        } catch (\Exception $e) {
            return ['success' => true, 'message' => 'Deleted failed'];
        }

    }

    /**
     * @param Request $request
     * @param Estimate $estimate
     */
    protected function updateInquiry(Request $request, Estimate $estimate)
    {
        if ($request->input('inquiry_id')){
            $inquiry = SalesInquiry::find($request->input('inquiry_id'));
            if (!$inquiry) return;
            if ($inquiry->status != 'Open') return;
            $inquiry->converted_type = 'App\\Estimate';
            $inquiry->converted_id = $estimate->id;
            $inquiry->status = 'Converted to Estimate';
            $inquiry->save();
        }
    }

    /**
     * @param string $method
     * @param Estimate|null $estimate
     * @return array
     */
    public function breadcrumbs(string $method, Estimate $estimate = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Estimates'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Estimates', 'route' => 'sales.estimate.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Estimates', 'route' => 'sales.estimate.index'],
                ['text' => $estimate->estimate_no ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Estimates', 'route' => 'sales.estimate.index'],
                ['text' => $estimate->estimate_no ?? ''],
                ['text' => 'Edit'],
            ],
            'clone' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Estimates', 'route' => 'sales.estimate.index'],
                ['text' => $estimate->estimate_no ?? ''],
                ['text' => 'Clone'],
            ],
            'print' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Estimates', 'route' => 'sales.estimate.index'],
                ['text' => $estimate->estimate_no ?? ''],
                ['text' => 'Print Estimate'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    /**
     * @param Request $request
     * @param bool $isApi
     * @return array
     */
    public function mapProducts(Request $request, $isApi = false)
    {
        $mappedProducts = [];
        if ($isApi) {
            if (!$request->input('order_items') || !is_array($request->input('order_items'))) return [];
            foreach ($request->input('order_items') as $item) {
                if (!isset($item['quantity'])) {
                    $item['quantity'] = 0;
                }
                if (!isset($item['rate'])) {
                    $item['rate'] = 0;
                }
                $amount = ($item['quantity'] * $item['rate']);
                $discount = 0;
                if (isset($item['discount_type']) && isset($item['discount_rate']) && isset($amount) && $item['discount_type'] == 'Percentage') {
                    if ($item['discount_rate'] > 0) {
                        $discount = $amount * ($item['discount_rate'] / 100);
                    }
                }
                if (isset($item['discount_type']) && isset($item['discount_rate']) && isset($amount) && $item['discount_type'] == 'Amount') {
                    $discount = $item['discount_rate'];
                }
                $totalAmount = $amount - $discount;
                $item['discount'] = $discount;
                $item['amount'] = $totalAmount;
                $item['notes'] = $item['notes'] ?? null;
                $item['estimate_id'] = $this->model->id;
                array_push($mappedProducts, $item);
            }
        } else {
            $products = $request->input('product');
            $stores = $request->input('store');
            $rate = $request->input('rate');
            $qty = $request->input('quantity');
            $discountRate = $request->input('item_discount_rate');
            $discountType = $request->input('item_discount_type');
            $notes = $request->input('product_notes');
            foreach ($products as $key => $product) {
                if (!$product) continue;
                $amount = ($qty[$key] * $rate[$key]);
                $discount = 0;
                if (isset($discountType[$key]) && isset($discountRate[$key]) && isset($amount) && $discountType[$key] == 'Percentage') {
                    if ($discountRate[$key] > 0) {
                        $discount = $amount * ($discountRate[$key] / 100);
                    }
                }
                if (isset($discountType[$key]) && isset($discountRate[$key]) && isset($amount) && $discountType[$key] == 'Amount') {
                    $discount = $discountRate[$key];
                }
                if (!isset($qty[$key])) {
                    $qty[$key] = 0;
                }
                if (!isset($rate[$key])) {
                    $rate[$key] = 0;
                }

                $totalAmount = $amount - $discount;
                $mappedProduct = [
                    'estimate_id' => $this->model->id ?? null,
                    'product_id' => $products[$key] ?? null,
                    'store_id' => $stores[$key] ?? null,
                    'quantity' => $qty[$key] ?? null,
                    'rate' => $rate[$key] ?? null,
                    'discount_type' => $discountType[$key] ?? null,
                    'discount_rate' => $discountRate[$key] ?? null,
                    'discount' => $discount,
                    'amount' => $totalAmount ?? null,
                    'notes' => $notes[$key] ?? null,
                ];
                array_push($mappedProducts, $mappedProduct);
            }
        }
        return $mappedProducts;
    }

    /**
     * @param Estimate $estimate
     * @return mixed
     */
    public function productItems(Estimate $estimate)
    {
        $estimate->load('products');
        $products = $estimate->products;
        $stores = Store::all();
        return $products->map(function ($productItem) use ($products, $stores) {
            if (!$productItem->pivot) {
                return $productItem;
            }
            $product = $products->where('id', $productItem->pivot->product_id)->first();
            $store = $stores->where('id', $productItem->pivot->store_id)->first();
            $pivotData = $productItem->pivot;
            $productItem->store_name = $store ? $store->name : null;
            $productItem->product_name = $product ? $product->name : null;
            $productItem->pivot = null;
            return array_merge($productItem->toArray(), $pivotData->toArray());
        });
    }

    /**
     * @param Estimate $estimate
     * @return array
     */
    public function send(Estimate $estimate): array
    {
        $estimate->setAttribute('status', 'Sent');
        $estimate->save();
        return ['success' => true];
    }

    /**
     * @param Estimate $estimate
     * @return array
     */
    public function accept(Estimate $estimate): array
    {
        $estimate->setAttribute('status', 'Accepted');
        $estimate->save();
        return ['success' => true];
    }

    /**
     * @param Estimate $estimate
     * @return array
     */
    public function decline(Estimate $estimate): array
    {
        $estimate->setAttribute('status', 'Declined');
        $estimate->save();
        return ['success' => true];
    }

    public function getEstimations()
    {
        $filter = \request()->input('filter');
        $search = \request()->input('search');
        $id = \request()->input('salesRepId');
        $userId = \request()->input('userId');
        $customerId = \request()->input('customerId');
        $productId = \request()->input('productId');
        $lastWeek = carbon()->subWeek();
        $estimates = Estimate::whereIn('company_id', userCompanyIds(loggedUser()))
            ->with('customer')->orderBy('id', 'desc');
        switch ($filter) {
            case 'Draft':
                $estimates->where('status', 'Draft');
                break;
            case 'Sent':
                $estimates->where('status', 'Sent');
                break;
            case 'Accepted':
                $estimates->where('status', 'Accepted');
                break;
            case 'Declined':
                $estimates->where('status', 'Declined');
                break;
            case 'Converted':
                $estimates->where('status', 'Ordered');
                break;
            case 'recentlyCreated':
                $estimates->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $estimates->where('updated_at', '>', $lastWeek);
                break;
        }
        if ($search) {
            $estimates->where(function ($q) use ($search) {
                $q->where('estimate_no', 'LIKE', '%' . $search . '%')
                    ->orWhere('expiry_date', 'LIKE', '%' . $search . '%')
                    ->orWhere('status', 'LIKE', '%' . $search . '%')
                    ->orWhere(function ($query) use ($search) {
                        $query->whereHas('customer', function ($q) use ($search) {
                            $q->where('display_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('mobile', 'LIKE', '%' . $search . '%');
                        });
                    });
            });
        }
        if ($id) {
            $estimates->where('rep_id', $id);
        }
        if ($userId) {
            $estimates->where('prepared_by', $userId);
        }

        if ($customerId) {
            $estimates->where('customer_id', $customerId);
        }

        if ($productId) {
            $estimates->where(function ($q) use ($productId) {
                $q->whereHas('products', function ($q) use ($productId) {
                    $q->where('id', $productId);
                });
            });
        }
        return $estimates->paginate(12)->toArray();
    }
}