<?php

namespace App\Http\Requests\Purchase;

use App\Bill;
use App\PurchaseOrder;
use Illuminate\Foundation\Http\FormRequest;

class BillStoreRequest extends FormRequest
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
            'bill_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:bill_date',
            'amount' => 'required|numeric'
        ];

        /**
         * Validate on create mode
         * @var PurchaseOrder $order
         */
        $order = $this->route('order');
        if($order){
            $rules['amount'] = 'required|numeric|max:' . $this->getPendingOrderAmount($order);
        }

        /**
         * Validate on Update mode
         * @var Bill $bill
         */
        $bill = $this->route('bill');
        if ($bill){
            $rules['amount'] = 'required|numeric|max:' . $this->getPendingOrderAmountForUpdate($bill);
        }

        /** Validate payment data */
        $order = $order ? $order : $bill->order;
        if ($order){
            $rules['bill_date'] = 'required|date|after_or_equal:' . $order->order_date;
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [];
        $amount = 0;
        /** @var PurchaseOrder $order */
        $order = $this->route('order');
        /** @var Bill  $bill */
        $bill = $this->route('bill');
        if ($order){
            $amount = $this->getPendingOrderAmount($order);
        }
        if ($bill){
            $amount = $this->getPendingOrderAmountForUpdate($bill);
        }
        $amountMsg = 'The bill amount can\'t be more than available order balance, you can create an bill for ' . number_format($amount, 2) . '.' ;
        if ($amount == 0){
            $amountMsg = 'This order fully billed, please refer associated bills for more information.';
        }
        $order = $order ? $order : $bill->order ?? '';
        if ($order){
            $messages = [
                'bill_date.after_or_equal' => 'The bill date must be a date after or equal to order date ' . $order->order_date ?? '',
                'amount.max' => $amountMsg,
            ];
        }
        return $messages;
    }

    /**
     * @param PurchaseOrder $order
     * @return float
     */
    protected function getPendingOrderAmount(PurchaseOrder $order)
    {
        $totalAmount = $order->total;
        $billedAmount = $order->bills->sum('amount');
        return (float) ($totalAmount - $billedAmount);
    }

    /**
     * @param Bill $bill
     * @return float
     */
    public function getPendingOrderAmountForUpdate(Bill $bill)
    {
        $billAmount = $bill->amount;
        $pendingOrderAmount = $this->getPendingOrderAmount($bill->order);
        return (float) ($billAmount + $pendingOrderAmount);
    }
}
