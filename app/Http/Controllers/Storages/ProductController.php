<?php

namespace App\Http\Controllers\Storages;

use App\Actions\Options\GetAccountOptions;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Storages\Product\CreateProductRequest;
use App\Http\Requests\Storages\Product\UpdateProductRequest;
use App\Http\Resources\Storages\Product\ProductCodeResource;
use App\Http\Resources\Storages\Product\ProductListResource;
use App\Http\Resources\SubmitDefaultResource;
use App\Services\Storages\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends AdminBaseController
{
    public function __construct(ProductService $productService, GetAccountOptions $getAccountOptions)
    {
        $this->productService = $productService;
        $this->getAccountOptions = $getAccountOptions;
    }

    public function productIndex()
    {
        return Inertia::render($this->source . 'storages/product/index', [
            "title" => 'Product | Jurnalin',
            'additional' => [
                'account_options' => $this->getAccountOptions->handle()
            ],
        ]);
    }

    public function generateCode()
    {
        try {
            $data = $this->productService->generateCode();
            $result = new ProductCodeResource($data, 'Success Generate Code');

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function getData(Request $request)
    {
        try {
            $data = $this->productService->getData($request);

            $result = new ProductListResource($data);

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function createData(CreateProductRequest $request)
    {
        try {
            $data = $this->productService->createData($request);
            $result = new SubmitDefaultResource($data, 'Success Create Product');

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function updateData($id, UpdateProductRequest $request)
    {
        try {
            $data = $this->productService->updateData($id, $request);
            $result = new SubmitDefaultResource($data, 'Success Update Product');

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function deleteData($id)
    {
        try {
            $data = $this->productService->destroy($id);
            $result = new SubmitDefaultResource($data, 'Success Delete Product');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
