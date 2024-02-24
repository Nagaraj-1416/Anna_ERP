<?php

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class AddDamageRequest extends FormRequest
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
            'damaged_qty' => 'required|numeric|max:'.(int)$this->request->get('available_stock')
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = [];
        return $messages;
    }

}
