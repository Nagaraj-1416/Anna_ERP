<?php

namespace App\Exports;

use App\Product;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromCollection, Responsable, ShouldAutoSize, WithHeadings, FromQuery, WithMapping
{
    use Exportable;

    /**
     * It's required to define the fileName within
     * the export class when making use of Responsable.
     */

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Collection|static[]
     */
    public function collection()
    {
        return Product::all();
    }


    /**
     * @return array
     */
    public function headings(): array
    {
        $headings = (new Product())->export;
        array_push($headings, 'Available Qty');
        return $headings;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        return Product::query()->select((new Product())->export);
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        $row->stock = Product::where('code', $row->code)->first()->stock->available_stock ?? 'None';
        return $row->toArray();
    }
}
