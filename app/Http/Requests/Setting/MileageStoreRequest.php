<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class MileageStoreRequest extends FormRequest
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
        $rule = [
            'date' => 'required|date|unique:mileage_rates',
            'rate' => 'required|numeric',
        ];
        // On edit mode
        $mileageRate = $this->route('mileageRate');
        if ($this->method() == 'PATCH' && $mileageRate) {
            $rule['date'] = 'required|date|unique:mileage_rates,date,' . $mileageRate->id;
        }
        return $rule;
    }
}
