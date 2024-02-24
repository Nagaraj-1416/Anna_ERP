<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed code
 * @property mixed name
 * @property mixed notes
 * @property mixed is_active
 * @property mixed staff_id
 * @property mixed vehicle_id
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed cl_amount
 * @property mixed cl_notify_rate
 */
class RepResource extends JsonResource
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
            'is_active' => $this->is_active,
            'staff_id' => $this->staff_id,
            'vehicle_id' => $this->vehicle_id,
            'credit_limit_amount' => $this->cl_amount,
            'credit_limit_notify_rate' => $this->cl_notify_rate,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'staff' => new StaffResource($this->whenLoaded('staff')),
            'vehicle' => new VehicleResource($this->whenLoaded('vehicle'))
        ];
    }
}
