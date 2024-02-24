<?php

namespace App\Exports;

use App\Product;
use App\Route;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RouteProductExport implements Responsable, ShouldAutoSize, WithHeadings, FromCollection, WithMapping
{
    use Exportable;
    public $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
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
        $headings = (new Product())->export;
        array_push($headings, 'default_qty');
        return $headings;
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        $row->default_qty = $row->pivot->default_qty;
        $row = $row->toArray();
        unset($row['pivot']);
        return $row;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $headings = (new Product())->export;
        return $this->route->products()->select($headings)->get();
    }
}
