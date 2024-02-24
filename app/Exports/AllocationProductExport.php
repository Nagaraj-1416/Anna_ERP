<?php

namespace App\Exports;

use App\DailySale;
use App\DailySaleCustomer;
use App\DailySaleItem;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AllocationProductExport implements Responsable, ShouldAutoSize, WithHeadings, FromCollection, WithMapping
{
    use Exportable;
    public $allocation;
    public $headings = ['product_name', 'tamil_name', 'cf_qty', 'quantity', 'sold_qty', 'replaced_qty', 'returned_qty', 'shortage_qty', 'excess_qty', 'damaged_qty', 'restored_qty', 'available_qty'];

    public function __construct(DailySale $allocation)
    {
        $this->allocation = $allocation;
    }
    /**
     * It's required to define the fileName within
     * the export class when making use of Responsable.
     */

    /**
     * @return array
     */
    public function headings(): array
    {
        return $this->headings;
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        $row->product_name = $row->product->name ?? 'None';
        $row->tamil_name = $row->product->tamil_name ?? 'None';
        $row->cf_qty = $row->cf_qty ?? 0;
        $row->quantity = $row->quantity ?? 0;
        $row->sold_qty = $row->sold_qty ?? 0;
        $row->replaced_qty = $row->replaced_qty ?? 0;
        $row->restored_qty = $row->restored_qty ?? 0;

        $row->returned_qty = $row->returned_qty ? $row->returned_qty : 0;
        $row->shortage_qty = $row->shortage_qty ?? 0;
        $row->damaged_qty = $row->damaged_qty ?? 0;
        $row->excess_qty = $row->excess_qty ?? 0;
        $row->available_qty = getAvailableQty($row);
        $row = $row->toArray();
        unset($row['product']);
        unset($row['product_id']);
        $array = [];
        foreach ($this->headings as $heading) {
            $array[$heading] = array_get($row, $heading, 'None');
        }
        return $array;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function collection()
    {
        $select = (new DailySaleItem())->export;
        return $this->allocation->items()->with('product')->select($select)->get();
    }
}
