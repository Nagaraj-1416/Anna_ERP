<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed code
 * @property mixed salutation
 * @property mixed first_name
 * @property mixed last_name
 * @property mixed full_name
 * @property mixed short_name
 * @property mixed gender
 * @property mixed dob
 * @property mixed email
 * @property mixed phone
 * @property mixed mobile
 * @property mixed joined_date
 * @property mixed resigned_date
 * @property mixed designation
 * @property mixed bank_name
 * @property mixed branch
 * @property mixed account_name
 * @property mixed account_no
 * @property mixed epf_no
 * @property mixed etf_no
 * @property mixed notes
 * @property mixed is_active
 * @property mixed is_sales_rep
 * @property mixed id
 * @property mixed created_at
 * @property mixed updated_at
 */
class StaffResource extends JsonResource
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
            'code' => $this->code,
            'salutation' => $this->salutation,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'short_name' => $this->short_name,
            'gender' => $this->gender,
            'dob' => $this->dob,
            'email' => $this->email,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'joined_date' => $this->joined_date,
            'resigned_date' => $this->resigned_date,
            'designation' => $this->designation,
            'bank_name' => $this->bank_name,
            'branch' => $this->branch,
            'account_name' => $this->account_name,
            'account_no' => $this->account_no,
            'epf_no' => $this->epf_no,
            'etf_no' => $this->etf_no,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
            'is_sales_rep' => $this->is_sales_rep,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'companies' => CompanyResource::collection($this->whenLoaded('companies')),
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
            'rep' => new RepResource($this->whenLoaded('rep')),
        ];
    }
}
