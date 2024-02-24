<?php

namespace App\Exports;

use App\DailySale;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class SalesSheetExport implements FromView
{
    use Exportable;
    public $allocation;
    public $allocationData;

    public function __construct(DailySale $allocation, $data)
    {
        $this->allocation = $allocation;
        $this->allocationData = $data;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view('sales.allocation.export.sheet', [
            'allocationData' => $this->allocationData,
            'allocation' => $this->allocation
        ]);
    }

}
