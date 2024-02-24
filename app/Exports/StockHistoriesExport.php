<?php

namespace App\Exports;

use App\Stock;
use App\StockHistory;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockHistoriesExport implements Responsable, ShouldAutoSize, WithHeadings, FromCollection, WithMapping
{
    use Exportable;
    public $stock;
    public $headings = ['Date', 'Type', 'IN', 'OUT', 'Description'];

    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
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
        $row->Date = $row->trans_date ?? 'None';
        $row->Type = $row->type ?? 'None';
        $row->IN = $row->transaction == 'In' ? $row->quantity : '0';
        $row->OUT = $row->transaction == 'Out' ? $row->quantity : '0';
        $row->Description = $row->trans_description ?? 0;

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
        $select = (new StockHistory())->export;
        return $this->stock->histories()->select($select)->get();
    }
}
