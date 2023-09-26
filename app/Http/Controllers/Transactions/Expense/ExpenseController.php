<?php

namespace App\Http\Controllers\Transactions\Expense;

use App\Actions\Options\GetAccountOptions;
use App\Actions\Options\GetExpenseAccountOptions;
use App\Enum\Accounts\Classification;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transactions\Expense\CreateExpenseRequest;
use App\Http\Requests\Transactions\Expense\UpdateExpenseRequest;
use App\Http\Resources\SubmitDefaultResource;
use App\Http\Resources\Transactions\Expense\ExpenseListResource;
use App\Services\Transactions\ExpenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ExpenseController extends AdminBaseController
{
    private $expenseService;
    private $getExpenseAccountOptions;
    private $getAccountOptions;

    public function __construct(ExpenseService $expenseService, GetExpenseAccountOptions $getExpenseAccountOptions, GetAccountOptions $getAccountOptions)
    {
        $this->expenseService = $expenseService;
        $this->getExpenseAccountOptions = $getExpenseAccountOptions;
        $this->getAccountOptions = $getAccountOptions;
    }

    public function expenseIndex()
    {
        return Inertia::render($this->source . 'transactions/expense/index', [
            'title' => 'Expense | Jurnalin'
        ]);
    }


    public function getData(Request $request)
    {
        try {
            $data = $this->expenseService->getData($request);
            $result = new ExpenseListResource($data);
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function create()
    {
        return Inertia::render($this->source . 'transactions/expense/create', [
            'title' => 'Create Expense | Jurnalin',
            'additional' => [
                'account_options' => $this->getAccountOptions->handle([Classification::BEBAN]),
                'expense_account_options' => $this->getExpenseAccountOptions->handle(),
                // 'product_options' => $this->getProductOptions->handle()
            ]
        ]);
    }
    public function store(CreateExpenseRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->expenseService->store($request);
            $result = new SubmitDefaultResource($data, 'Expense created');
            DB::commit();
            return $this->respond($result);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionError($e->getMessage());
        }
    }

    public function edit($id)
    {
        $data = $this->expenseService->getDataById($id);
        return Inertia::render($this->source . 'transactions/expense/create', [
            'title' => 'Create Sales | Jurnalin',
            'additional' => [
                'account_options' => $this->getAccountOptions->handle(),
                'expense_account_options' => $this->getExpenseAccountOptions->handle(),
                'data' => $data
            ]
        ]);
    }
    public function updateData($id, UpdateExpenseRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->expenseService->updateData($id, $request);
            $result = new SubmitDefaultResource($data, 'Success update Expense');
            DB::commit();
            return $this->respond($result);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionError($e->getMessage());
        }
    }
    public function deleteData($id)
    {
        try {
            $data = $this->expenseService->destroy($id);
            $result = new SubmitDefaultResource($data, 'Success delete expense');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
