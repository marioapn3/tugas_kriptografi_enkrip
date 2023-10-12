<?php

namespace App\Exports;

use App\Models\Account;
use App\Services\Report\BalanceReportService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BalanceExport implements FromView
{
    private $start_date;
    private $end_date;
    private $balanceReportService;

    /**
     * __construct
     *
     * @param  mixed $start_date
     * @param  mixed $end_date
     * @return void
     */
    public function __construct($start_date, $end_date,)
    {
        $this->start_date = $start_date;
        $this->end_date   = $end_date;
        $this->balanceReportService = new BalanceReportService();
    }

    /**
     * view
     *
     * @return View
     */
    public function view(): View
    {
        $data = $this->balanceReportService->getPdfData($this->start_date, $this->end_date);

        return view('export.balance.balance_excel', [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'accounts' => $data['accounts'],
            'total_debit' => $data['totalDebit'],
            'total_credit' => $data['totalCredit']
        ]);
    }
}
