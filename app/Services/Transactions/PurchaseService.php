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
        $inputs = $request->only(['no_transaction', 'date', 'account_id', 'supplier_id', 'description', 'purchase_details']);
        $inputs['pay_with_account_id'] = $inputs['account_id'];

        // if no transaction is empty, generate no transaction
        if (empty($inputs['no_transaction'])) {
            $inputs['no_transaction'] = 'PURCHASE-' . rand(100000, 999999);
        }

        $purchase_details = $request->purchase_details;

        $purchase = Purchase::create($inputs);

        foreach ($purchase_details as $purchase_detail) {
            $data = [
                'purchase_id' => $purchase->id,
                'product_id' => $purchase_detail['product_id'],
                'quantity' => $purchase_detail['quantity'],
                'price_per_unit' => $purchase_detail['price_per_unit'],
                'total_price' => $purchase_detail['quantity'] * $purchase_detail['price_per_unit']
            ];

            $purchase->purchase_details()->create($data);
        }
    }

    public function updateData($id, $request)
    {
        $inputs = $request->only(['no_transaction', 'date', 'account_id', 'supplier_id', 'description', 'purchase_details']);
        $inputs['pay_with_account_id'] = $inputs['account_id'];

        // if no transaction is empty, generate no transaction
        if (empty($inputs['no_transaction'])) {
            $inputs['no_transaction'] = 'PURCHASE-' . rand(100000, 999999);
        }

        $purchase_details = $request->purchase_details;

        $purchase = Purchase::findOrFail($id);

        $purchase->update($inputs);

        $purchase->purchase_details()->delete();

        foreach ($purchase_details as $purchase_detail) {
            $data = [
                'purchase_id' => $purchase->id,
                'product_id' => $purchase_detail['product_id'],
                'quantity' => $purchase_detail['quantity'],
                'price_per_unit' => $purchase_detail['price_per_unit'],
                'total_price' => $purchase_detail['quantity'] * $purchase_detail['price_per_unit']
            ];

            $purchase->purchase_details()->create($data);
        }

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
