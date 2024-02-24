<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class VehicleRepRequest extends FormRequest
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
        if ($this->route('method') == 'Revoke') {
            $time = carbon()->now()->toDateTimeString();
            $rules['date'] = 'required|after:' . $time;
            return $rules;
        } else if ($this->route('method') == 'Block') {
            $time = carbon()->now()->toDateTimeString();
            $rules['block_date'] = 'required|after:' . $time;
            return $rules;
        } else {
            $rules = [
                'date' => 'required'
            ];
        }
        return $rules;
    }
}
