<?php

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class StockReturnStoreRequest extends FormRequest
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
        $rules = [
            'return_to' => 'required'
        ];

        if(count($this->request->get('returns')['qty'])){
            foreach ($this->request->get('returns')['qty'] as $key => $value) {
                $rules['returns.qty.'.$key] = 'lte:'.(int)array_get($this->request->get('returns')['available_qty'], $key);
            }
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'return_to.required' => 'The return to field is required.'
        ];

        if(count($this->request->get('returns')['qty'])){
            foreach ($this->request->get('returns')['qty'] as $key => $value) {
                $messages['returns.qty.'.$key.'.lte'] = 'Issued qty can not be more than available in store.';
            }
        }
        return $messages;
    }

}
