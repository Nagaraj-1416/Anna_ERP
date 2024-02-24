<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed payment
 * @property mixed uuid
 * @property mixed payment_date
 * @property mixed payment_type
 * @property mixed payment_mode
 * @property mixed payment_from
 * @property mixed cheque_no
 * @property mixed cheque_date
 * @property mixed account_no
 * @property mixed deposited_date
 * @property mixed bank_id
 * @property mixed status
 * @property mixed notes
 * @property mixed prepared_by
 * @property mixed invoice_id
 * @property mixed sales_order_id
 * @property mixed customer_id
 * @property mixed business_type_id
 * @property mixed company_id
 * @property mixed deposited_to
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed card_holder_name
 * @property mixed card_no
 * @property mixed expiry_date
 * @property mixed cheque_type
 */
class InvoicePaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'payment' => (double) $this->payment,
            'payment_date' => $this->payment_date,
            'uuid' => $this->uuid,
            'payment_type' => $this->payment_type,
            'payment_mode' => $this->payment_mode,
            'payment_from' => $this->payment_from,
            'cheque_no' => $this->cheque_no,
            'cheque_date' => $this->cheque_date,
            'cheque_type' => $this->cheque_type,
            'account_no' => $this->account_no,
            'deposited_date' => $this->deposited_date,
            'card_holder_name' => $this->card_holder_name,
            'card_no' => $this->card_no,
            'expiry_date' => $this->expiry_date,
            'bank_id' => $this->bank_id,
            'status' => $this->status,
            'notes' => $this->notes,
            'prepared_by' => $this->prepared_by,
            'invoice_id' => $this->invoice_id,
            'sales_order_id' => $this->sales_order_id,
            'customer_id' => $this->customer_id,
            'business_type_id' => $this->business_type_id,
            'company_id' => $this->company_id,
            'deposited_to' => $this->deposited_to,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'bank' =>   new BankResource($this->whenLoaded('bank')),
            'preparedBy' =>   new UserResource($this->whenLoaded('preparedBy')),
            'invoice' =>   new InvoiceResource($this->whenLoaded('invoice')),
            'order' =>   new SalesOrderResource($this->whenLoaded('order')),
            'customer' =>   new CustomerResource($this->whenLoaded('customer')),
            'businessType' =>   new BusinessTypeResource($this->whenLoaded('businessType')),
            'company' =>   new CompanyResource($this->whenLoaded('company')),
            'depositedTo' =>   new AccountResource($this->whenLoaded('depositedTo')),
        ];
    }
}
