<?php

namespace App\Http\Requests\Setting;

use App\AccountGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;


class AccountGroupStoreRequest extends FormRequest
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
            'name' => 'required',
            'is_active' => 'required|in:Yes,No',
            'category_id' => 'required|exists:account_categories,id',
        ];
        if ($this->input('parent_id')) {
            $rule['parent_id'] = 'required|exists:account_groups,id';
        }
        return $rules;
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
     * @return \Illuminate\Http\RedirectResponse | JsonResponse
     */
    protected function getResponse(Validator $validator)
    {
        $errors = $validator->getMessageBag();
        $response = $this->except([]);
        if ($this->expectsJson()) {
            return new JsonResponse($errors, 422);
        }

        /** map store name to old value */
        if ($this->get('parent_id')) {
            $group = AccountGroup::find($this->get('parent_id'));
            $response['parent_name'] = $group ? $group->name : '';
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($response)
            ->withErrors($errors, $this->errorBag);
    }
}