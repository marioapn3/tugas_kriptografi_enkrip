<?php

namespace App\Http\Controllers\Journal;

use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Journals\AccountCategoryRequest;
use App\Http\Resources\Journal\AccountCategoryListResource;
use App\Http\Resources\SubmitDefaultResource;
use App\Models\AccountCategory;
use App\Services\Journal\AccountCategoryService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountCategoryController extends AdminBaseController
{
    private $accountCategoryService;
    public function __construct(AccountCategoryService $accountCategoryService)
    {
        $this->accountCategoryService = $accountCategoryService;
    }

    public function accountCategoryIndex()
    {
        return Inertia::render($this->source . 'journal/accountCategory/index', [
            "title" => 'Account Category | Jurnalin',
        ]);
    }


    public function getData(Request $request)
    {
        try {
            $data = $this->accountCategoryService->getData($request);
            $result = new AccountCategoryListResource($data);
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function createData(AccountCategoryRequest $request)
    {
        try {
            $data = $this->accountCategoryService->createData($request);
            $result = new SubmitDefaultResource($data, 'Success create a new account category');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function updateData(AccountCategory $account_category, AccountCategoryRequest $request)
    {
        try {
            $data = $this->accountCategoryService->updateData($account_category, $request);
            $result = new SubmitDefaultResource($data, 'Success update account category');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function deleteData($id, Request $request)
    {
        try {
            $data = $this->accountCategoryService->deleteData($id, $request);
            $result = new SubmitDefaultResource($data, 'Success delete account category');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
