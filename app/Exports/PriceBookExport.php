<?php

namespace App\Exports;

use App\PriceBook;
use App\Price;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PriceBookExport implements Responsable, ShouldAutoSize, WithHeadings, FromCollection, WithMapping
{
    use Exportable;
    public $priceBook;
    public $headings = ['Products', 'Price', 'QtyStartRange', 'QtyEndRange'];

    public function __construct(PriceBook $priceBook)
    {
        $this->priceBook = $priceBook;
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
        $row->Products = $row->product->name ?? 'None';
        $row->Price = $row->price ?? 'None';
        $row->QtyStartRange = $row->range_start_from ?? '0';
        $row->QtyEndRange = $row->range_end_to ?? '0';

        $row = $row->toArray();
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
        $select = (new Price())->export;
        return $this->priceBook->prices()->with('product')
            ->select($select)->get()
            ->sortBy('product.name');
    }
}
