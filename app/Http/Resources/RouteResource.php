<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed is_active
 * @property mixed created_at
 * @property mixed updated_at
 * @property string code
 * @property string name
 * @property string notes
 * @property array start_point
 * @property array end_point
 * @property array way_points
 */
class RouteResource extends JsonResource
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
            'start_point' => $this->start_point,
            'end_point' => $this->end_point,
            'way_points' => $this->way_points,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'customers' => CustomerForDashboardResource::collection($this->whenLoaded('customers')),
            'targets' => TargetResource::collection($this->whenLoaded('targets')),
        ];
    }
}
