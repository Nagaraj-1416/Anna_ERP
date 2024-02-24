<?php

namespace App\Http\Requests\Purchase;

use App\Bill;
use App\SupplierCredit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class SupplierCreditBillRequest extends FormRequest
{
    public $messageData = [
        'payment.*.required' => 'The payment field is required.',
        'payment_date.*.required' => 'The payment date field is required.',
        'payment_type.*.required' => 'The payment type field is required.',
        'account.*.required' => 'The paid through field is required.'
    ];

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
         * For Check Each Amount lesser than Bill Amount
         */
        Validator::extend('checkAmount', function ($attribute, $value, $parameters, $validator) {
            $text = explode('.', $attribute);
            $attributeKey = array_get($text, 1);
            $bills = $this->input('bill_id');
            if (!$value) return false;
            $billId = array_get($bills, $attributeKey);
            $bill = Bill::find($billId);
            if ($bill) {
                $paid = $bill->payments->sum('payment');
                $balance = $bill->amount - $paid;
                if ($balance < $value) {
                    $name = $attribute . '.check_amount';

                    $this->messageData[$name] = 'The given payment may not be greater than 140';
                    return false;
                }
            }
            return true;
        });
        /**
         * to check Applied Totals Amount lesser than credit amount
         */
        Validator::extend('checkTotalAmount', function ($attribute, $value, $parameters, $validator) {
            $credit = $this->route('credit');
            $payments = $this->input('payment');
            $requestAmount = array_sum($payments);
            $paid = getSupplierCreditUsed($credit);
            $totalValue = $credit->amount - $paid;
            if ($totalValue < $requestAmount) {
                $this->messageData['payment.*.check_total_amount'] = 'Applied total bill amount: ' . $requestAmount . ', The credit remaining is not enough to proceed this credit use request.';
                return false;
            }
            return true;
        });
        $this->updateMessage();
        /**
         *To Check Applied Bill payment Date lesser than Bill Date
         */
        Validator::extend('checkDate', function ($attribute, $value, $parameters, $validator) {
            $text = explode('.', $attribute);
            $attributeKey = array_get($text, 1);
            $bills = $this->input('bill_id');
            if (!$value) return false;
            $billId = array_get($bills, $attributeKey);
            $bill = Bill::find($billId);
            if ($bill) {
                if ($bill->bill_date > $value) {
                    return false;
                }
            }
            return true;
        });

        return [
            'payment' => 'array|checkTotalAmount',
            'payment.*' => 'required|checkAmount',
            'payment_date.*' => 'required|checkDate',
            'payment_type.*' => 'required',
            'bill_id.*' => 'required',
            'account.*' => 'required'
        ];
    }

    public function updateMessage()
    {
        $credit = $this->route('credit');
        $payments = $this->input('payment');
        $paymentDate = $this->input('payment_date');
        $requestAmount = array_sum($payments);
        $paid = getSupplierCreditUsed($credit);
        $totalValue = $credit->amount - $paid;
        if ($totalValue < $requestAmount) {
            $this->messageData['payment.check_total_amount'] = 'Applied total bill amount: ' . $requestAmount . ', The credit remaining is not enough to proceed this credit use request.';
        }

        //Mano
        $bills = $this->input('bill_id');
        foreach ($bills as $key => $bill) {
            $billId = array_get($bills, $key);
            $bill = Bill::find($billId);
            if ($bill) {
                $paid = $bill->payments->sum('payment');
                $balance = $bill->amount - $paid;
                if ($balance < array_get($payments, $key)) {
                    $name = 'payment.' . $key . '.check_amount';
                    $this->messageData[$name] = 'The given payment may not be greater than ' . number_format($balance, 2);
                }

                if ($bill->bill_date > array_get($paymentDate, $key)) {
                    $name = 'payment_date.' . $key . '.check_date';
                    $this->messageData[$name] = 'The payment date must be a date after or equal to bill date ' . $bill->bill_date;
                }
            }
        }
    }

    public function messages()
    {
        return $this->messageData;
    }
}
