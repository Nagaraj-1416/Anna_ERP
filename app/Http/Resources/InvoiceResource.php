<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed invoice_no
 * @property mixed invoice_date
 * @property mixed due_date
 * @property mixed invoice_type
 * @property mixed uuid
 * @property mixed amount
 * @property mixed prepared_by
 * @property mixed approval_status
 * @property mixed approved_by
 * @property mixed status
 * @property mixed notes
 * @property mixed sales_order_id
 * @property mixed customer_id
 * @property mixed business_type_id
 * @property mixed company_id
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed ref
 */
class InvoiceResource extends JsonResource
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
            'invoice_no' => $this->invoice_no,
            'uuid' => $this->uuid,
            'ref' => $this->ref,
            'invoice_date' => $this->invoice_date,
            'due_date' => $this->due_date,
            'invoice_type' => $this->invoice_type,
            'amount' => (double) $this->amount,
            'prepared_by' => $this->prepared_by,
            'approval_status' => $this->approval_status,
            'approved_by' => $this->approved_by,
            'status' => $this->status,
            'notes' => $this->notes,
            'sales_order_id' => $this->sales_order_id,
            'customer_id' => $this->customer_id,
            'business_type_id' => $this->business_type_id,
            'company_id' => $this->company_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'preparedBy' =>   new UserResource($this->whenLoaded('preparedBy')),
            'approvedBy' =>   new UserResource($this->whenLoaded('approvedBy')),
            'order' =>   new SalesOrderResource($this->whenLoaded('order')),
            'customer' =>   new CustomerResource($this->whenLoaded('customer')),
            'businessType' =>   new BusinessTypeResource($this->whenLoaded('businessType')),
            'company' =>   new CompanyResource($this->whenLoaded('company')),
            'payments' =>   InvoicePaymentResource::collection($this->whenLoaded('payments')),
            'comments' =>   CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
