<?php

namespace App\Http\Requests\Api\Sales;

use App\Repositories\Sales\HandOverRepository;
use Illuminate\Foundation\Http\FormRequest;

class HandOverStoreRequest extends FormRequest
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
        $handOver = new HandOverRepository();
        $rules =  [
            'allowance' => 'required|numeric',
            'odometer_end_reading' => 'required|numeric',
            'sold_qty' => 'array',
            'sold_qty.*' => 'numeric',
        ];
        /*if ($this->input('expenses')){
            $rules['expenses'] = 'required|array';
        }*/
        /*if ($this->input('expenses') && is_array($this->input('expenses'))){
            foreach ($this->input('expenses') as $key => $expense){
                if (isset($expense['type_id']) && $expense['type_id'] == mileageTypeId()) {
                    $rules['expenses.' . $key . '.calculate_mileage_using'] = 'required|in:"Distance","Odometer"';
                    $calculateMileageType = isset($expense['calculate_mileage_using']) ? $expense['calculate_mileage_using'] : null;
                    if ( $calculateMileageType == 'Distance' || $calculateMileageType == null) {
                        $rules['expenses.' . $key . '.distance'] = 'required|numeric|min:0.1';
                    }
                    if ($calculateMileageType == 'Odometer') {
                        $rules['expenses.' . $key . '.start_reading'] = 'required|numeric';
                        $rules['expenses.' . $key . '.end_reading'] = 'required|numeric';
                    }
                }elseif(isset($expense['type_id']) && $expense['type_id'] == fuelTypeId()) {
                    $rules['expenses.' . $key . '.amount'] = 'required|numeric';
                    $rules['expenses.' . $key . '.liter'] = 'required|numeric';
                    $rules['expenses.' . $key . '.odometer'] = 'required|numeric';
                    $rules['expenses.' . $key . '.gps_lat'] = 'required';
                    $rules['expenses.' . $key . '.gps_long'] = 'required';
                }else{
                    $rules['expenses.' . $key . '.amount'] = 'required|numeric';
                }
            }
        };*/

        $todayNotVisitedCustomers = $handOver->todayNotVisitedCustomers();
        if (is_array($todayNotVisitedCustomers) && count($todayNotVisitedCustomers)){
            $rules['not_visit_customer_notes'] = 'required|array';
            foreach ($todayNotVisitedCustomers as $customerId){
                $rules['not_visit_customer_notes.'.$customerId] = 'required|array';
                $rules['not_visit_customer_notes.'.$customerId.'.reason'] = 'required';
                $rules['not_visit_customer_notes.'.$customerId.'.gps_lat'] = 'required';
                $rules['not_visit_customer_notes.'.$customerId.'.gps_long'] = 'required';
                $rules['not_visit_customer_notes.'.$customerId.'.is_visited'] = 'in:"Yes","No"';
            }
        }
        return $rules;
    }
}
