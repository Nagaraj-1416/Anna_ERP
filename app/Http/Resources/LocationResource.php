<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed name
 * @property mixed code
 * @property mixed notes
 * @property mixed is_active
 * @property mixed route
 * @property mixed id
 * @property mixed created_at
 * @property mixed updated_at
 */
class LocationResource extends JsonResource
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
            'name' => $this->name,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
            'route' => new RouteResource($this->whenLoaded('route')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
