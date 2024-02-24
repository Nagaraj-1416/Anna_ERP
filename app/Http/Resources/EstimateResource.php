<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed estimate_no
 * @property mixed estimate_date
 * @property mixed expiry_date
 * @property mixed terms
 * @property mixed notes
 * @property mixed sub_total
 * @property mixed discount
 * @property mixed discount_rate
 * @property mixed discount_type
 * @property mixed adjustment
 * @property mixed total
 * @property mixed status
 * @property mixed order_status
 * @property mixed rep_id
 * @property mixed prepared_by
 * @property mixed customer_id
 * @property mixed business_type_id
 * @property mixed company_id
 * @property mixed created_at
 * @property mixed updated_at
 */
class EstimateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array(
            'id' => $this->id,
            'estimate_no' => $this->estimate_no,
            'estimate_date' => $this->estimate_date,
            'expiry_date' => $this->expiry_date,
            'terms' => $this->terms,
            'notes' => $this->notes,
            'sub_total' => $this->sub_total,
            'discount' => $this->discount,
            'discount_rate' => $this->discount_rate,
            'discount_type' => $this->discount_type,
            'adjustment' => $this->adjustment,
            'total' => $this->total,
            'status' => $this->status,
            'order_status' => $this->order_status,
            'rep_id' => $this->rep_id,
            'prepared_by' => $this->prepared_by,
            'customer_id' => $this->customer_id,
            'business_type_id' => $this->business_type_id,
            'company_id' => $this->company_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'rep' =>   new RepResource($this->whenLoaded('rep')),
            'preparedBy' =>   new UserResource($this->whenLoaded('preparedBy')),
            'customer' =>   new CustomerResource($this->whenLoaded('customer')),
            'businessType' =>   new BusinessTypeResource($this->whenLoaded('businessType')),
            'company' =>   new CompanyResource($this->whenLoaded('company')),
            'product_items' =>   ProductItemResource::collection($this->whenLoaded('products')),
            'products' =>   ProductResource::collection($this->whenLoaded('products')),
            'comments' =>   CommentResource::collection($this->whenLoaded('comments')),
        );
    }
}
