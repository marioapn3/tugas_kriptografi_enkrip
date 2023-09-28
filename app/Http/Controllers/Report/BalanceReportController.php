<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Report\Balance\BalanceReportResource;
use App\Services\Report\BalanceReportService as ReportBalanceReportService;
use BalanceReportService;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
}
