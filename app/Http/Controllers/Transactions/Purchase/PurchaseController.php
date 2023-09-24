<?php

namespace App\Http\Controllers\Transactions\Purchase;

use App\Actions\Options\GetAccountOptions;
use App\Actions\Options\GetProductOptions;
use App\Actions\Options\GetSupplierOptions;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Purchase\PurchaseRequest;
use App\Http\Requests\Transactions\Purchase\CreatePurchaseRequest;
use App\Http\Requests\Transactions\Purchase\UpdatePurchaseRequest;
use App\Http\Resources\SubmitDefaultResource;
use App\Http\Resources\Transactions\GetProductDetailResource;
use App\Http\Resources\Transactions\Purchase\PurchaseListResource;
use App\Services\Storages\ProductService;
use App\Services\Transactions\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PurchaseController extends AdminBaseController
{
    private $purchaseService;
    private $getProductOptions;
    private $getSupplierOptions;
    private $getAccountOptions;
    private $productService;

    public function __construct(PurchaseService $purchaseService, ProductService $productService, GetProductOptions $getProductOptions, GetSupplierOptions $getSupplierOptions, GetAccountOptions $getAccountOptions)
    {
        $this->purchaseService = $purchaseService;
        $this->productService = $productService;
        $this->getProductOptions = $getProductOptions;
        $this->getSupplierOptions = $getSupplierOptions;
        $this->getAccountOptions = $getAccountOptions;
    }
    public function purchaseIndex()
    {
        return Inertia::render($this->source . 'transactions/purchase/index', [
            'title' => 'Purchase | Jurnalin'
        ]);
    }

    public function getData(Request $request)
    {
        try {
            $data = $this->purchaseService->getData($request);
            $result = new PurchaseListResource($data);
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function getProduct($id)
    {
        try {
            $data = $this->productService->getDetail($id);
            $result = new GetProductDetailResource($data, 'Product found');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function create()
    {
        return Inertia::render($this->source . 'transactions/purchase/create', [
            'title' => 'Create Purchase | Jurnalin',
            'additional' => [
                'product_options' => $this->getProductOptions->handle(),
                'account_options' => $this->getAccountOptions->handle(),
                'supplier_options' => $this->getSupplierOptions->handle()
            ]
        ]);
    }

    public function store(CreatePurchaseRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->purchaseService->createData($request);
            $result = new SubmitDefaultResource($data, 'Success create purchase');
            DB::commit();

            return $this->respond($result);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionError($e->getMessage());
        }
    }

    public function edit($id)
    {
        $data = $this->purchaseService->getDataById($id);
        return Inertia::render($this->source . 'transactions/purchase/create', [
            'title' => 'Edit Journal | Jurnalin',
            'additional' => [
                'product_options' => $this->getProductOptions->handle(),
                'account_options' => $this->getAccountOptions->handle(),
                'supplier_options' => $this->getSupplierOptions->handle(),
                'data' => $data
            ]
        ]);
    }

    public function updateData($id, UpdatePurchaseRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->purchaseService->updateData($id, $request);
            $result = new SubmitDefaultResource($data, 'Success update journal');
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
            $data = $this->purchaseService->destroy($id);
            $result = new SubmitDefaultResource($data, 'Success delete purchase');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
