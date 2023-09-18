<?php

namespace App\Http\Controllers\Storages;

use App\Http\Controllers\AdminBaseController;
use App\Http\Resources\Storages\Product\ProductListResource;
use App\Services\Storages\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends AdminBaseController
{
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function productIndex()
    {
        return Inertia::render($this->source . 'storages/product/index', [
            "title" => 'Product | Jurnalin',
        ]);
    }

    public function generateCode()
    {
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
}
