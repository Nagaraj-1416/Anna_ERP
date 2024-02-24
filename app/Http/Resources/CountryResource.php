<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed capital
 * @property mixed citizenship
 * @property mixed country_code
 * @property mixed currency
 * @property mixed currency_code
 * @property mixed currency_sub_unit
 * @property mixed currency_symbol
 * @property mixed full_name
 * @property mixed iso_3166_2
 * @property mixed iso_3166_3
 * @property mixed name
 * @property mixed region_code
 * @property mixed sub_region_code
 * @property mixed eea
 * @property mixed calling_code
 * @property mixed flag
 * @property mixed id
 * @property mixed created_at
 * @property mixed updated_at
 */
class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'capital' => $this->capital,
            'citizenship' => $this->citizenship,
            'country_code' => $this->country_code,
            'currency' => $this->currency,
            'currency_code' => $this->currency_code,
            'currency_sub_unit' => $this->currency_sub_unit,
            'currency_symbol' => $this->currency_symbol,
            'full_name' => $this->full_name,
            'iso_3166_2' => $this->iso_3166_2,
            'iso_3166_3' => $this->iso_3166_3,
            'name' => $this->name,
            'region_code' => $this->region_code,
            'sub_region_code' => $this->sub_region_code,
            'eea' => $this->eea,
            'calling_code' => $this->calling_code,
            'flag' => $this->flag,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
