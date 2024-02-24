<?php

namespace App\Exports;

use App\Account;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class AccTransExport implements FromView
{
    use Exportable;
    public $account;
    public $transData;

    public function __construct(Account $account, $data)
    {
        $this->account = $account;
        $this->transData = $data;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view('finance.account.export-excel', [
            'transData' => $this->transData,
            'account' => $this->account
        ]);
    }

}
