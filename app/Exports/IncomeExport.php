<?php

namespace App\Exports;


use App\Services\Report\IncomeStatementService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IncomeExport implements FromView
{
    private $start_date;
    private $end_date;
    private $incomeStatementService;

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
        $this->incomeStatementService = new IncomeStatementService(); // Inisialisasi objek IncomeStatementService
    }

    /**
     * view
     *
     * @return View
     */
    public function view(): View
    {
        $data = $this->incomeStatementService->getPdfData($this->start_date, $this->end_date);
        return view('export.income.income_report_excel', [
            'data' => $data['accounts'],
            'totalIncome' => $data['totalIncome'],
            'totalExpense' => $data['totalExpense'],
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);
    }
}
