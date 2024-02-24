<?php

namespace App\Http\Requests\Setting;

use App\Route;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class RouteTargetRequest extends FormRequest
{
    protected $key = [];
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
        Validator::extend('checkRange', function ($attribute, $value, $parameters, $validator) {
            $text = explode('.', $attribute);
            $attributeKey = array_get($text, 1);
            $newDatas = $this->all();
            $route = $this->route('route');
            $oldTargets = $route->targets()->where('is_active', 'Yes');
            $oldStartDates = $oldTargets->pluck('start_date')->toArray();
            $oldEndDates = $oldTargets->pluck('end_date')->toArray();
            $requestStartDates = array_get($newDatas, 'start_date');
            $requestEndDates = array_get($newDatas, 'end_date');
            $allStartDates = array_merge($oldStartDates, $requestStartDates);
            $allEndDates = array_merge($oldEndDates, $requestEndDates);
            $validation = $this->checkValidation($allStartDates, $allEndDates, $requestStartDates, $requestEndDates, $value, $attributeKey);
            if (!$validation) return false;
            return true;
        });

        $rules = [
            'type.*' => 'required',
            'start_date.*' => 'required|checkRange',
            'end_date.*' => 'required',
            'target.*' => 'required',
        ];
        return $rules;
    }

    public function checkInRange($start_date, $end_date, $date_from_user)
    {
        // Convert to timestamp
        $start_ts = strtotime($start_date);
        $end_ts = strtotime($end_date);
        $user_ts = strtotime($date_from_user);
        // Check that user date is between start & end
        return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
    }

    public function checkValidation($startDates, $endDates, $requestStartDates, $requestEndDates, $value, $attributeKey)
    {
        foreach ($requestStartDates as $key => $item) {
            if (array_key_exists($key, $this->key) && $key != $attributeKey) continue;
            $this->key[$key] = $item;
            $forUnsetStart = $startDates;
            $forUnsetEnd = $endDates;
            $thisItem = array_search($value, $startDates);
            $startDate = array_get($requestStartDates, $key);
            $endDate = array_get($requestEndDates, $key);
            unset($requestStartDates[$key]);
            unset($requestEndDates[$key]);
            unset($forUnsetStart[$thisItem]);
            unset($forUnsetEnd[$thisItem]);
            foreach ($forUnsetStart as $index => $data) {
                if ($thisItem <= $index) continue;
                $oldStartDate = array_get($forUnsetStart, $index);
                $oldEndDate = array_get($forUnsetEnd, $index);
                $start = $this->checkInRange($oldStartDate, $oldEndDate, $startDate);
                $end = $this->checkInRange($oldStartDate, $oldEndDate, $endDate);
                if ($start || $end) return false;
            }
        }
        return true;
    }

    public function messages()
    {
        return [
            'start_date.*' => 'date_range'
        ];
    }
}
