<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed code
 * @property mixed name
 * @property mixed salutation
 * @property mixed contact_person
 * @property mixed phone
 * @property mixed fax
 * @property mixed mobile
 * @property mixed email
 * @property mixed website
 * @property mixed notes
 * @property mixed is_active
 * @property mixed created_at
 * @property mixed updated_at
 */
class BankResource extends JsonResource
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
            'salutation' => $this->salutation,
            'contact_person' => $this->contact_person,
            'phone' => $this->phone,
            'fax' => $this->fax,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'website' => $this->website,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
