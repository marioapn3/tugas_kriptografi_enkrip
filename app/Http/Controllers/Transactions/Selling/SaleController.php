<?php

namespace App\Http\Controllers\Transactions\Selling;

use App\Actions\Options\GetAccountOptions;
use App\Actions\Options\GetCustomerOptions;
use App\Actions\Options\GetProductOptions;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Transactions\Selling\CreateSaleRequest;
use App\Http\Requests\Transactions\Selling\UpdateSaleRequest;
use App\Http\Resources\SubmitDefaultResource;
use App\Http\Resources\Transactions\GetProductDetailResource;
use App\Http\Resources\Transactions\Selling\SaleListResource;
use App\Services\Storages\ProductService;
use App\Services\Transactions\SellingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SaleController extends AdminBaseController
{
    public function __construct(
        SellingService $sellingService,
        ProductService $productService,
        GetAccountOptions $getAccountOptions,
        GetCustomerOptions $getCustomerOptions,
        GetProductOptions $getProductOptions
    ) {
        $this->sellingService = $sellingService;
        $this->productService = $productService;
        $this->getAccountOptions = $getAccountOptions;
        $this->getCustomerOptions = $getCustomerOptions;
        $this->getProductOptions = $getProductOptions;
    }

    public function saleIndex()
    {
        return Inertia::render($this->source . 'transactions/selling/index', [
            'title' => 'Selling | Jurnalin'
        ]);
    }

    public function getData(Request $request)
    {
        try {
            $data = $this->sellingService->getData($request);
            $result = new SaleListResource($data);
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function create()
    {
        return Inertia::render($this->source . 'transactions/selling/create', [
            'title' => 'Create Sales | Jurnalin',
            'additional' => [
                'account_options' => $this->getAccountOptions->handle(),
                'customer_options' => $this->getCustomerOptions->handle(),
                'product_options' => $this->getProductOptions->handle()
            ]
        ]);
    }

    public function edit($id)
    {
        $data = $this->sellingService->getDataById($id);
        return Inertia::render($this->source . 'transactions/selling/create', [
            'title' => 'Create Sales | Jurnalin',
            'additional' => [
                'account_options' => $this->getAccountOptions->handle(),
                'customer_options' => $this->getCustomerOptions->handle(),
                'product_options' => $this->getProductOptions->handle(),
                'data' => $data
            ]
        ]);
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

    public function storeData(CreateSaleRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->sellingService->store($request);
            $result = new SubmitDefaultResource($data, 'Sale created');
            DB::commit();
            return $this->respond($result);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionError($e->getMessage());
        }
    }

    public function updateData($id, UpdateSaleRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->sellingService->update($id, $request);
            $result = new SubmitDefaultResource($data, 'Sale updated');
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
            $data = $this->sellingService->destroy($id);
            $result = new SubmitDefaultResource($data, 'Sale deleted');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
