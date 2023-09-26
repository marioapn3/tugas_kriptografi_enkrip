<?php

namespace App\Services\Transactions;

use App\Models\Account;
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
            $inputs['no_transaction'] = rand(100000, 999999);
        }

        $purchase_details = $request->purchase_details;


        // journal entry
        $journal = Journal::create([
            'no_transaction' => 'J-' . $inputs['no_transaction'],
            'date' => $inputs['date'],
            'description' => $inputs['description'] ?? null,
        ]);

        // get total prices
        $total_prices = 0;
        foreach ($purchase_details as $purchase_entry) {
            $total_prices += $purchase_entry['quantity'] * $purchase_entry['price_per_unit'];
        }

        // check pay_with_account_id is debit or credit
        $debit_or_credit = Account::findOrFail($inputs['pay_with_account_id'])->AccountCategory->classification->debit_or_credit;

        // collect data journal details
        $journal_data = [
            // the amount in the deposit account decreases
            [
                'journal_id' => $journal->id,
                'account_id' => $inputs['pay_with_account_id'],
                'debit' => $debit_or_credit == 'debit' ? 0 : $total_prices,
                'credit' => $debit_or_credit == 'credit' ? 0 : $total_prices,
            ],
        ];

        $purchase = Purchase::create([
            'no_transaction' => 'PURCHASE-' . $inputs['no_transaction'],
            'journal_id' => $journal->id,
            'pay_with_account_id' => $inputs['pay_with_account_id'],
            'supplier_id' => $inputs['supplier_id'],
            'date' => $inputs['date'],
            'description' => $inputs['description'] ?? null,
        ]);

        $journal_data_details = [];
        foreach ($purchase_details as $purchase_detail) {
            $product = Product::findOrFail($purchase_detail['product_id']);

            $purchase->purchase_details()->create([
                'purchase_id' => $purchase->id,
                'product_id' => $purchase_detail['product_id'],
                'quantity' => $purchase_detail['quantity'],
                'price_per_unit' => $purchase_detail['price_per_unit'],
                'total_price' => $purchase_detail['quantity'] * $purchase_detail['price_per_unit']
            ]);


            // add to journal details (inventory account)
            $journal_data_details[] = [
                'journal_id' => $journal->id,
                'account_id' => $product->inventory_account,
                'debit' => $product->inventory_debit_or_credit == 'debit' ? $purchase_detail['quantity'] * $purchase_detail['price_per_unit'] : 0,
                'credit' => $product->inventory_debit_or_credit == 'credit' ? $purchase_detail['quantity'] * $purchase_detail['price_per_unit'] : 0,
            ];
        }

        // update history stock (create)
        foreach ($purchase_details as $purchase_detail) {
            $product = Product::findOrFail($purchase_detail['product_id']);

            $product->productStock()->create([
                'product_id' => $product->id,
                'journal_id' => $journal->id,
                'quantity' => $purchase_detail['quantity'],
                'type' => 'in',
            ]);
        }

        // concat journal data and journal data details
        $journal_data = collect(array_merge($journal_data, $journal_data_details));

        // insert journal details
        $journal->journalDetails()->createMany($journal_data);

        $totalDebit = $journal_data->sum('debit');
        $totalCredit = $journal_data->sum('credit');

        return $purchase;
    }

    public function updateData($id, $request)
    {
        $purchase = Purchase::findOrFail($id);
        $inputs = $request->only(['no_transaction', 'date', 'account_id', 'supplier_id', 'description', 'purchase_details']);
        $inputs['pay_with_account_id'] = $inputs['account_id'];

        // if no transaction is empty, generate no transaction
        if (empty($inputs['no_transaction'])) {
            $inputs['no_transaction'] = rand(100000, 999999);
        }

        $purchase_details = $request->purchase_details;

        // update journal entry
        $journal = Journal::findOrFail($purchase->journal_id);
        $journal->update([
            'date' => $inputs['date'],
            'description' => $inputs['description'],
        ]);

        // get total prices
        $total_prices = 0;
        foreach ($purchase_details as $purchase_entry) {
            $total_prices += $purchase_entry['quantity'] * $purchase_entry['price_per_unit'];
        }

        // check pay_with_account_id is debit or credit
        $debit_or_credit = Account::findOrFail($inputs['pay_with_account_id'])->AccountCategory->classification->debit_or_credit;

        // collect data journal details
        $journal_data = [
            // the amount in the deposit account decreases
            [
                'journal_id' => $journal->id,
                'account_id' => $inputs['pay_with_account_id'],
                'debit' => $debit_or_credit == 'debit' ? 0 : $total_prices,
                'credit' => $debit_or_credit == 'credit' ? 0 : $total_prices,
            ],
        ];

        $purchase->update([
            'journal_id' => $journal->id,
            'pay_with_account_id' => $inputs['pay_with_account_id'],
            'supplier_id' => $inputs['supplier_id'],
            'date' => $inputs['date'],
            'description' => $inputs['description'] ?? null,
        ]);

        // delete old purchase details
        $purchase->purchase_details()->delete();

        $journal_data_details = [];
        foreach ($purchase_details as $purchase_detail) {
            $product = Product::findOrFail($purchase_detail['product_id']);

            $purchase->purchase_details()->create([
                'purchase_id' => $purchase->id,
                'product_id' => $purchase_detail['product_id'],
                'quantity' => $purchase_detail['quantity'],
                'price_per_unit' => $purchase_detail['price_per_unit'],
                'total_price' => $purchase_detail['quantity'] * $purchase_detail['price_per_unit']
            ]);


            // add to journal details (inventory account)
            $journal_data_details[] = [
                'journal_id' => $journal->id,
                'account_id' => $product->inventory_account,
                'debit' => $product->inventory_debit_or_credit == 'debit' ? $purchase_detail['quantity'] * $purchase_detail['price_per_unit'] : 0,
                'credit' => $product->inventory_debit_or_credit == 'credit' ? $purchase_detail['quantity'] * $purchase_detail['price_per_unit'] : 0,
            ];
        }

        // update history stock (create)
        foreach ($purchase_details as $purchase_detail) {
            $product = Product::findOrFail($purchase_detail['product_id']);

            // delete old product stock
            $product->productStock()->where('journal_id', $journal->id)->delete();

            $product->productStock()->create([
                'product_id' => $product->id,
                'journal_id' => $journal->id,
                'quantity' => $purchase_detail['quantity'],
                'type' => 'in',
            ]);
        }

        // delete old journal details
        $journal->journalDetails()->delete();
        
        // concat journal data and journal data details
        $journal_data = collect(array_merge($journal_data, $journal_data_details));

        // insert journal details
        $journal->journalDetails()->createMany($journal_data);

        $totalDebit = $journal_data->sum('debit');
        $totalCredit = $journal_data->sum('credit');

        return $purchase;
    }

    public function getDataById($id)
    {
        return Purchase::findOrFail($id);
    }

    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);
        $journal = Journal::findOrFail($purchase->journal_id);

        // delete stock product data where related with this journal
        foreach ($purchase->purchase_details as $purchase_detail) {
            $purchase_detail->product->productStock()->where('journal_id', $journal->id)->delete();
        }

        $purchase->delete();
        $journal->delete();
        return $purchase;
    }
}
