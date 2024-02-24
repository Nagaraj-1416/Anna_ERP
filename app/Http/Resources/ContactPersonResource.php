<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed first_name
 * @property mixed last_name
 * @property mixed salutation
 * @property mixed full_name
 * @property mixed phone
 * @property mixed mobile
 * @property mixed email
 * @property mixed designation
 * @property mixed department
 * @property mixed is_active
 * @property mixed created_at
 * @property mixed updated_at
 */
class ContactPersonResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'salutation' => $this->salutation,
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'designation' => $this->designation,
            'department' => $this->department,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
