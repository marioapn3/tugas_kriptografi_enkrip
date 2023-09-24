<?php

namespace App\Services\Transactions;

use App\Models\Product;
use App\Models\Sale;

class SellingService
{
    public function getData($request)
    {
        $search = $request->search;

        $query = Sale::with(['customer', 'deposit_to_account'])->orderBy('id', 'desc');

        $query->when(request('search', false), function ($q) use ($search) {
            $q->where('no_transaction', 'like', '%' . $search . '%');
        });

        return $query->paginate(10);
    }

    public function getProduct($id)
    {
        $product = Product::find($id);

        return $product;
    }

    public function getDataById($id)
    {
        $sale = Sale::with(['customer', 'deposit_to_account', 'sale_details'])->find($id);

        return $sale;
    }

    public function store($request)
    {
        $inputs = [
            'no_transaction' => $request->no_transaction,
            'date' => $request->date,
            'description' => $request->description,
            'customer_id' => $request->customer_id,
            'deposit_to_account_id' => $request->account_id,
        ];

        // if no transaction is empty, generate no transaction
        if (empty($inputs['no_transaction'])) {
            $inputs['no_transaction'] = 'SALE-' . rand(100000, 999999);
        }

        $product_entries = $request->product_entries;

        $sale = Sale::create($inputs);

        foreach ($product_entries as $product_entry) {
            $data = [
                'sale_id' => $sale->id,
                'product_id' => $product_entry['product_id'],
                'qty' => $product_entry['qty'],
                'price' => $product_entry['price'],
            ];
            $sale->sale_details()->create($data);
        }

        return $sale;
    }

    public function update($id, $request)
    {
        $inputs = [
            'no_transaction' => $request->no_transaction,
            'date' => $request->date,
            'description' => $request->description,
            'customer_id' => $request->customer_id,
            'deposit_to_account_id' => $request->account_id,
        ];

        // if no transaction is empty, generate no transaction
        if (empty($inputs['no_transaction'])) {
            $inputs['no_transaction'] = 'SALE-' . rand(100000, 999999);
        }

        $product_entries = $request->product_entries;

        $sale = Sale::find($id);

        $sale->update($inputs);

        $sale->sale_details()->delete();

        foreach ($product_entries as $product_entry) {
            $data = [
                'sale_id' => $sale->id,
                'product_id' => $product_entry['product_id'],
                'qty' => $product_entry['qty'],
                'price' => $product_entry['price'],
            ];
            $sale->sale_details()->create($data);
        }

        return $sale;
    }

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);

        $sale->delete();

        return $sale;
    }
}
