<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;

class RestoreStockRequest extends FormRequest
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
        $availableStock = (float)$this->request->get('actual_av_stock');
        $shortageQty = (float)$this->request->get('shortage_qty');
        $excessQty = (float)$this->request->get('excess_qty');

        if($shortageQty > 0){
            $availableStock = ($availableStock - $shortageQty);
        }

        if($excessQty > 0){
            $availableStock = ($availableStock + $excessQty);
        }

        $rules = [
            'restored_qty' => 'required|lte:'.$availableStock,
            'shortage_qty' => 'required',
            'excess_qty' => 'required'
        ];

        return $rules;
    }

    public function messages()
    {
        $availableStock = (float)$this->request->get('actual_av_stock');
        $shortageQty = (float)$this->request->get('shortage_qty');
        $excessQty = (float)$this->request->get('excess_qty');

        if($shortageQty > 0){
            $availableStock = ($availableStock - $shortageQty);
        }

        if($excessQty > 0){
            $availableStock = ($availableStock + $excessQty);
        }

        $messages = [
            'restored_qty.lte' => 'Restoring qty can not be more than actual available qty ('.$availableStock.')'
        ];
        return $messages;
    }

}
