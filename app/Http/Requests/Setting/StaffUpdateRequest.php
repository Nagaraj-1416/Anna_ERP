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
class StaffUpdateRequest extends FormRequest
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
        ExtentValidator::extend('uniqueTest', function ($attribute, $value, $parameters, $validator) {
            $staff = $this->route('staff');
            $prefix = $this->get('prefix_first') . '/' . $this->get('prefix_last');
            if ($staff->user) {
                $users = User::whereNotIn('id', [$staff->user->id])->get();
                foreach ($users as $user) {
                    if ($prefix == $user->prefix) return false;
                }
            }
            return true;
        });
        // marge the data
        $this->merge(['full_name' => $this->input('first_name') . ' ' . $this->input('last_name')]);
        $this->merge(['name' => $this->input('short_name')]);
        $this->merge(['role_id' => $this->input('role')]);
        $this->merge(['prefix' => $this->input('prefix_first') . '/' . $this->input('prefix_last')]);
        // validation rules
        $rule = [
            'first_name' => 'required',
            'last_name' => 'required',
            'short_name' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'email' => 'required|email',
            'mobile' => 'required',
            'joined_date' => 'required',
            'street_one' => 'required',
            'street_two' => 'required',
            'city' => 'required',
            'province' => 'required',
            'postal_code' => 'required',
            'country_id' => 'required',
            'is_active' => 'required',
            'create_user' => 'required',
            'epf_no' => 'max:10',
            'etf_no' => 'max:10'
        ];
        // Check email exist
        $staff = $this->route('staff');
        if ($this->input('email') != $staff->email) {
            $rule['email'] = 'required|email|unique:staff';
        }
        if ($this->input('create_user') == 'Yes') {
            $rule['role'] = 'required|exists:roles,id';
            $rule['prefix_first'] = 'required|max:3|min:2';
            $rule['prefix_last'] = 'required|uniqueTest|max:3|min:2';
            if ($this->input('password')) {
                $rule['password'] = 'confirmed|min:6';
            }
        }
        return $rule;
    }

    public function messages()
    {
        return [
            'prefix_last.unique_test' => 'The prefix has already been taken, please try another and continue.'
        ];
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
