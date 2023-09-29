<?php

namespace App\Http\Controllers\Journal;

use App\Actions\Options\GetAccountCategoryOptions;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Journals\AccountRequest;
use App\Http\Resources\Journal\AccountDetailResource;
use App\Http\Resources\Journal\AccountListResource;
use App\Http\Resources\Journal\CategoryCodeResource;
use App\Http\Resources\SubmitDefaultResource;
use App\Models\Account;
use App\Services\Journal\AccountService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends AdminBaseController
{
    private $accountServices;
    private $getAccountCategoryOptions;

    public function __construct(AccountService $accountServices, GetAccountCategoryOptions $getAccountCategoryOptions)
    {
        $this->accountServices = $accountServices;
        $this->getAccountCategoryOptions = $getAccountCategoryOptions;
    }

    public function accountIndex()
    {
        return Inertia::render($this->source . 'journal/account/index', [
            "title" => 'Account | Jurnalin',
            'additional' => [
                'category_options' => $this->getAccountCategoryOptions->handle()
            ]
        ]);
    }

    public function show($id)
    {
        $data = $this->accountServices->getDataById($id);
        $result = new AccountDetailResource($data, 'Success get detail account');
        // return $result;
        return Inertia::render($this->source . 'journal/account/detail', [
            "title" => 'Account Detail | Jurnalin',
            'data' => $result,

        ]);
    }

    public function getData(Request $request)
    {
        try {
            $data = $this->accountServices->getData($request);
            $result = new AccountListResource($data);
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function generateCode($account_category_id)
    {
        try {
            $data = $this->accountServices->generateCode($account_category_id);
            $result = new CategoryCodeResource($data, 'Success generate code');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function createData(AccountRequest $request)
    {
        try {
            $data = $this->accountServices->createData($request);
            $result = new SubmitDefaultResource($data, 'Success create a new account ');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function updateData(Account $account, AccountRequest $request)
    {
        try {
            $data = $this->accountServices->updateData($account, $request);
            $result = new SubmitDefaultResource($data, 'Success update account');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function deleteData($id, Request $request)
    {
        try {
            $data = $this->accountServices->deleteData($id, $request);
            $result = new SubmitDefaultResource($data, 'Success delete account category');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
