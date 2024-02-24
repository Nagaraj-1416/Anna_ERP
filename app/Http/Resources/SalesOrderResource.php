<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed order_no
 * @property mixed uuid
 * @property mixed order_date
 * @property mixed delivery_date
 * @property mixed order_type
 * @property mixed scheduled_date
 * @property mixed is_po_received
 * @property mixed po_no
 * @property mixed po_date
 * @property mixed po_file
 * @property mixed terms
 * @property mixed notes
 * @property mixed sub_total
 * @property mixed discount
 * @property mixed discount_rate
 * @property mixed discount_type
 * @property mixed adjustment
 * @property mixed total
 * @property mixed status
 * @property mixed delivery_status
 * @property mixed invoice_status
 * @property mixed is_invoiced
 * @property mixed prepared_by
 * @property mixed approval_status
 * @property mixed approved_by
 * @property mixed customer_id
 * @property mixed business_type_id
 * @property mixed company_id
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed price_book_id
 * @property mixed rep_id
 * @property mixed sales_type
 * @property mixed customer_outstanding
 * @property mixed ref
 * @property mixed gps_lat
 * @property mixed gps_long
 * @property mixed is_order_printed
 * @property mixed is_credit_sales
 */
class SalesOrderResource extends JsonResource
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
            'order_no' => $this->order_no,
            'uuid' => $this->uuid,
            'ref' => $this->ref,
            'order_date' => $this->order_date,
            'delivery_date' => $this->delivery_date,
            'order_type' => $this->order_type,
            'scheduled_date' => $this->scheduled_date,
            'is_po_received' => $this->is_po_received,
            'po_no' => $this->po_no,
            'po_date' => $this->po_date,
            'po_file' => $this->po_file,
            'terms' => $this->terms,
            'notes' => $this->notes,
            'sub_total' => $this->sub_total,
            'discount' => (double) $this->discount,
            'discount_rate' => $this->discount_rate,
            'discount_type' => $this->discount_type,
            'adjustment' => $this->adjustment,
            'total' => (double) $this->total,
            'status' => $this->status,
            'delivery_status' => $this->delivery_status,
            'invoice_status' => $this->invoice_status,
            'is_invoiced' => $this->is_invoiced,
            'prepared_by' => $this->prepared_by,
            'approval_status' => $this->approval_status,
            'approved_by' => $this->approved_by,
            'customer_id' => $this->customer_id,
            'price_book_id' => $this->price_book_id,
            'is_order_printed' => $this->is_order_printed,
            'is_credit_sales' => $this->is_credit_sales,
            'rep_id' => $this->rep_id,
            'sales_type' => $this->sales_type,
            'business_type_id' => $this->business_type_id,
            'customer_outstanding' => $this->customer_outstanding,
            'company_id' => $this->company_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'gps_lat' => $this->gps_lat,
            'gps_long' => $this->gps_long,
            'preparedBy' =>   new UserResource($this->whenLoaded('preparedBy')),
            'approvedBy' =>   new UserResource($this->whenLoaded('approvedBy')),
            'customer' =>   new CustomerResource($this->whenLoaded('customer')),
            'businessType' =>   new BusinessTypeResource($this->whenLoaded('businessType')),
            'company' =>   new CompanyResource($this->whenLoaded('company')),
            'order_items' =>   ProductItemResource::collection($this->whenLoaded('products')),
            'products' =>   ProductResource::collection($this->whenLoaded('products')),
            'invoices' =>   InvoiceResource::collection($this->whenLoaded('invoices')),
            'payments' =>   InvoicePaymentResource::collection($this->whenLoaded('payments')),
            'comments' =>   CommentResource::collection($this->whenLoaded('comments')),
            'price_book' =>  new PriceBookResource($this->whenLoaded('priceBook')),
            'rep' =>  new RepResource($this->whenLoaded('salesRep')),
        ];
    }
}
