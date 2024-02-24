<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'Vehicle' => $this->Vehicle,
            'engine_no' => $this->engine_no,
            'chassis_no' => $this->chassis_no,
            'reg_date' => $this->reg_date,
            'year' => $this->year,
            'color' => $this->color,
            'fuel_type' => $this->fuel_type,
            'type_id' => $this->type_id,
            'make_id' => $this->make_id,
            'model_id' => $this->model_id,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
            'company_id' => $this->company_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
