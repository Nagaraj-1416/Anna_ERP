<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed price
 * @property mixed product_id
 * @property mixed price_book_id
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed range_start_from
 * @property mixed range_end_to
 */
class PriceResource extends JsonResource
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
          'price' => $this->price,
          'range_start_from' => $this->range_start_from,
          'range_end_to' => $this->range_end_to,
          'product_id' => $this->product_id,
          'price_book_id' => $this->price_book_id,
          'created_at' => $this->created_at ,
          'updated_at' => $this->updated_at
        ];
    }
}
