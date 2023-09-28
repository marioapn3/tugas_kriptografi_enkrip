<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Report\IncomeStatement\IncomeStatementResource;
use App\Services\Report\IncomeStatementService;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
}
