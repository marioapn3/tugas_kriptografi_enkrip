<?php

namespace App\Http\Controllers\Report;

use App\Exports\BalanceExport;
use App\Http\Controllers\AdminBaseController;
use App\Http\Resources\Report\Balance\BalanceReportResource;
use App\Services\Report\BalanceReportService as ReportBalanceReportService;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class BalanceReportController extends AdminBaseController
{
    private  $balanceReportService;
    public function __construct(ReportBalanceReportService $balanceReportService)
    {
        $this->balanceReportService = $balanceReportService;
    }

    public function balanceIndex()
    {
        return Inertia::render($this->source . 'report/balance/index', [
            'title' => 'Balance Report | Jurnalin'
        ]);
    }

    public function getData(Request $request)
    {
        try {
            $data = $this->balanceReportService->getData($request);

            $result = new BalanceReportResource($data);
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
            $data = $this->balanceReportService->getPdfData($start_date, $end_date);

            $pdf = PDF::loadView('export.balance.balance_pdf', [
                'accounts' => $data['accounts'],
                'start_date' => $start_date,
                'end_date' => $end_date
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
            return Excel::download(new BalanceExport($start_date, $end_date), 'balance_report_' . Carbon::now()->timestamp . '.xlsx');
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
