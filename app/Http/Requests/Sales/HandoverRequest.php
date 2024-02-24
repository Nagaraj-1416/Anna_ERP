<?php

namespace App\Http\Requests\Sales;

use App\DailySaleItem;
use App\Route;
use App\Store;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator as ExtentValidator;

class HandoverRequest extends FormRequest
{
    public $types = [];
    public $errorMessages = [
        'cashCollection.*.check_required' => 'Cash Details are required.',
        'cashCollection.*.check_dup' => 'Please avoid duplicated rupee type.',
        'cheques.required' => 'At least you are required to choose one cheque from the list below.',
        'products.id.required' => 'At least you are required to choose one product from the list below.',
        'total_cash_breakdown.check_total_cash' => 'Cash breakdown total should be equal to total cash collection.',
        'cheques.cheque_no.*.required' => 'The cheque no field is required.',
        'cheques.cheque_date.*.required' => 'The cheque date field is required.',
        'cheques.cheque_bank.*.required' => 'The bank field is required.',
        'cheques.payment.*.required' => 'The payment field is required.',
        'cheques.cheque_no.*.max' => 'The cheque no may not be greater than 6 characters.',
        'cheques.cheque_no.*.min' => 'The cheque no must be at least 6 characters.',
        'cheques.payment.*.numeric' => 'The payment must be a number',
        'expenses.amount.*.numeric' => 'The expense amount must be a number',
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
        ExtentValidator::extend('check_required', function ($attribute, $value, $parameters, $validator) {
            $attr = explode('.', $attribute);
            $id = array_get($attr, 1);
            $data = $this->get(array_get($attr, 0));
            if ($data) {
                $array = array_get($data, $id);
                if ($array) {
                    if (!array_get($array, 'type')) return false;
                    if (!array_get($array, 'count')) return false;
                } else {
                    return false;
                }
            } else {
                return false;
            }
            return true;
        });

        ExtentValidator::extend('check_dup', function ($attribute, $value, $parameters, $validator) {
            $attr = explode('.', $attribute);
            $id = array_get($attr, 1);
            $data = $this->get(array_get($attr, 0)) ?? [];
            $array = array_get($data, $id) ?? [];
            $type = array_get($array, 'type') ?? 0;
            $find = array_search($type, $this->types);
            array_push($this->types, $type);
            if ($find === 0) return false;
            if (!$find) {
                return true;
            }
            return false;
        });
        ExtentValidator::extend('check_total_cash', function ($attribute, $value, $parameters, $validator) {
            $cashCollection = $this->get('cashCollection');
            $totalAmount = array_sum(array_pluck($cashCollection, 'total')) + ($this->get('shortage') ?? 0);
            $handover = $this->route('handover');
            $totalSales = $handover->cash_sales + $handover->old_cash_sales;
            if ($totalAmount != $totalSales) return false;
            return true;
        });

        ExtentValidator::extend('check_restore_qty', function ($attribute, $value, $parameters, $validator) {
            $attr = explode('.', $attribute);
            $productId = array_get($attr, 2);
            return $this->checkBalanceProductQty($productId, $value, 'restore', 'restore_qty', 'check_restore_qty');
        });

        ExtentValidator::extend('check_shortage_qty', function ($attribute, $value, $parameters, $validator) {
            $attr = explode('.', $attribute);
            $productId = array_get($attr, 2);
            return $this->checkBalanceProductQty($productId, $value, 'shortage', 'shortage_qty', 'check_shortage_qty');
        });
        ExtentValidator::extend('check_damaged_qty', function ($attribute, $value, $parameters, $validator) {
            $attr = explode('.', $attribute);
            $productId = array_get($attr, 2);
            return $this->checkBalanceProductQty($productId, $value, 'damaged', 'damaged_qty', 'check_damaged_qty');
        });

        $rules = [];
        if (isCashierLevelStaff() || isDirectorLevelStaff() || isAccountLevelStaff()) {
            $rules = [
                'cashCollection.*' => 'check_required|check_dup',
//                'total_cash_breakdown' => 'check_total_cash',
                'cheques.cheque_no.*' => 'required|min:6|max:6',
                'cheques.cheque_date.*' => 'required',
                'cheques.cheque_bank.*' => 'required',
                'cheques.payment.*' => 'required|numeric',
                'expenses.amount.*' => 'numeric',
                'route_id' => 'required',
                'store_id' => 'required'
            ];
        }
        if (isStoreLevelStaff() || isDirectorLevelStaff() || isAccountLevelStaff()) {
            $products = $this->get('products');
            if ($products && array_get($products, 'restore_qty')) {
                foreach (array_get($products, 'restore_qty') as $productId => $value) {
                    $this->checkBalanceProductQty($productId, $value, 'restore', 'restore_qty', 'check_restore_qty');
                }

                foreach (array_get($products, 'shortage_qty') as $productId => $value) {
                    $this->checkBalanceProductQty($productId, $value, 'shortage', 'shortage_qty', 'check_shortage_qty');
                }
                foreach (array_get($products, 'damaged_qty') as $productId => $value) {
                    $this->checkBalanceProductQty($productId, $value, 'damaged', 'damaged_qty', 'check_damaged_qty');
                }
                $rules['products.restore_qty.*'] = 'required|check_restore_qty';
                $rules['products.shortage_qty.*'] = 'required|check_shortage_qty';
                $rules['products.damaged_qty.*'] = 'required';
            }
        }
        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return $this->errorMessages;
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

    protected function getResponse(Validator $validator)
    {
        $errors = $validator->getMessageBag();
        $response = $this->except([]);
        if ($this->expectsJson()) {
            return new JsonResponse($errors, 422);
        }

        $route = $this->get('route_id');
        $store = $this->get('store_id');
        $route = Route::find($route);
        if ($route) {
            $response['route_name'] = $route->name ?? '';
        }

        $store = Store::find($store);
        $response['store_name'] = $store ? $store->name : '';

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($response)
            ->withErrors($errors, $this->errorBag);
    }

    /**
     * @param $id
     * @param $value
     * @param $name
     * @param $field
     * @param $validation
     * @return bool
     */
    public function checkBalanceProductQty($id, $value, $name, $field, $validation)
    {
        $product = DailySaleItem::find($id);
        $shortage_qty = $this->input('products.shortage_qty');
        $damaged_qty = $this->input('products.damaged_qty');
        $returned_qty = $this->input('products.returned_qty');
        if (!$product) return false;
        $total = $product->quantity + $product->cf_qty + array_get($returned_qty, $product->id, 0) + + $product->excess_qty;
        $deuct = $product->sold_qty + $product->restored_qty + $product->replaced_qty + array_get($shortage_qty, $product->id, 0);
        if ($field == 'shortage_qty') {
            $deuct = $deuct - array_get($shortage_qty, $product->id, 0);
        }

        /*if ($field == 'damaged_qty') {
            $deuct = $deuct - array_get($damaged_qty, $product->id, 0);
        }*/
        $available = $total - $deuct;
        if ($available < $value && $value != 0) {
            $this->errorMessages["products.{$field}.{$id}.{$validation}"] = "The {$name} quantity can't be more than the available quantity ( {$available} ).";
            return false;
        }
        return true;
    }
}
