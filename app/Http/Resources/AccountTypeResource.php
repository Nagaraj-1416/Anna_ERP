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
 * @property mixed account_category_id
 * @property mixed created_at
 * @property mixed updated_at
 */
class AccountTypeResource extends JsonResource
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
            'account_category_id' => $this->account_category_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'category' =>   new AccountCategoryResource($this->whenLoaded('category')),
        ];
    }
}
