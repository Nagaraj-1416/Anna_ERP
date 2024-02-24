<?php

namespace App\Http\Controllers\Setting;

use App\Exports\ProductExport;
use App\Http\Controllers\Controller;

use App\Http\Requests\Setting\ProductStoreRequest;
use App\Product;
use App\PurchaseOrder;
use App\Repositories\Settings\ProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ProductController extends Controller
{
    /**
     * @var ProductRepository
     */
    protected $product;
    protected $imagePath;

    /**
     * StoreController constructor.
     * @param ProductRepository $product
     */
    public function __construct(ProductRepository $product)
    {
        $this->product = $product;
        $this->imagePath = $product->getImagePath();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        if (\request()->ajax()|| \request()->header('User-Agent') == 'Postman') {
            $products = $this->product->grid();
            return response()->json($products);
        }
        $this->authorize('index', $this->product->getModel());
        $breadcrumb = $this->product->breadcrumbs('index');
        //if (\request()->ajax()) {
        //    $products = $this->product->grid();
        //    return response()->json($products);
        //}
        return view('settings.product.index', compact('breadcrumb'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function dataTableData(Request $request)
    {
        if (\request()->ajax()) {
            return $this->product->dataTable($request);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->product->getModel());
        $breadcrumb = $this->product->breadcrumbs('create');
        return view('settings.product.create', compact('breadcrumb'));
    }

    /**
     * @param ProductStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(ProductStoreRequest $request)
    {
        //  \Log::info('Form submitted with data:', $request->all());
         if ( \request()->header('User-Agent') == 'Postman') {
            $product=$this->product->save($request);
            return response()->json($product);
        }
        $this->authorize('store', $this->product->getModel());
        $this->product->save($request);
        alert()->success('Product created successfully', 'Success')->persistent();
        return redirect()->route('setting.product.index');
    }

    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Product $product)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            return response()->json($product);
        }
        $this->authorize('show', $this->product->getModel());
        if (\request()->ajax()) return response()->json($product->toArray());
        $breadcrumb = $this->product->breadcrumbs('show', $product);
        $stock = $product->stocks()->first();
        return view('settings.product.show', compact('breadcrumb', 'product', 'stock'));
    }

    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Product $product)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            $product->company_id = $product->company ? $product->company->id : '';
            return response()->json($product);
        }
        $this->authorize('edit', $this->product->getModel());
        $breadcrumb = $this->product->breadcrumbs('edit', $product);
        $product->company_id = $product->company ? $product->company->id : '';
        return view('settings.product.edit', compact('breadcrumb', 'product'));
    }

    /**
     * @param ProductStoreRequest $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(ProductStoreRequest $request, Product $product)
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $single_product=$this->product->update($request, $product);
            return response()->json($single_product);
        }
        $this->authorize('update', $this->product->getModel());
        $this->product->update($request, $product);
        alert()->success('Product updated successfully', 'Success')->persistent();
        return redirect()->route('setting.product.index');
    }

    /**
     * @param Product $product
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(Product $product): JsonResponse
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
              $response = $this->product->delete($product);
        return response()->json($response);
        }
        $this->authorize('delete', $this->product->getModel());
        $response = $this->product->delete($product);
        return response()->json($response);
    }

    /**
     * Search products for drop down
     * @param $type
     * @param null $q
     * @return JsonResponse
     */
    public function search($type, $q = null)
    {
        if ($type != 'All') {
            $response = $this->product->search($q, 'name', ['name'], ['is_active' => 'No'], [['type', $type]]);
        } else {
            $response = $this->product->search($q, 'name', ['name'], ['is_active' => 'No']);
        }
        return response()->json($response);
    }

    /**
     * @param string $ids
     * @param null $q
     * @return JsonResponse
     */
    public function searchSalesProduct($ids = '', $q = null)
    {
        if (!json_decode($ids) && !$q && !is_array(json_decode($ids))) {
            $q = $ids;
        }
        $response = $this->product->search($q, 'name', ['name'], ['type' => ['Raw Material'], 'id' => json_decode($ids)]);
        return response()->json($response);
    }

    /**
     * @param Product $product
     * @return mixed
     */
    public function getImage(Product $product)
    {
        if ($product->getAttribute('product_image')) {
            $imagePath = Storage::get($this->imagePath . $product->getAttribute('product_image'));
        } else {
            $imagePath = Storage::get('data/default.png');
        }
        return response($imagePath)->header('Content-Type', 'image/jpg');
    }

    public function export()
    {
        if (\request()->input('type') == 'excel') {
            return $this->excelDownload();
        }
        $products = Product::with('stock')->get();
        $data = [];
        $data['products'] = $products;
        ini_set("pcre.backtrack_limit", "2000000");
        ini_set('memory_limit', '256M');
        $pdf = PDF::loadView('settings.product.export', $data);
        return $pdf->download(env('APP_NAME') . ' - Products.pdf');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function excelDownload()
    {
        return Excel::download(new ProductExport(), env('APP_NAME') . ' - Products.xlsx', 'Xlsx');
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function lastPurchasedPrices(Product $product)
    {
        $purchaseOrder = PurchaseOrder::whereHas('products', function ($query) use ($product) {
            $query->where('id', $product->id);
        })->with(['products' => function ($query) use ($product) {
            $query->where('id', $product->id);
        }])->orderBy('id', 'desc')->take(5)->get();
        $purchaseOrder = $purchaseOrder->map(function ($item, $product) {
            $ItemProduct = $item->products->first();
            return [
                'po_no' => $item->po_no,
                'order_date' => $item->order_date,
                'price' => $ItemProduct ? $ItemProduct->pivot->rate : $product->buying_price,
            ];
        })->toArray();
        return response()->json($purchaseOrder);
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function uploadImage(Product $product)
    {
        request()->validate(['image' => 'required|image']);
        $productImg = request()->file('image');
        if ($productImg) {
            $proImgType = $productImg->getClientOriginalExtension();
            $proImgName = $product->getAttribute('code') . '.' . $proImgType;
            Storage::put($this->imagePath . $proImgName, file_get_contents($productImg));
            /** update product image name to row item */
            $product->setAttribute('product_image', $proImgName);
            $product->save();
        }

        return response()->json(['success' => true]);
    }

    /**
     * @param Product $product
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function barcodeImage(Product $product){
        return response(base64_decode(getBarCodeImage($product->barcode_number)))->header('Content-Type', 'image/png');
    }

    /**
     * @param Product $product
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function downloadBarcodeImage(Product $product)
    {
        $fileContent = base64_decode(getBarCodeImage($product->barcode_number));
        $response = response($fileContent, 200, [
            'Content-Type' => 'image/png',
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename=$product->name-barcode.png",
            'Content-Transfer-Encoding' => 'binary',
        ]);
        ob_end_clean();
        return $response;
    }
}
