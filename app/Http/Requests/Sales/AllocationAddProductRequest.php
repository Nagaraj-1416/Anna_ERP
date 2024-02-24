<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AllocationAddProductRequest
 * @package App\Http\Requests\Sales
 */
class AllocationAddProductRequest extends FormRequest
{
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
        $rules = [];

        if(count($this->request->get('allocates')['stock_id'])){
            foreach ($this->request->get('allocates')['stock_id'] as $key => $value) {
                $rules['allocates.issue_qty.'.$key] = 'required|lte:'.(int)array_get($this->request->get('allocates')['available_qty'], $key);
            }
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        $messages = [];

        if(count($this->request->get('allocates')['issue_qty'])){
            foreach ($this->request->get('allocates')['issue_qty'] as $key => $value) {
                $messages['allocates.issue_qty.'.$key.'.required'] = 'Issued qty is required.';
                $messages['allocates.issue_qty.'.$key.'.lte'] = 'Issued qty can not be more than available in store.';
            }
        }
        return $messages;
    }

}
