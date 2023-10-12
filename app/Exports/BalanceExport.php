<?php

namespace App\Exports;

use App\Models\Account;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BalanceExport implements FromView
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
    public function __construct($start_date, $end_date,)
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
            $query->whereBetween('date', [$this->start_date, $this->end_date]);
        })->get();

        return view('export.balance.balance_excel', [
            'accounts' => $accounts,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date
        ]);
    }
}
