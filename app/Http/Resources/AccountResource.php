<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed code
 * @property mixed name
 * @property mixed short_name
 * @property mixed notes
 * @property mixed is_default
 * @property mixed is_active
 * @property mixed closing_bl_carried
 * @property mixed first_tx_date
 * @property mixed latest_tx_date
 * @property mixed parent_account_id
 * @property mixed account_category_id
 * @property mixed company_id
 * @property mixed created_at
 * @property mixed updated_at
 */
class AccountResource extends JsonResource
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
            'short_name' => $this->short_name,
            'notes' => $this->notes,
            'is_default' => $this->is_default,
            'is_active' => $this->is_active,
            'closing_bl_carried' => $this->closing_bl_carried,
            'first_tx_date' => $this->first_tx_date,
            'latest_tx_date' => $this->latest_tx_date,
            'parent_account_id' => $this->parent_account_id,
            'account_category_id' => $this->account_category_id,
            'company_id' => $this->company_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'parent' =>   new AccountResource($this->whenLoaded('parent')),
            'company' =>   new CompanyResource($this->whenLoaded('company')),
            'type' =>   new AccountTypeResource($this->whenLoaded('type')),
            'category' =>   new AccountCategoryResource($this->whenLoaded('category')),
        ];
    }
}
