<?php

namespace App\Exports;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportExport implements FromView
{
    private $start_date;
    private $end_date;

    /**
     * __construct
     *
     * @param  mixed $start_date
     * @param  mixed $end_date
     * @return void
     */
    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date   = $end_date;
    }

    /**
     * view
     *
     * @return View
     */
    public function view(): View
    {
        $accounts = Account::with('journalDetails')->whereHas('journalDetails.journal', function ($query) {
            // You can now access $this->start_date and $this->end_date here
            $query->whereBetween('date', [$this->start_date, $this->end_date]);
        })->get();

        return view('export.general_ledger_report_excel', [
            'accounts' => $accounts,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date
        ]);
    }
}
