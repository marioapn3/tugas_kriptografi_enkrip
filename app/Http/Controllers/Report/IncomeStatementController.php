<?php

namespace App\Http\Controllers\Report;

use App\Exports\IncomeExport;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Report\IncomeStatement\IncomeStatementResource;
use App\Services\Report\IncomeStatementService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class IncomeStatementController extends AdminBaseController
{
    private  $incomeStatementService;
    public function __construct(IncomeStatementService $incomeStatementService)
    {
        $this->incomeStatementService = $incomeStatementService;
    }

    public function incomeIndex()
    {
        return Inertia::render($this->source . 'report/incomeStatement/index', [
            'title' => 'Income Statement Report | Jurnalin'
        ]);
    }

    public function getData(Request $request)
    {
        try {
            $data = $this->incomeStatementService->getData($request);
            $result = new IncomeStatementResource($data);
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function exportPdfReport(Request $request)
    {
        try {
            $start_date = $request->start_date ?: Carbon::now()->startOfMonth()->format('Y-m-d');
            $end_date = $request->end_date ?: Carbon::now()->endOfMonth()->format('Y-m-d');
            $data = $this->incomeStatementService->getPdfData($start_date, $end_date);

            $pdf = PDF::loadView('export.income.income_report_pdf', [
                'data' => $data['accounts'],
                'totalIncome' => $data['totalIncome'],
                'totalExpense' => $data['totalExpense'],
                'start_date' => $start_date,
                'end_date' => $end_date,
            ])->setPaper('a4', 'portrait');
            return $pdf->download('BalanceReport_' . Carbon::now()->timestamp . '.pdf');
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function exportExcelReport(Request $request)
    {
        try {
            $start_date = $request->start_date ?: Carbon::now()->startOfMonth()->format('Y-m-d');
            $end_date = $request->end_date ?: Carbon::now()->endOfMonth()->format('Y-m-d');

            return Excel::download(new IncomeExport($start_date, $end_date, $this->incomeStatementService), 'income_statement_' . Carbon::now()->timestamp . '.xlsx');
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
