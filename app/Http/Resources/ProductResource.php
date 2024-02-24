<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed code
 * @property mixed name
 * @property mixed type
 * @property mixed measurement
 * @property mixed min_stock_level
 * @property mixed notes
 * @property mixed is_active
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed buying_price
 * @property mixed expense_account
 * @property mixed wholesale_price
 * @property mixed retail_price
 * @property mixed distribution_price
 * @property mixed tamil_name
 */
class ProductResource extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
            'tamil_name' => $this->tamil_name,
            'type' => $this->type,
            'buying_price' => $this->buying_price,
            'expense_account' => $this->expense_account,
            'wholesale_price' => $this->wholesale_price,
            'retail_price' => $this->retail_price,
            'distribution_price' => $this->distribution_price,
            'packet_price' => $this->packet_price,
            'measurement' => $this->measurement,
            'min_stock_level' => $this->min_stock_level,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'batches' =>   BatchResource::collection($this->whenLoaded('batches')),

            'batch_id' => $this->whenPivotLoaded('role_users', function () {
                return $this->pivot->batch_id;
            }),
        ];
    }
}
