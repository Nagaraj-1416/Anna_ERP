<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string code
 * @property string name
 * @property string display_name
 * @property string phone
 * @property string fax
 * @property string mobile
 * @property string email
 * @property string website
 * @property string base_currency
 * @property mixed fiscal_year_start
 * @property mixed fiscal_year_end
 * @property mixed timezone
 * @property string date_time_format
 * @property mixed business_starts_at
 * @property mixed business_end_at
 * @property string is_active
 * @property string country
 * @property mixed id
 * @property mixed created_at
 * @property mixed updated_at
 */
class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'phone' => $this->phone,
            'fax' => $this->fax,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'website' => $this->website,
            'base_currency' => $this->base_currency,
            'fiscal_year_start' => $this->fiscal_year_start,
            'fiscal_year_end' => $this->fiscal_year_end,
            'timezone' => $this->timezone,
            'date_time_format' => $this->date_time_format,
            'business_starts_at' => $this->business_starts_at,
            'business_end_at' => $this->business_end_at,
            'is_active' => $this->is_active,
            'country' => new RouteResource($this->whenLoaded('country')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
        ];
    }
}
