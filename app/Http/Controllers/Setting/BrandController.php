<?php

namespace App\Http\Controllers\Setting;

use App\Http\Requests\Setting\BrandStoreRequest;
use App\Repositories\Settings\BrandRepository;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    /** @var BrandRepository */
    protected $brand;

    /**
     * BrandController constructor.
     * @param BrandRepository $brand
     */
    public function __construct(BrandRepository $brand)
    {
        $this->brand = $brand;
    }

    /**
     * Store the brand
     * @param BrandStoreRequest $request
     * @return JsonResponse
     */
    public function store(BrandStoreRequest $request)
    {
        $brand = $this->brand->storeItem($request->toArray());
        if ($request->ajax()) {
            return response()->json($brand->toArray());
        }
    }

    /**
     * Search business type for drop down
     * @param null $q
     * @return JsonResponse
     */
    public function search($q = null)
    {
        $response = $this->brand->search($q, 'name', ['name'], ['is_active' => ['No']]);
        return response()->json($response);
    }
}
