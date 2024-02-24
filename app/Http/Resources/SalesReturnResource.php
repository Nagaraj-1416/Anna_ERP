<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed date
 * @property mixed notes
 * @property mixed status
 * @property mixed customer_id
 * @property mixed prepared_by
 * @property mixed company_id
 * @property mixed updated_at
 * @property mixed created_at
 * @property mixed payment
 */
class SalesReturnResource extends JsonResource
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
            'date' => $this->date,
            'notes' => $this->notes,
            'status' => $this->status,
            'credit_payment' => $this->payment ? new InvoicePaymentResource($this->payment) : null,
            'customer_id' => $this->customer_id,
            'prepared_by' => $this->prepared_by,
            'company_id' => $this->company_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
