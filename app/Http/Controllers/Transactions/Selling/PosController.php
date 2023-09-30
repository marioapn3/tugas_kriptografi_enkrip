<?php

namespace App\Http\Controllers\Transactions\Selling;

use App\Http\Controllers\AdminBaseController;
use App\Http\Resources\Transactions\Selling\ProductPosListResource;
use App\Services\Storages\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PosController extends AdminBaseController
{
    public function posIndex()
    {
        return Inertia::render($this->source . 'transactions/selling/pos', [
            'title' => 'POS | Jurnalin',
        ]);
    }

    public function getProduct(Request $request)
    {
        $product = new ProductService();

        try {
            $data = $product->getDataPos($request);
            $result = new ProductPosListResource($data, 'Success get product list');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
