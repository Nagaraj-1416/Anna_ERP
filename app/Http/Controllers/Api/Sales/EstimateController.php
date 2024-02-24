<?php

namespace App\Http\Controllers\Api\Sales;

use App\Estimate;
use App\Http\Requests\Api\Sales\EstimateStoreRequest;
use App\Http\Resources\EstimateResource;
use App\Repositories\Sales\EstimateRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EstimateController extends Controller
{
    /**
     * @var EstimateRepository
     */
    protected $estimate;

    /**
     * EstimateController constructor.
     * @param EstimateRepository $estimate
     */
    public function __construct(EstimateRepository $estimate)
    {
        $this->estimate = $estimate;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $orders = Estimate::where('prepared_by', auth()->id())->get();
        return EstimateResource::collection($orders);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function todayIndex(): AnonymousResourceCollection
    {
        $orders = Estimate::where('prepared_by', auth()->id())->with(['products', 'preparedBy', 'rep', 'customer', 'comments'])->get();
        return EstimateResource::collection($orders);
    }

    /**
     * @param EstimateStoreRequest $request
     * @return EstimateResource
     */
    public function store(EstimateStoreRequest $request): EstimateResource
    {
        $estimate = $this->estimate->save($request, true);
        return new EstimateResource($estimate);
    }

    /**
     * @param Estimate $estimate
     * @return EstimateResource
     */
    public function show(Estimate $estimate): EstimateResource
    {
        $estimate->load('products', 'preparedBy', 'rep', 'customer', 'comments');
        return new EstimateResource($estimate);
    }

    /**
     * @param EstimateStoreRequest $request
     * @param Estimate $estimate
     * @return EstimateResource
     */
    public function update(EstimateStoreRequest $request, Estimate $estimate): EstimateResource
    {
        $this->estimate->setModel($estimate);
        $estimate = $this->estimate->update($request, true);
        return new EstimateResource($estimate);
    }

    /**
     * @param Estimate $estimate
     * @return array
     */
    public function delete(Estimate $estimate): array
    {
        return $this->estimate->delete($estimate);
    }
}
