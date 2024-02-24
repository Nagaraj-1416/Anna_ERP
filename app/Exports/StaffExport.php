<?php

namespace App\Exports;

use App\Staff;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StaffExport implements FromCollection, Responsable, ShouldAutoSize, WithHeadings, FromQuery
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
        return Staff::all();
    }


    /**
     * @return array
     */
    public function headings(): array
    {
        return (new Staff())->export;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        return Staff::query()->select((new Staff())->export);
    }
}
