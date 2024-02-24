<?php

namespace App\Http\Requests\Sales;


use App\{
    Route, Location
};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CustomerStoreRequest extends FormRequest
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
            'salutation' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'display_name' => 'required',
            //'phone' => 'required',
            //'fax' => 'required',
            //'mobile' => 'required',
            //'email' => 'required',
            //'website' => 'required',
            'cl_amount' => 'required',
            'cl_notify_rate' => 'required',
            'route_id' => 'required',
            //'tamil_name' => 'required',
            'location_id' => 'required'
        ];

        $rules['street_one'] = 'required';
        $rules['city'] = 'required';
        $rules['province'] = 'required';
        $rules['postal_code'] = 'required';
        $rules['country_id'] = 'required';

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'phone.required' => 'The phone no field is required.',
            'fax.required' => 'The fax no field is required.',
            'mobile.required' => 'The mobile no field is required.',
            'email.required' => 'The email address field is required.',
            'country_id.required' => 'The country field is required.',
            'cl_amount.required' => 'The CL amount field is required.',
            'cl_notify_rate.required' => 'The CL notify rate field is required.',
            'route_id.required' => 'The route field is required.',
            'location_id.required' => 'The route location field is required.',
        ];
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
     * @return \Illuminate\Http\RedirectResponse | JsonResponse
     */
    protected function getResponse(Validator $validator)
    {
        $errors = $validator->getMessageBag();
        $response = $this->except([]);
        if ($this->expectsJson()) {
            return new JsonResponse($errors, 422);
        }
        // Map the route name to old value
        if ($this->get('route_id')) {
            $route = Route::find($this->get('route_id'));
            $response['route_name']  = $route ? $route->name : '';
        }

        // Map the location name to old value
        if ($this->get('location_id')) {
            $location = Location::find($this->get('location_id'));
            $response['location_name']  = $location ? $location->name : '';
            $response['location_name']  .= ' ' . $location ? '(' . $location->code . ')' : '';
        }

        // return response
        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($response)
            ->withErrors($errors, $this->errorBag);
    }
}
