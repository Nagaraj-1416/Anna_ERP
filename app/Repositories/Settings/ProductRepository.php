<?php

namespace App\Repositories\Settings;

use App\Http\Requests\Setting\ProductStoreRequest;
use App\Product;
use App\ProductCategory;
use App\Repositories\BaseRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class ProductRepository
 * @package App\Repositories\Settings
 */
class ProductRepository extends BaseRepository
{
    protected $imagePath = 'product-images/';

    /**
     * ProductRepository constructor.
     * @param Product|null $product
     */
    public function __construct(Product $product = null)
    {
        $this->setModel($product ?? new Product());
    }

    /**
     * Get data to data table
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['code', 'name', 'type', 'buying_price', 'expense_account', 'wholesale_price', 'retail_price', 'distribution_price',
            'income_account', 'measurement', 'min_stock_level', 'inventory_account', 'notes', 'is_active'];

        $searchingColumns = ['code', 'name', 'type', 'buying_price', 'expense_account', 'wholesale_price', 'retail_price', 'distribution_price',
            'income_account', 'measurement', 'min_stock_level', 'inventory_account', 'notes', 'is_active'];

        $data = $this->getTableData($request, $columns, $searchingColumns);

        $data['data'] = array_map(function ($item) {
            $item['code'] = '<a href="' . route('setting.product.show', $item['id']) . '">' . $item['code'] . '</a>';
            $item['action'] = "<div class=\"button-group\">";
            if (can('show', $this->getModel())) {
                $item['action'] .= actionBtn('Show', null, ['setting.product.show', [$item['id']]], ['class' => 'btn-success']);
            }
            if (can('edit', $this->getModel())) {
                $item['action'] .= actionBtn('Edit', null, ['setting.product.edit', [$item['id']]]);
            }
            if (can('delete', $this->getModel())) {
                $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-product']);
            }
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    public function grid()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $typeId = \request()->input('type_id');
        $categoryId = \request()->input('category_id');
        $lastWeek = carbon()->subWeek();
        $products = Product::orderBy('id', 'desc')->with('category', 'stock');
        if ($search) {
            $products->where('code', 'LIKE', '%' . $search . '%')
                ->orWhere('name', 'LIKE', '%' . $search . '%')
                ->orWhere('type', 'LIKE', '%' . $search . '%')
                ->orwhere(function ($query) use ($search) {
                    $query->whereHas('category', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                    });
                });
        }

        if ($filter) {
            switch ($filter) {
                case 'active':
                    $products->where('is_active', 'Yes');
                    break;
                case 'inactive':
                    $products->where('is_active', 'No');
                    break;
                case 'recentlyCreated':
                    $products->where('created_at', '>', $lastWeek);
                    break;
                case 'recentlyModified':
                    $products->where('updated_at', '>', $lastWeek);
                    break;
            }
        }

        if ($typeId) {
            $products->where('type', $typeId);
        }

        if ($categoryId) {
            $products->where('category_id', $categoryId);
        }

        return $products->paginate(15)->toArray();
    }

    public function save(ProductStoreRequest $request)
    {
        if ($request->input('type') == 'Raw Material') {
            $prefix = 'RM';
        } elseif ($request->input('type') == 'Finished Good') {
            $prefix = 'FG';
        } else {
            $prefix = 'TPP';
        }
        $category = $request->input('category');

        if($category){
            $request->merge(['category_id' => $request->input('category')]);
            $categoryId = ProductCategory::firstOrCreate(['name' => $category]);
            $request->merge(['category_id' => $categoryId->id]);
        }
        $this->setCodePrefix($prefix, 'code', ['type' => $request->input('type')]);
        $request->merge(['code' => $this->getCode()]);
        //$request->merge(['barcode_number' => generateProductBarcodeNumber($request->input('type'))]);
        $request->merge(['barcode_number' => $request->input('barcode_number')]);
        $product = $this->model->fill($request->toArray());
        $product->save();

        /** upload product image to storage - if image attached only */
        $productImg = $request->file('product_image');
        if ($productImg) {
            $proImgType = $productImg->getClientOriginalExtension();
            $proImgName = $product->getAttribute('code') . '.' . $proImgType;
            Storage::put($this->imagePath . $proImgName, file_get_contents($productImg));

            /** update product image name to row item */
            $product->setAttribute('product_image', $proImgName);
            $product->save();
        }

        return $product;
    }

    /**
     * @param ProductStoreRequest $request
     * @param Product $product
     * @return Product
     */
    public function update(ProductStoreRequest $request, Product $product)
    {
        $request->merge(['code' => $product->code]);
        //$request->merge(['barcode_number' => $product->barcode_number]);
        $request->merge(['barcode_number' => $request->input('barcode_number')]);
        $this->setModel($product);
        $this->model->update($request->toArray());

        /** upload product image to storage - if image attached only */
        $productImg = $request->file('product_image');
        if ($productImg) {
            /** remove already available image if new image attached */
            //Storage::delete($this->imagePath . $product->getAttribute('product_image'));
            //$product->setAttribute('product_image', null);
            //$product->save();

            /** upload the new image to storage and update raw data item */
            $proImgType = $productImg->getClientOriginalExtension();
            $proImgName = $product->getAttribute('code') . '.' . $proImgType;
            Storage::put($this->imagePath . $proImgName, file_get_contents($productImg));

            /** update product image name to row item */
            $product->setAttribute('product_image', $proImgName);
            $product->save();
        }

        return $product;
    }

    /**
     * @param Product $product
     * @return array
     * @throws \Exception
     */
    public function delete(Product $product): array
    {
        $product->delete();
        return ['success' => true];
    }

    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * Get the breadcrumbs of the product module
     * @param string $method
     * @param Product|null $product
     * @return array|mixed
     */
    public function breadcrumbs(string $method, Product $product = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Products'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Products', 'route' => 'setting.product.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Products', 'route' => 'setting.product.index'],
                ['text' => $product->name ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Products', 'route' => 'setting.product.index'],
                ['text' => $product->name ?? ''],
                ['text' => 'Edit'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}
