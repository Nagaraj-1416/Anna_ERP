<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed code
 * @property mixed name
 * @property mixed notes
 * @property mixed balance_type
 * @property mixed is_default
 * @property mixed is_active
 * @property mixed created_at
 * @property mixed updated_at
 */
class AccountCategoryResource extends JsonResource
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
            'notes' => $this->notes,
            'balance_type' => $this->balance_type,
            'is_default' => $this->is_default,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}