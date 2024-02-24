<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed code
 * @property mixed name
 * @property mixed category
 * @property mixed type
 * @property mixed notes
 * @property mixed is_active
 */
class PriceBookResource extends JsonResource
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
            'category' => $this->category,
            'type' => $this->type,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
            'prices' => PriceResource::collection($this->whenLoaded('prices'))
        ];
    }
}
