<?php

namespace App\Http\Requests\Setting;

use App\Designation;
use App\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator as ExtentValidator;
class StaffStoreRequest extends FormRequest
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
        // marge the data
        $this->merge(['full_name' => $this->input('first_name') . ' ' . $this->input('last_name')]);
        $this->merge(['name' => $this->input('short_name')]);
        $this->merge(['role_id' => $this->input('role')]);

        ExtentValidator::extend('uniqueTest', function ($attribute, $value, $parameters, $validator) {
            $prefix = $this->get('prefix_first') . '/' . $this->get('prefix_last');
            $users = User::all();
            foreach ($users as $user) {
                if ($prefix == $user->prefix) return false;
            }
            return true;
        });
        // validation rules
        $rule = [
            'first_name' => 'required',
            'last_name' => 'required',
            'short_name' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'email' => 'required|email|unique:staff',
            'mobile' => 'required',
            'joined_date' => 'required',
            'street_one' => 'required',
            'street_two' => 'required',
            'city' => 'required',
            'province' => 'required',
            'postal_code' => 'required|max:10',
            'country_id' => 'required',
            'is_active' => 'required',
            'create_user' => 'required',
            'is_sales_rep' => 'required',
            'epf_no' => 'max:10',
            'etf_no' => 'max:10'
        ];
        if ($this->input('create_user') == 'Yes') {
            $rule['role'] = 'required|exists:roles,id';
            $rule['password'] = 'required|confirmed|min:6';
            $rule['business_type'] = 'required';
            $rule['prefix_first'] = 'required|max:3|min:2';
            $rule['prefix_last'] = 'required|uniqueTest|max:3|min:2';
        }
        if ($this->input('is_sales_rep') == 'Yes') {
            $rule['vehicle_id'] = 'required';
            $rule['route_id'] = 'required';
            $rule['cl_amount'] = 'required';
            $rule['cl_notify_rate'] = 'required';
        }
        return $rule;
    }

    public function messages()
    {
        $messages = [
            'create_user.required' => 'Is this staff is a System User? If so, check "Yes" to assign a user account.',
            'is_sales_rep.required' => 'Is this staff is a Sale Rep? If so, check "Yes" to associate a vehicle.',
            'prefix_last.unique_test' => 'The prefix has already been taken, please try another and continue.'
        ];

        if ($this->input('is_sales_rep') == 'Yes') {
            $messages['vehicle_id.required'] = 'The vehicle field is required.';
            $messages['route_id.required'] = 'The route field is required.';
            $messages['cl_amount.required'] = 'The CL amount field is required.';
            $messages['cl_notify_rate.required'] = 'The CL notify rate field is required.';
        }
        return $messages;
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

        $designation_id = $this->get('designation_id');
        if ($designation_id) {
            $response['designation'] = Designation::where('id', $designation_id)->select(['name', 'id'])->first();
        }
        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($response)
            ->withErrors($errors, $this->errorBag);
    }
}
