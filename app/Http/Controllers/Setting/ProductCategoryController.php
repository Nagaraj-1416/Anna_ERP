<?php

namespace App\Http\Controllers\Setting;

use App\Http\Requests\Setting\ProductCategoryRequest;
use App\ProductCategory;
use App\Repositories\Settings\ProductCategoryRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Class ProductCategoryController
 * @package App\Http\Controllers\Setting
 */
class ProductCategoryController extends Controller
{
    /**
     * @var ProductCategoryRepository
     */
    protected $productCategory;

    /**
     * ProductCategoryController constructor.
     * @param ProductCategoryRepository $productCategory
     */
    public function __construct(ProductCategoryRepository $productCategory)
    {
        $this->productCategory = $productCategory;
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        if (\request()->ajax()|| \request()->header('User-Agent') == 'Postman') {
            $productCategory = $this->productCategory->grid();
            return response()->json($productCategory);
        }
        $breadcrumb = $this->productCategory->breadcrumbs('index');
        return view('settings.product.category.index', compact('breadcrumb'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function dataTableData(Request $request)
    {
        if (\request()->ajax()) {
            return $this->productCategory->dataTable($request);
        }
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        $breadcrumb = $this->productCategory->breadcrumbs('create');
        return view('settings.product.category.create', compact('breadcrumb'));
    }

    /**
     * @param ProductCategoryRequest $request
     * @return RedirectResponse
     */
    public function store(ProductCategoryRequest $request)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            $category = $this->productCategory->store($request);
            return response()->json('Product Category added successfully');
        }else{
            $category = $this->productCategory->store($request);
            return redirect()->route('setting.product.category.index');
        }
       
    }

    /**
     * @param ProductCategory $category
     * @return Factory|View
     */
    public function edit(ProductCategory $category)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            $category->company_id = $category->company ? $category->company->id : '';
            return response()->json($category);
        }
        $breadcrumb = $this->productCategory->breadcrumbs('edit', $category);
        return view('settings.product.category.edit', compact('breadcrumb', 'category'));
    }

    /**
     * @param ProductCategory $category
     * @param ProductCategoryRequest $request
     * @return RedirectResponse
     */
    public function update(ProductCategory $category, ProductCategoryRequest $request)
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $category = $this->productCategory->update($category, $request);
            return response()->json($category);
        }
        $category = $this->productCategory->update($category, $request);
        return redirect()->route('setting.product.category.index');
    }

    /**
     * @param ProductCategory $category
     * @return Factory/View
     */
    public function show(ProductCategory $category)
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            return response()->json($category);
        }
        $breadcrumb = $this->productCategory->breadcrumbs('show', $category);
        return view('settings.product.category.show', compact('breadcrumb', 'category'));
    }

    /**
     * @param ProductCategory $category
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(ProductCategory $category)
    {
        $response = $this->productCategory->delete($category);
        return response()->json($response);
    }

    /**
     * @param null $q
     * @return JsonResponse
     */
    public function search($q = null): JsonResponse
    {
        $response = $this->productCategory->search($q, 'name', ['name']);
        return response()->json($response);
    }
}
