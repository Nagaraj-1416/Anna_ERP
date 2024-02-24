<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed code
 * @property mixed expense_date
 * @property mixed expense_time
 * @property mixed calculate_mileage_using
 * @property mixed notes
 * @property mixed amount
 * @property mixed distance
 * @property mixed start_reading
 * @property mixed end_reading
 * @property mixed status
 * @property mixed prepared_by
 * @property mixed approved_by
 * @property mixed staff_id
 * @property mixed company_id
 * @property mixed daily_sale_id
 * @property mixed sales_handover_id
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed gps_lat
 * @property mixed gps_long
 * @property mixed liter
 * @property mixed odometer
 * @property mixed type_id
 * @property mixed type_name
 */
class SalesExpenseResource extends JsonResource
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
            'expense_date' => $this->expense_date,
            'expense_time' => $this->expense_time,
            'calculate_mileage_using' => $this->calculate_mileage_using,
            'notes' => $this->notes,
            'amount' => $this->amount,
            'distance' => $this->distance,
            'start_reading' => $this->start_reading,
            'end_reading' => $this->end_reading,
            'status' => $this->status,
            'prepared_by' => $this->prepared_by,
            'approved_by' => $this->approved_by,
            'staff_id' => $this->staff_id,
            'company_id' => $this->company_id,
            'daily_sale_id' => $this->daily_sale_id,
            'sales_handover_id' => $this->sales_handover_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'gps_lat' => $this->gps_lat,
            'gps_long' => $this->gps_long,
            'liter' => $this->liter,
            'odometer' => $this->odometer,
            'type_id' => $this->type_id,
            'type_name' => $this->type->name,
        ];
    }
}
