<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed code
 * @property mixed salutation
 * @property mixed first_name
 * @property mixed last_name
 * @property mixed full_name
 * @property mixed display_name
 * @property mixed phone
 * @property mixed fax
 * @property mixed mobile
 * @property mixed email
 * @property mixed website
 * @property mixed type
 * @property mixed gps_lat
 * @property mixed gps_long
 * @property mixed notes
 * @property mixed is_active
 * @property mixed route
 * @property mixed location
 * @property mixed company
 * @property mixed id
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed outstanding
 * @property mixed cl_amount
 * @property mixed cl_notify_rate
 * @property mixed is_today_allocation
 * @property mixed tamil_name
 * @property mixed outstanding_orders
 * @property mixed not_realized_cheque
 * @property mixed current_cl_days
 */
class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $data = [
            'id' => $this->id,
            'code' => $this->code,
            'salutation' => $this->salutation,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'display_name' => $this->display_name,
            'tamil_name' => $this->tamil_name,
            'phone' => $this->phone,
            'fax' => $this->fax ? $this->fax : 'None',
            'mobile' => $this->mobile ? $this->mobile : 'None',
            'email' => $this->email ? $this->email : 'None',
            'website' => $this->website ? $this->website : 'None',
            'type' => $this->type,
            'gps_lat' => $this->gps_lat,
            'gps_long' => $this->gps_long,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
            'outstanding' => $this->outstanding,
            'credit_limit_amount' => $this->cl_amount,
            'balance_cl' => $this->balance_cl ?? 0,
            'cl_days' => $this->cl_days ?? 0,
            'credit_limit_notify_rate' => $this->cl_notify_rate,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'route' => new RouteResource($this->whenLoaded('route')),
            'location' => new LocationResource($this->whenLoaded('location')),
            'company' => new CompanyResource($this->whenLoaded('company')),
            'contact_persons' => ContactPersonResource::collection($this->whenLoaded('contactPersons')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
//            'orders' => SalesOrderResource::collection($this->whenLoaded('orders')),
//            'invoices' => InvoiceResource::collection($this->whenLoaded('invoices')),
//            'payments' => InvoicePaymentResource::collection($this->whenLoaded('payments')),
        ];
        if ($this->is_today_allocation){
            $data['is_today_allocation'] = $this->is_today_allocation;
        }
        if ($this->outstanding_orders){
            $data['outstanding_orders'] = $this->outstanding_orders;
        }

        $data['not_realized_cheque'] = $this->not_realized_cheque;
        $data['current_cl_days'] = $this->current_cl_days;
        return $data;
    }
}
