<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed street_one
 * @property mixed street_two
 * @property mixed city
 * @property mixed province
 * @property mixed postal_code
 * @property mixed country_id
 */
class AddressResource extends JsonResource
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
            'street_one' => $this->street_one,
            'street_two' => $this->street_two,
            'city' => $this->city,
            'province' => $this->province,
            'postal_code' => $this->postal_code,
            'country_id' => $this->country_id,
            'country' => new CountryResource($this->whenLoaded('country'))
        ];
    }
}
