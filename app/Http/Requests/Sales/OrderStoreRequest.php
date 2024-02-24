<?php

namespace App\Http\Requests\Sales;

use App\BusinessType;
use App\PriceBook;
use App\Product;
use App\Rep;
use App\SalesLocation;
use App\Stock;
use App\Store;
use App\Customer;
use App\UnitType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator as ExtentValidator;

class OrderStoreRequest extends FormRequest
{
    protected $errorMessages = [
        'customer_id.required' => 'The customer field is required.',
        'business_type_id.required' => 'The business type field is required.',
    ];

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
        ExtentValidator::extend('check_product_quantity_available', function ($attribute, $value, $parameters, $validator) {
            $attr = explode('.', $attribute);
            $id = array_get($attr, 1);
            return $this->checkProductAvailable($id);
        });
        foreach ($this->get('quantity') as $key => $value) {
            $this->checkProductAvailable($key);
        }
        $date = carbon()->parse($this->request->get('order_date'))->subDay()->toDateString();
        $rules = [
            'sales_type' => 'required|in:"Retail","Wholesale","Distribution"',
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'delivery_date' => 'required|date|after:' . $date,
            'adjustment' => 'numeric',
            'discount_rate' => 'numeric',
            'discount_type' => 'in:Percentage,Amount',
            'product.*' => 'required|exists:products,id',
//            'store.*' => 'required|exists:stores,id',
            'qty.*' => 'required|numeric|check_product_quantity_available',
            'quantity.*' => 'check_product_quantity_available',
            'item_discount_rate.*' => 'required|numeric',
            'item_discount_type.*' => 'required|in:Percentage,Amount',
        ];

        if ($this->input('rep_id')) {
            $rules['rep_id'] = 'exists:reps,id';
        }

        if (showLocationDropdown() && $this->input('sales_locations')) {
            $rules['sales_location_id'] = 'exists:sales_locations,id';
        }

        if ($this->input('price_book_id')) {
            $rules['price_book_id'] = 'exists:price_books,id';
        }

        if ($this->input('order_type') == 'Schedule') {
            $rules['scheduled_date'] = 'required|date';
        }

        return $rules;
    }

    public function messages()
    {

        return $this->errorMessages;
    }

    /**
     * @param Validator $validator
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator, $this->getResponse($validator)))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

    /**
     * @param Validator $validator
     * @return \Illuminate\Http\RedirectResponse | JsonResponse
     */
    protected function getResponse(Validator $validator)
    {
        $errors = $validator->getMessageBag();
        $response = $this->except([]);
        if ($this->expectsJson()) {
            return new JsonResponse($errors, 422);
        }

        /** map business type name to old value */
        if ($this->get('business_type_id')) {
            $businessType = BusinessType::find($this->input('business_type_id'));
            $response['business_type_name'] = $businessType ? $businessType->name : '';
        }

        /** map customer name to old value */
        if ($this->get('customer_id')) {
            $customer = Customer::find($this->input('customer_id'));
            $response['customer_name'] = $customer ? $customer->display_name : '';
        }

        /** map rep name to old value */
        if ($this->get('rep_id')) {
            $rep = Rep::find($this->input('rep_id'));
            $response['rep_name'] = $rep ? $rep->name : '';
        }

        /** map rep name to old value */
        if ($this->get('price_book_id')) {
            $priceBook = PriceBook::find($this->input('price_book_id'));
            $response['price_book_name'] = $priceBook ? $priceBook->name : '';
        }

        /** map rep name to old value */
        if ($this->get('sales_location_id')) {
            $salesLocation = SalesLocation::find($this->input('sales_location_id'));
            $response['sales_location_name'] = $salesLocation ? $salesLocation->name : '';
        }

        /** map product name to old value */
        if ($this->get('product')) {
            $products = $this->get('product');
            foreach ($products as $key => $id) {
                if (!isset($response['product_name'])) {
                    $response['product_name'] = [];
                }
                $product = Product::find($id);
                $response['product_name'][$key] = $product ? $product->name : '';
            }
        }

        /** map store name to old value */
        if ($this->get('store')) {
            $stores = $this->get('store');
            foreach ($stores as $key => $id) {
                if (!isset($response['store_name'])) {
                    $response['store_name'] = [];
                }
                $store = Store::find($id);
                $response['store_name'][$key] = $store ? $store->name : '';
            }
        }

        if ($this->get('unit_type')) {
            $unitTypes = $this->get('unit_type');
            foreach ($unitTypes as $key => $id) {
                if (!isset($response['unit_type'])) {
                    $response['unit_type'] = [];
                }
                $unitType = UnitType::find($id);
                $response['unit_type_name'][$key] = $unitType ? $unitType->name : '';
            }
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($response)
            ->withErrors($errors, $this->errorBag);
    }


    protected function checkProductAvailable($index)
    {
        $product = array_get($this->get('product'), $index);

        $store = array_get($this->get('store'), $index);
        $quantity = array_get($this->get('quantity'), $index);
        $this->errorMessages['quantity.' . $index . '.check_product_quantity_available'] = 'The quantity field is required';
        if (!$quantity) return false;
        $stock = Stock::where('store_id', $store)->where('product_id', $product)->first();
        $this->errorMessages['quantity.' . $index . '.check_product_quantity_available'] = 'No stock assigned for this product in this store, please contact store manager.';
        if (!$stock) return false;
        $this->errorMessages['quantity.' . $index . '.check_product_quantity_available'] = 'The quantity can\'t be more than the available stock ( ' . $stock->available_stock . ' ) in the store.';
        if ($quantity > $stock->available_stock) return false;
        return true;
    }

}
