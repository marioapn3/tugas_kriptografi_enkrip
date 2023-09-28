<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\AdminBaseController;
use App\Http\Resources\Report\Ledger\GeneralLedgerReportResource;
use App\Services\Report\GeneralLedgerReportService;
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
}
