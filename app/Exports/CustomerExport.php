<?php

namespace App\Exports;

use App\Customer;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerExport implements FromCollection, Responsable, ShouldAutoSize, WithHeadings, FromQuery, WithMapping
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
        return Customer::select((new Customer())->export)->get();
    }


    /**
     * @return array
     */
    public function headings(): array
    {
        $customers = (new Customer())->export;
        array_push($customers, 'route');
        array_push($customers, 'location');
        return $customers;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        return Customer::query()->select((new Customer())->export);
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        $row->route_name = Customer::where('code', $row->code)->first()->route->name ?? 'None';
        $row->location_name = Customer::where('code', $row->code)->first()->location->name ?? 'None';
        return $row->toArray();
    }
}
