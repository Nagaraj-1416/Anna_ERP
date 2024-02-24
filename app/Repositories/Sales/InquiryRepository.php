<?php

namespace App\Repositories\Sales;

use App\Customer;
use App\Repositories\BaseRepository;
use App\Repositories\General\DocumentRepository;
use App\SalesInquiry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class InquiryRepository
 * @package App\Repositories\Sales
 */
class InquiryRepository extends BaseRepository
{
    /** @var DocumentRepository */
    protected $document;

    /**
     * InquiryRepository constructor.
     * @param SalesInquiry|null $inquiry
     * @param DocumentRepository $document
     */
    public function __construct(SalesInquiry $inquiry = null, DocumentRepository $document)
    {
        $this->document = $document;
        $this->setModel($inquiry ?? new SalesInquiry());
        $this->setCodePrefix('INQ', 'code');
    }

    /**
     *  For index page
     * @return mixed
     */
    public function index()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $userId = \request()->input('user_id');
        $customerId = \request()->input('customer_id');
        $productId = \request()->input('product_id');
        $lastWeek = carbon()->subWeek();
        $inquiries = SalesInquiry::whereIn('company_id', userCompanyIds(loggedUser()))
            ->with('customer')->orderBy('id', 'desc');
        if ($search) {
            $inquiries->where(function($q) use($search){
                $q->where('code', 'LIKE', '%' . $search . '%')
                    ->orWhere('status', 'LIKE', '%' . $search . '%')
                    ->orWhere('status', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%')
                    ->orWhere('inquiry_date', 'LIKE', '%' . $search . '%')
                    ->orwhere(function ($query) use ($search) {
                        $query->whereHas('customer', function ($q) use ($search) {
                            $q->where('display_name', 'LIKE', '%' . $search . '%');
                        });
                    });
            });
        }
        switch ($filter) {
            case 'Open':
                $inquiries->where('status', 'Open');
                break;
            case 'ConvertedToEstimate':
                $inquiries->where('status', 'Converted to Estimate');
                break;
            case 'ConvertedToOrder':
                $inquiries->where('status', 'Converted to Order');
                break;
            case 'recentlyCreated':
                $inquiries->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $inquiries->where('updated_at', '>', $lastWeek);
                break;
        }

        if ($userId) {
            $inquiries->where('prepared_by', $userId);
        }
        if ($customerId) {
            $inquiries->where('customer_id', $customerId);
        }
        if ($productId) {
            $inquiries->whereHas('products', function ($builder) use ($productId) {
                $builder->where('product_id', $productId);
            });
        }
        return $inquiries->paginate(12)->toArray();
    }

    /**
     * @param Request $request
     * @param bool $isAPI
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function save(Request $request, $isAPI = false)
    {
        $request->merge(['company_id' => userCompany() ? userCompany()->id : null]);
        return $this->storeData($request, $isAPI);
    }

    /**
     * @param Request $request
     * @param bool $isAPI
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(Request $request, $isAPI = false)
    {
        $this->model->products()->detach();
        return $this->storeData($request, $isAPI);
    }

    /**
     * @return array
     */
    public function delete(): array
    {
        try {
            $this->model->delete();
            return ['success' => true, 'message' => 'deleted success'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'deleted failed'];
        }
    }

    /**
     * @param Request $request
     * @param bool $isAPI
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function storeData(Request $request, $isAPI = false)
    {
        if (!$this->model->getAttribute('code')) {
            $this->model->setAttribute('code', $this->getCode());
            $this->model->setAttribute('prepared_by', auth()->id());
        }

        if ($request->input('company_id')){
            $this->model->setAttribute('company_id', $request->input('company_id'));
        }

        $this->model->setAttribute('business_type_id', $request->input('business_type_id'));
        $this->model->setAttribute('customer_id', $request->input('customer_id'));
        $this->model->setAttribute('inquiry_date', $request->input('inquiry_date'));
        $this->model->setAttribute('description', $request->input('description'));
        $this->model->save();

        /** attach products to order */
        $products = $this->mapProducts($request, $isAPI);
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
     * @param bool $isAPI
     * @return array
     */
    public function mapProducts(Request $request, $isAPI = false)
    {
        $mappedProducts = [];
        if ($isAPI) {
            if (!$request->input('order_items') || !is_array($request->input('order_items'))) return [];
            foreach ($request->input('order_items') as $item) {
                $newItem = [];
                $newItem['sales_inquiry_id'] = $this->model->id ?? null;
                $newItem['product_id'] = $item['product_id'] ?? null;
                $newItem['quantity'] = $item['quantity'] ?? null;
                $newItem['delivery_date'] = $item['delivery_date'] ?? null;
                $newItem['notes'] = $item['product_notes'] ?? null;
                array_push($mappedProducts, $newItem);
            }
        } else {
            $products = $request->input('product_id');
            $quantities = $request->input('quantity');
            $deliveryDates = $request->input('delivery_date');
            $notes = $request->input('product_notes');
            foreach ($products as $key => $productId) {
                if (!$productId) continue;
                $mappedProduct = [
                    'sales_inquiry_id' => $this->model->id ?? null,
                    'product_id' => $products[$key] ?? null,
                    'quantity' => $quantities[$key] ?? null,
                    'delivery_date' => $deliveryDates[$key] ?? null,
                    'notes' => $notes[$key] ?? null,
                ];
                array_push($mappedProducts, $mappedProduct);
            }
        }
        return $mappedProducts;
    }

    /**
     * @param SalesInquiry $inquiry
     * @return mixed
     */
    public function productItems(SalesInquiry $inquiry)
    {
        $inquiry->load('products');
        $products = $inquiry->products;
        $customers = Customer::all();
        return $products->map(function ($productItem) use ($products, $customers) {
            if (!$productItem->pivot) {
                return $productItem;
            }
            $product = $products->where('id', $productItem->pivot->product_id)->first();
            $customer = $customers->where('id', $productItem->pivot->customer_id)->first();
            $pivotData = $productItem->pivot;
            $productItem->product_name = $product ? $product->name : null;
            $productItem->customer_name = $customer ? $customer->display_name : null;
            $productItem->pivot = null;
            return array_merge($productItem->toArray(), $pivotData->toArray());
        });
    }

    /**
     * @param null|SalesInquiry $inquiry
     * @param string|null $method
     * @return array
     */
    public function breadcrumbs(SalesInquiry $inquiry = null, string $method = null): array
    {
        if (!$method) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
            $method = $backtrace[1]['function'] ?? null;
        }
        $base = [
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Sales', 'route' => 'sales.index'],
        ];
        $breadcrumbs = [
            'index' => array_merge($base, [
                ['text' => 'Inquiries'],
            ]),
            'create' => array_merge($base, [
                ['text' => 'Inquiries', 'route' => 'sales.inquiries.create'],
                ['text' => 'Create']
            ]),
            'show' => array_merge($base, [
                ['text' => 'Inquiries', 'route' => 'sales.inquiries.index'],
                ['text' => $inquiry->code ?? ''],
            ]),
            'edit' => array_merge($base, [
                ['text' => 'Inquiries', 'route' => 'sales.inquiries.index'],
                ['text' => $inquiry->code ?? ''],
                ['text' => 'Edit'],
            ])
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}