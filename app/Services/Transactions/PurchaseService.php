<?php

namespace App\Services\Transactions;

use App\Models\Journal;
use App\Models\Product;
use App\Models\Purchase;

class PurchaseService
{
    public function getData($request)
    {
        $search = $request->search;
        $query = Purchase::query();

        $query->when(request('search', false), function ($q) use ($search) {
            $q->where('no_transaction', 'like', '%' . $search . '%');
        });

        return $query->paginate(10);
    }

    public function createData($request)
    {
        $purchaseTotal = 0;
        $inputs = $request->only(['no_transaction', 'date', 'description']);
        $request_all = $request->all();
        // if no transaction is empty, generate no transaction
        if (empty($inputs['no_transaction'])) {
            $inputs['no_transaction'] = 'J-' . rand(100000, 999999);
        }

        $purchase_details = $request->purchase_details;

        $journal = Journal::create($inputs);

        $purchase = Purchase::create(
            [
                'date' => $inputs['date'],
                'no_purchase' => 'P' . $inputs['no_transaction'],
                'supplier_id' => $request_all['supplier_id'],
                'description' => $inputs['description'],
                'journal_id' => $journal->id,
            ]
        );

        foreach ($purchase_details as $purchase_detail) {
            $product = Product::findOrFail($purchase_detail['product_id'])->first();
            $data = [
                'purchase_id' => $purchase->id,
                'product_id' => $purchase_detail['product_id'],
                'quantity' => $purchase_detail['quantity'],
                'price_per_unit' => $purchase_detail['price_per_unit'],
                'total_price' => $purchase_detail['quantity'] * $purchase_detail['price_per_unit']
            ];

            $purchase->purchase_details()->create($data);

            $product->update([
                'stock' => $product->stock + $purchase_detail['quantity']
            ]);

            $purchaseTotal += ($purchase_detail['price_per_unit'] * $purchase_detail['quantity']);
        }

        $data2 = [
            'jornal_id' => $journal->id,
            'account_id' => $product->inventoryAccount->id,
            'debit' => $purchaseTotal,
            'credit' => 0,
        ];

        $data3 = [
            'jornal_id' => $journal->id,
            'account_id' => $request_all['account_id'],
            'debit' => 0,
            'credit' => $purchaseTotal,
        ];

        $journal->journalDetails()->create($data2);
        $journal->journalDetails()->create($data3);
        return $purchase;
    }

    public function updateData($id, $request)
    {
        $purchaseTotal = 0;
        $inputs = $request->only(['no_transaction', 'date', 'description']);
        $request_all = $request->all();
        // if no transaction is empty, generate no transaction
        if (empty($inputs['no_transaction'])) {
            $inputs['no_transaction'] = 'J-' . rand(100000, 999999);
        }

        $purchase_details = $request->purchase_details;

        $purchase = Purchase::findOrFail($id);
        $journal = Journal::findOrFail($purchase->id);
        foreach ($purchase_details as $purchase_detail) {
            $product = Product::findOrFail($purchase_detail['product_id'])->first();
            $data = [
                'purchase_id' => $purchase->id,
                'product_id' => $purchase_detail['product_id'],
                'quantity' => $purchase_detail['quantity'],
                'price_per_unit' => $purchase_detail['price_per_unit'],
                'total_price' => $purchase_detail['quantity'] * $purchase_detail['price_per_unit']
            ];

            $purchase->purchase_details()->create($data);

            $product->update([
                'stock' => $product->stock + $purchase_detail['quantity']
            ]);

            $purchaseTotal += ($purchase_detail['price_per_unit'] * $purchase_detail['quantity']);
        }

        $data2 = [
            'jornal_id' => $journal->id,
            'account_id' => $product->inventoryAccount->id,
            'debit' => $purchaseTotal,
            'credit' => 0,
        ];

        $data3 = [
            'jornal_id' => $journal->id,
            'account_id' => $request_all['account_id'],
            'debit' => 0,
            'credit' => $purchaseTotal,
        ];

        $journal->journalDetails()->create($data2);
        $journal->journalDetails()->create($data3);
        return $purchase;
    }

    public function getDataById($id)
    {
        return Purchase::findOrFail($id);
    }

    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);
        $journal = Journal::findOrFail($purchase->id);
        $purchase->delete();
        $journal->delete();
        return $purchase;
    }
}
