<?php

namespace App\Services\Storages;

use App\Models\Product;

class ProductService
{
    public function generateCode()
    {
        $product = Product::orderBy('code', 'desc')->first();

        if ($product) {
            $code = (int) substr($product->code, 5) + 1;
        } else {
            $code = 1;
        }

        return [
            'code' => "ITEM-" . $code
        ];
    }


    public function getData($request)
    {
        $search = $request->search;

        $query = Product::query();

        $query->when(request('search', false), function ($q) use ($search) {
            $q->where('code', 'like', '%' . $search . '%')->orWhere('name', 'like', '%' . $search . '%');
        });

        return $query->paginate(10);
    }
}
