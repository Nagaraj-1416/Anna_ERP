<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
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
            'name' => 'required',
            'description' => 'required',
            'access_level' => 'required',
        ];

        // Check access level exist
        $role = $this->route('role');
        if ($this->input('access_level') != $role->access_level) {
            $rule['access_level'] = 'required|unique:roles';
        }

        return $rule;
    }
}
