<?php

namespace App\Repositories\Settings;

use App\Http\Requests\Setting\ProductCategoryRequest;
use App\ProductCategory;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

class ProductCategoryRepository extends BaseRepository
{
    /**
     * ProductRepository constructor.
     * @param ProductCategory|null $productCategory
     */
    public function __construct(ProductCategory $productCategory = null)
    {
        $this->setModel($productCategory ?? new ProductCategory());
    }

    public function dataTable(Request $request)
    {
        $columns = ['name'];
        $searchingColumns = ['name'];
        $data = $this->getTableData($request, $columns, $searchingColumns);
        $data['data'] = array_map(function ($item) {
            $item['action'] = "<div class=\"button-group\">";
            $item['action'] .= actionBtn('Edit', null, ['setting.product.category.edit', [$item['id']]]);
            $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-category']);
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    /**
     * @param ProductCategoryRequest $request
     * @return mixed
     */
    public function store(ProductCategoryRequest $request)
    {
        $category = $this->model->fill($request->toArray());
        $category->save();
        return $category;
    }

    /**
     * @param ProductCategory $category
     * @param ProductCategoryRequest $request
     * @return ProductCategory
     */
    public function update(ProductCategory $category, ProductCategoryRequest $request)
    {
        $this->setModel($category);
        $this->model->update($request->toArray());
        return $category;
    }

    /**
     * @param ProductCategory $category
     * @return array
     * @throws \Exception
     */
    public function delete(ProductCategory $category): array
    {
        if (!$category->products()->count()) {
            $category->delete();
            return ['success' => true];
        }
        return ['error' => true];
    }

    public function breadcrumbs(string $method, ProductCategory $category = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Products', 'route' => 'setting.product.index'],
                ['text' => 'Product category'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Products', 'route' => 'setting.product.index'],
                ['text' => 'Product category', 'route' => 'setting.product.category.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Products', 'route' => 'setting.product.index'],
                ['text' => 'Product category', 'route' => 'setting.product.category.index'],
                ['text' => $category->name ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Products', 'route' => 'setting.product.index'],
                ['text' => 'Product category', 'route' => 'setting.product.category.index'],
                ['text' => $category->name ?? ''],
                ['text' => 'Edit'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    public function searchData($q = null)
    {
        if (!$q) {
            $category = $this->model->get(['name'])->toArray();
        } else {
            $category = $this->model->where(function ($query) use ($q) {
                $query->where('name', 'LIKE', '%' . $q . '%');
            })
                ->get(['name'])
                ->toArray();
        }
        // mapping the data
        $category = array_map(function ($obj) {
            return ["name" => $obj['name'], "value" => $obj['name']];
        }, $category);
        return ["success" => true, "results" => $category];
    }
}