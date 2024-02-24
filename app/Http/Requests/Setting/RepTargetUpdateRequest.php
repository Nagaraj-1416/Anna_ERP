<?php

namespace App\Http\Requests\Setting;

use App\Rep;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class RepTargetUpdateRequest extends FormRequest
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
        /**
         * Validator for start and end Date range
         */
        Validator::extend('checkRange', function ($attribute, $value, $parameters, $validator) {

            $rep = $this->route('rep');
            $target = $this->route('target');
            $oldTargets = $rep->targets()->where('is_active', 'Yes')->whereNotIn('id', [$target->id]);
            $oldStartDates = $oldTargets->pluck('start_date')->toArray();
            $oldEndDates = $oldTargets->pluck('end_date')->toArray();
            $requestStartDates = $this->input('start_date');
            $requestEndDates = $this->input('end_date');
            $validation = $this->checkValidation($oldStartDates, $oldEndDates, $requestStartDates, $requestEndDates);
            if (!$validation) return false;
            return true;
        });

        $rules = [
            'type' => 'required',
            'start_date' => 'required|checkRange',
            'end_date' => 'required|after:'.$this->input('start_date'),
            'target' => 'required',
        ];
        return $rules;
    }

    /**
     * @param $start_date
     * @param $end_date
     * @param $date_from_user
     * @return bool
     */
    public function checkInRange($start_date, $end_date, $date_from_user)
    {
        // Convert to timestamp
        $start_ts = strtotime($start_date);
        $end_ts = strtotime($end_date);
        $user_ts = strtotime($date_from_user);
        // Check that user date is between start & end
        return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
    }

    /**
     * @param $startDates
     * @param $endDates
     * @param $requestStartDates
     * @param $requestEndDates
     * @return bool
     */
    public function checkValidation($startDates, $endDates, $requestStartDates, $requestEndDates)
    {
        foreach ($startDates as $index => $data) {
            $oldStartDate = array_get($startDates, $index);
            $oldEndDate = array_get($endDates, $index);
            $start = $this->checkInRange($oldStartDate, $oldEndDate, $requestStartDates);
            $end = $this->checkInRange($oldStartDate, $oldEndDate, $requestEndDates);
            if ($start || $end) return false;
        }
        return true;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'start_date.check_range' => 'A target is already defined for this chosen period, please check the available targets and pick another period to continue.'
        ];
    }
}
