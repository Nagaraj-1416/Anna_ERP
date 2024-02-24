<?php

namespace App\Http\Requests\Sales;

use App\DailySale;
use App\Product;
use App\Rep;
use App\Repositories\Sales\AllocationRepository;
use App\Route;
use App\SalesLocation;
use App\Stock;
use App\Store;
use App\Staff;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator as ExtentValidator;

class AllocationRequest extends FormRequest
{
    protected $message = [
        'product.check_required' => 'At least you are required to choose one product from the list below',
        'store_id.required' => 'The store field is required.',
        'sales_location_id.required' => 'The sales location field is required.',
        'customer.required' => 'At least you are required to choose one customer from the list below',
        'duplicate.check_duplicate' => 'There is an allocation already exists for this period, please choose different date range.'
    ];
    protected $count = 0;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        ExtentValidator::extend('check_required', function ($attribute, $value, $parameters, $validator) {
            $name = array_get($parameters, 0);
            if (!$name) return false;
            $products = array_get($this->get($name), 'id') ?? [];
            return count($products);
        });

        ExtentValidator::extend('check_duplicate', function ($attribute, $value, $parameters, $validator) {
            $fromDate = $this->get('from_date');
            $toDate = $this->get('to_date');
            $salesLocation = $this->get('sales_location');
            $salesLocationID = $this->get('sales_location_id');
            $repID = $this->get('rep_id');
            $oldAllocation = $this->route('allocation') ?? '';
            $allocations = DailySale::whereNotIn('status', ['Canceled'])->where(function ($q) use ($fromDate, $toDate, $salesLocation, $salesLocationID) {
                $q->where('from_date', '>=', $fromDate)
                    ->where('to_date', '<=', $toDate)
                    ->where('sales_location', $salesLocation)
                    ->where('sales_location_id', $salesLocationID);
            });

            if ($oldAllocation) {
                $allocations->whereNotIn('id', [$oldAllocation->id]);
            }
            if ($repID) {
                $allocations->where('rep_id', $repID);
            }
            $allocations = $allocations->count();
            return !$allocations;
        });

        ExtentValidator::extend('check_product_store', function ($attribute, $value, $parameters, $validator) {
            $attr = explode('.', $attribute);
            $id = array_get($attr, 2);
            return $this->getValidationData('store', $id);
        });

        ExtentValidator::extend('check_product_quantity', function ($attribute, $value, $parameters, $validator) {
            $attr = explode('.', $attribute);
            $id = array_get($attr, 2);
            $allocation = new AllocationRepository();
            $data = $allocation->getOldAllocationData($this, false);
            if (array_key_exists($id, $data)) return true;
            return $this->getValidationData('quantity', $id);
        });

        ExtentValidator::extend('check_product_quantity_available', function ($attribute, $value, $parameters, $validator) {
            $attr = explode('.', $attribute);
            $id = array_get($attr, 2);
            return $this->checkProductAvailable($id);
        });

        $products = array_get($this->get('product'), 'id') ?? [];
        foreach ($products as $key => $value) {
            $this->checkProductAvailable($key);
            $this->getValidationData('quantity', $key);
            $this->getValidationData('store', $key);
        }
        $rules = [
            'day_type' => 'required',
            'from_date' => 'required',
            'to_date' => 'required|after_or_equal:' . $this->input('from_date'),
            'sales_location' => 'required',
            'sales_location_id' => 'required',
            'notes' => 'required',
            'duplicate' => 'check_duplicate'
        ];
        if ($this->input('sales_location') == 'Shop') {
            $rules['product'] = 'check_required:product';
            $rules['store_id'] = 'required';
        }
        $rules['product.id.*'] = 'check_product_quantity_available';
        //$rules['product.quantity.*'] = 'check_product_quantity';

        if ($this->input('sales_location') == 'Van') {
            $rules['rep_id'] = 'required';
            $rules['route_id'] = 'required';
            $rules['customer'] = 'required';
            $rules['driver_id'] = 'required';
            //$rules['labour_id'] = 'required';
            $rules['allowance'] = 'required';
            $rules['odo_meter_reading'] = 'required';
        }
        return $rules;
    }


    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator, $this->getResponse($validator)))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

    /**
     * @param Validator $validator
     * @return RedirectResponse | JsonResponse
     */
    protected function getResponse(Validator $validator)
    {
        $errors = $validator->getMessageBag();
        $response = $this->except([]);
        if ($this->expectsJson()) {
            return new JsonResponse($errors, 422);
        }

        $labour_id = $this->get('labour_id');
        if ($labour_id) {
            $labourIds = explode(',', $labour_id);
            $response['labours'] = Staff::whereIn('id', $labourIds)->select(['short_name', 'id'])->get();
        }
        $driver_id = $this->get('driver_id');
        if ($driver_id) {
            $response['driver'] = Staff::where('id', $driver_id)->select(['short_name', 'id'])->first();
        }
        $products = $this->get('product');
        $ids = array_get($products, 'id');
        $stores = array_get($products, 'store');
        $location = $this->get('sales_location_id');
        $location = SalesLocation::find($location);
        if ($location) {
            $response['location_name'] = $location->name ?? '';
        }

        $rep = $this->get('rep_id');
        $rep = Rep::find($rep);
        if ($rep) {
            $response['rep_name'] = $rep->name ?? '';
        }

        $route = $this->get('route_id');
        $route = Route::find($route);
        if ($route) {
            $response['route_name'] = $route->name ?? '';
        }
        /** map store name to old value */
        if ($ids) {
            foreach ($ids as $key => $id) {
                $store = array_get($stores, $key);
                if (!$store) continue;
                if (!isset($response['store_name'])) {
                    $response['store_name'] = [];
                }
                $store = Store::find($store);
                $response['store_name'][$key] = $store ? $store->name : '';
                $response['store_id'][$key] = $store ? $store->id : '';
            }
        }
        $response['qty_count'] = ($this->count / 2);
        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($response)
            ->withErrors($errors, $this->errorBag);
    }

    protected function getValidationData($getName, $id)
    {
        $allocation = new AllocationRepository();
        $data = $allocation->getOldAllocationData($this, false);
        $product = $this->get('product');
        $ids = array_get($product, 'id') ?? [];
        $value = array_get($product, $getName);
        if (array_key_exists($id, $data)) return true;
        if ($id && array_key_exists($id, $ids)) {
            $data = array_get($value, $id);
            $this->message['product.' . $getName . '.' . $id . '.check_product_' . $getName] = 'The ' . $getName . ' field is required';
            if (!$data) {
                if ($getName === 'quantity') {
                    $this->count++;
                }
                return false;
            }
        }
        return true;
    }

    protected function checkProductAvailable($id)
    {
        $product = $this->get('product');
        $store = $this->get('store_id');
        $quantities = array_get($product, 'quantity');
        $quantity = array_get($quantities, $id);
        if (!$quantity) return true;
        $stock = Stock::where('store_id', $store)->where('product_id', $id)->first();
        $this->message['product.id.' . $id . '.check_product_quantity_available'] = 'No stock assigned for this product in this store, please contact store manager.';
        if (!$stock) return false;
        $test = $stock->available_stock;
        $this->message['product.id.' . $id . '.check_product_quantity_available'] = 'The quantity can\'t be more than the available stock ( ' . $test . ' ) in the store.';
        if ($quantity > $stock->available_stock) return false;
        return true;
    }

    /**
     * @return array
     */
    public function messages()
    {
        $messages = $this->message;
        return $messages;
    }
}
