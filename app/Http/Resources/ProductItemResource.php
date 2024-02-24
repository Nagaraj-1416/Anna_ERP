<?php

namespace App\Http\Resources;

use App\Store;
use App\UnitType;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $store = $this->pivot->store_id ? Store::find($this->pivot->store_id) : null;
        $unitType = $this->pivot->unit_type_id ? UnitType::find($this->pivot->unit_type_id) : null;
        return [
            'product_id' => $this->id,
            'product_name' => $this->name,
            'tamil_name' => $this->tamil_name,
            'price_book_id' => $this->whenPivotLoaded('product_sales_order', function () {
                return $this->pivot->price_book_id;
            }),
            'store_id' => $this->whenPivotLoaded('product_sales_order', function () {
                return $this->pivot->store_id;
            }),
            'store_name' => $store ? $store->name : '',
            'unit_type_id' => $unitType ? $unitType->id : null,
            'unit_type_name' => $unitType ? $unitType->name : null,
            'quantity' => $this->whenPivotLoaded('product_sales_order', function () {
                return $this->pivot->quantity;
            }),
            'rate' => $this->whenPivotLoaded('product_sales_order', function () {
                return $this->pivot->rate;
            }),
            'discount_type' => $this->whenPivotLoaded('product_sales_order', function () {
                return $this->pivot->discount_type;
            }),
            'discount_rate' => $this->whenPivotLoaded('product_sales_order', function () {
                return $this->pivot->discount_rate;
            }),
            'discount' => $this->whenPivotLoaded('product_sales_order', function () {
                return $this->pivot->discount;
            }),
            'amount' => $this->whenPivotLoaded('product_sales_order', function () {
                return $this->pivot->amount;
            }),
            'status' => $this->whenPivotLoaded('product_sales_order', function () {
                return $this->pivot->status;
            }),
            'notes' => $this->whenPivotLoaded('product_sales_order', function () {
                return $this->pivot->notes;
            }),
        ];
    }
}
