<?php

namespace App\Exports;

use App\DailySale;
use App\DailySaleCustomer;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AllocationCustomerExport implements Responsable, ShouldAutoSize, WithHeadings, FromCollection, WithMapping
{
    use Exportable;
    public $allocation;
    public $headings = ['display_name', 'tamil_name', 'is_visited', 'reason', 'notes'];

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
        $row->display_name = $row->customer->display_name ?? 'None';
        $row->tamil_name = $row->customer->tamil_name ?? 'None';
        $row = $row->toArray();
        unset($row['customer']);
        unset($row['customer_id']);

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
        $headings = (new DailySaleCustomer())->export;
        return $this->allocation->customers()->with('customer')->select($headings)->get();
    }
}
