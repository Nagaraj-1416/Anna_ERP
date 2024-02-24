<?php

namespace App\Http\Resources\Sales;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = parent::toArray($request);
        array_forget($resource, 'invoices');
        array_forget($resource, 'orders');
        array_forget($resource, 'payments');
        array_forget($resource, 'total_orders');
        array_forget($resource, 'total_outstanding');
        array_forget($resource, 'total_paid');
        array_forget($resource, 'total_sales');
        return $resource;
    }
}
