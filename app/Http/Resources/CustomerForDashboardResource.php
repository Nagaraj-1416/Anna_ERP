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
 * @property mixed no_of_order
 * @property mixed total_sales
 * @property mixed no_of_invoice
 * @property mixed total_invoiced
 * @property mixed received_payment
 * @property mixed payment_reminding
 * @property mixed old_sales
 * @property mixed tamil_name
 */
class CustomerForDashboardResource extends JsonResource
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
            'salutation' => $this->salutation,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'display_name' => $this->display_name,
            'tamil_name' => $this->tamil_name,
            'phone' => $this->phone,
            'fax' => $this->fax,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'website' => $this->website,
            'type' => $this->type,
            'gps_lat' => $this->gps_lat,
            'gps_long' => $this->gps_long,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
            'outstanding' => $this->outstanding,
            'no_of_order' => $this->no_of_order,
            'total_sales' => $this->total_sales,
            'no_of_invoice' => $this->no_of_invoice,
            'total_invoiced' => $this->total_invoiced,
            'received_payment' => $this->received_payment,
            'payment_reminding' => $this->payment_reminding,
            'old_sales' => $this->old_sales,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'route' => new RouteResource($this->whenLoaded('route')),
            'location' => new LocationResource($this->whenLoaded('location')),
            'company' => new CompanyResource($this->whenLoaded('company')),
            'contactPersons' => ContactPersonResource::collection($this->whenLoaded('contactPersons')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
        ];
    }
}
