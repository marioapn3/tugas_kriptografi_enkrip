<?php

namespace App\Http\Controllers\Report;

use App\Exports\ReportExport;
use App\Http\Controllers\AdminBaseController;
use App\Http\Resources\Report\Ledger\GeneralLedgerReportResource;
use App\Models\Account;
use App\Services\Report\GeneralLedgerReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GeneralLedgerReportController extends AdminBaseController
{
    private  $generalLedgerReportService;
    public function __construct(GeneralLedgerReportService $generalLedgerReportService)
    {
        $this->generalLedgerReportService = $generalLedgerReportService;
    }

    public function ledgerIndex()
    {
        return Inertia::render($this->source . 'report/ledger/index', [
            'title' => 'General Ledger Report | Jurnalin'
        ]);
    }

    public function getData(Request $request)
    {
        try {
            $data = $this->generalLedgerReportService->getData($request);

            $result = new GeneralLedgerReportResource($data);
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function exportExcelReport(Request $request)
    {
        try {
            $start_date = $request->start_date ?: Carbon::now()->startOfMonth()->format('Y-m-d');
            $end_date = $request->end_date ?: Carbon::now()->endOfMonth()->format('Y-m-d');

            return Excel::download(new ReportExport($start_date, $end_date), 'transactions_' . Carbon::now()->timestamp . '.xlsx');
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function exportPdfReport(Request $request)
    {
        try {
            $start_date = $request->start_date ?: Carbon::now()->startOfMonth()->format('Y-m-d');
            $end_date = $request->end_date ?: Carbon::now()->endOfMonth()->format('Y-m-d');
            $data = $this->generalLedgerReportService->getPdfData($start_date, $end_date);

            $pdf = PDF::loadView('export.general_ledger_report', [
                'accounts' => $data['accounts'],
                'start_date' => $start_date,
                'end_date' => $end_date
            ])->setPaper('a4', 'landscape');
            return $pdf->download('GeneralLedger_' . Carbon::now()->timestamp . '.pdf');
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function showPdf(Request $request)
    {
        try {

            $accounts = Account::all();

            return view('export.general_ledger_report_excel', compact('accounts'));
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
