<?php

namespace App\Services\Storages;

use App\Models\Product;
use App\Services\FileService;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function generateCode()
    {
        $product = Product::orderBy('code', 'desc')->withTrashed()->first();

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

    public function getDataPos($request)
    {
        $search = $request->search;

        $query = Product::query();

        $query->when(request('search', false), function ($q) use ($search) {
            $q->where('code', 'like', '%' . $search . '%')->orWhere('name', 'like', '%' . $search . '%');
        });

        // return all product without pagination
        return $query->get();
    }

    public function getDetail($id)
    {
        $product = Product::with(['purchaseAccount', 'saleAccount', 'inventoryAccount'])->findOrFail($id);

        // dd($product);
        return $product;
    }

    public function getDetailWithTransaction($id)
    {
        $product = Product::with(['purchaseAccount', 'saleAccount', 'inventoryAccount', 'productStock.journal.purchase', 'productStock.journal.purchase.purchase_details' => function ($query) use ($id) {
            $query->where('product_id', $id);
        }, 'productStock.journal.sales'])
            ->findOrFail($id);

        // dd($product);
        return $product;
    }

    public function createData($request)
    {
        $fileService = new FileService();

        $inputs = $request->only([
            'code',
            'name',
            'description',
            'purchase_price',
            'sale_price',
            'purchase_account',
            'sale_account',
            'inventory_account',
            'image',
            'stock'
        ]);

        if ($request->hasFile('image')) {
            $image = $fileService->uploadFile($request->file('image'), 'product/image');
            $inputs['image'] = $image;
        }


        $product = Product::create($inputs);

        return $product;
    }

    public function updateData($id, $request)
    {
        $product = Product::findOrFail($id);
        $fileService = new FileService();

        $inputs = $request->only([
            'code',
            'name',
            'description',
            'purchase_price',
            'sale_price',
            'purchase_account',
            'sale_account',
            'inventory_account',
            'image',
            'stock'
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::delete('public' . substr($product->image, 8));
            }

            $image = $fileService->uploadFile($request->file('image'), 'product/image');
            $inputs['image'] = $image;
        }

        $product = $product->update($inputs);

        return $product;
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            Storage::delete('public' . substr($product->image, 8));
        }

        $product->delete();

        return $product;
    }
}
