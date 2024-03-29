<?php

namespace App\Http\Controllers\Journal;

use App\Actions\Options\GetClasificationOptions;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Journals\AccountCategoryRequest;
use App\Http\Resources\Journal\AccountCategoryListResource;
use App\Http\Resources\Journal\CategoryCodeResource;
use App\Http\Resources\SubmitDefaultResource;
use App\Models\AccountCategory;
use App\Services\Journal\AccountCategoryService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountCategoryController extends AdminBaseController
{
    private $accountCategoryService;
    private $getClasificationOptions;
    public function __construct(AccountCategoryService $accountCategoryService, GetClasificationOptions $getClasificationOptions)
    {
        $this->accountCategoryService = $accountCategoryService;
        $this->getClasificationOptions = $getClasificationOptions;
    }

    public function accountCategoryIndex()
    {
        return Inertia::render($this->source . 'journal/accountCategory/index', [
            "title" => 'Account Category | Jurnalin',
            'additional' => [
                'clasification_options' => $this->getClasificationOptions->handle()
            ]
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

    public function generateCode($id_classification)
    {
        try {
            $data = $this->accountCategoryService->generateCode($id_classification);
            $result = new CategoryCodeResource($data, 'Success generate code');
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
