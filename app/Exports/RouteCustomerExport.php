<?php

namespace App\Exports;

use App\Customer;
use App\Route;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RouteCustomerExport implements Responsable, ShouldAutoSize, WithHeadings, FromCollection
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
        $headings = (new Customer())->export;
        return $headings;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $headings = (new Customer())->export;
        return $this->route->customers()->select($headings)->get();
    }
}
