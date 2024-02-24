<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class EmailTemplateUpdateRequest extends FormRequest
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
            'links' => 'array',
            'variables' => 'array',
            'loops' => 'array',
        ];

        if (!$this->ajax()) {
            $rules['content'] = 'required';
            $rules['subject'] = 'required';
        }
        return $rules;
    }
}
