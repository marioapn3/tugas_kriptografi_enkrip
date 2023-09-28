<?php

namespace App\Services\Transactions;

use App\Models\Account;
use App\Models\Journal;
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
            'no_transaction' => $request->no_transaction ?? null,
            'date' => $request->date,
            'description' => $request->description,
            'customer_id' => $request->customer_id,
            'deposit_to_account_id' => $request->account_id,
        ];

        // if no transaction is empty, generate no transaction
        if (empty($inputs['no_transaction'])) {
            $inputs['no_transaction'] = rand(100000, 999999);
        }

        $product_entries = $request->product_entries;


        // journal entry
        $journal = Journal::create([
            'no_transaction' => 'J-' . $inputs['no_transaction'],
            'date' => $inputs['date'],
            'description' => $inputs['description'],
        ]);

        // get total prices
        $total_prices = 0;
        foreach ($product_entries as $product_entry) {
            $total_prices += $product_entry['qty'] * $product_entry['price'];
        }

        // check deposit_to_account_id is debit or credit
        $debit_or_credit = Account::findOrFail($inputs['deposit_to_account_id'])->AccountCategory->classification->debit_or_credit;

        // collect data journal details
        $journal_data = [
            // the amount in the deposit account increases 
            [
                'journal_id' => $journal->id,
                'account_id' => $inputs['deposit_to_account_id'],
                'debit' => $debit_or_credit == 'debit' ? $total_prices : 0,
                'credit' => $debit_or_credit == 'credit' ? $total_prices : 0,
            ],
        ];

        $sale = Sale::create(
            [
                'no_transaction' => 'SALE-' . $inputs['no_transaction'],
                'date' => $inputs['date'],
                'description' => $inputs['description'] ?? null,
                'customer_id' => $inputs['customer_id'],
                'deposit_to_account_id' => $inputs['deposit_to_account_id'],
                'journal_id' => $journal->id,
            ]
        );

        $journal_data_purchase = [];
        $journal_data_selling = [];
        $journal_data_inventory = [];
        foreach ($product_entries as $key => $product_entry) {
            $product = Product::findOrFail($product_entry['product_id']);

            $sale->sale_details()->create([
                'sale_id' => $sale->id,
                'product_id' => $product_entry['product_id'],
                'qty' => $product_entry['qty'],
                'price' => $product_entry['price'],
            ]);
            // add data journal details

            // purchase account increases (default debit)
            if (count($journal_data_purchase) > 0) {
                foreach ($journal_data_purchase as $key => $data) {
                    if ($data["account_id"] == $product->purchase_account) {

                        $journal_data_purchase[$key]["debit"] += $product->purchase_debit_or_credit == 'debit' ? (int) $product->purchase_price * (int) $product_entry['qty'] : 0;
                        $journal_data_purchase[$key]["credit"] += $product->purchase_debit_or_credit == 'credit' ? (int) $product->purchase_price * (int) $product_entry['qty'] : 0;

                        break;
                    } else {
                        $journal_data_purchase[] = [
                            'type' => 'purchase',
                            'journal_id' => $journal->id,
                            'account_id' => $product->purchase_account,
                            'debit' => $product->purchase_debit_or_credit == 'debit' ? (int) $product->purchase_price * (int) $product_entry['qty'] : 0,
                            'credit' => $product->purchase_debit_or_credit == 'credit' ? (int) $product->purchase_price * (int) $product_entry['qty'] : 0,
                        ];

                        break;
                    }
                }
            } else {
                $journal_data_purchase[] = [
                    'type' => 'purchase',
                    'journal_id' => $journal->id,
                    'account_id' => $product->purchase_account,
                    'debit' => $product->purchase_debit_or_credit == 'debit' ? (int) $product->purchase_price * (int) $product_entry['qty'] : 0,
                    'credit' => $product->purchase_debit_or_credit == 'credit' ? (int) $product->purchase_price * (int) $product_entry['qty'] : 0,
                ];
            }

            // sale account increases (default credit)
            if (count($journal_data_selling) > 0) {
                foreach ($journal_data_selling as $key => $data) {
                    if ($data["account_id"] == $product->sale_account) {

                        $journal_data_selling[$key]["debit"] += $product->sale_debit_or_credit == 'debit' ? (int) $product->sale_price * (int) $product_entry['qty'] : 0;
                        $journal_data_selling[$key]["credit"] += $product->sale_debit_or_credit == 'credit' ? (int) $product->sale_price * (int) $product_entry['qty'] : 0;

                        break;
                    } else {
                        $journal_data_selling[] = [
                            'type' => 'sale',
                            'journal_id' => $journal->id,
                            'account_id' => $product->sale_account,
                            'debit' => $product->sale_debit_or_credit == 'debit' ? (int) $product->sale_price * (int) $product_entry['qty'] : 0,
                            'credit' => $product->sale_debit_or_credit == 'credit' ? (int) $product->sale_price * (int) $product_entry['qty'] : 0,
                        ];

                        break;
                    }
                }
            } else {
                $journal_data_selling[] = [
                    'type' => 'sale',
                    'journal_id' => $journal->id,
                    'account_id' => $product->sale_account,
                    'debit' => $product->sale_debit_or_credit == 'debit' ? (int) $product->sale_price * (int) $product_entry['qty'] : 0,
                    'credit' => $product->sale_debit_or_credit == 'credit' ? (int) $product->sale_price * (int) $product_entry['qty'] : 0,
                ];
            }

            // inventory account increases (default credit)
            $journal_data_inventory[] = [
                'type' => 'inventory',
                'journal_id' => $journal->id,
                'account_id' => $product->inventory_account,
                'debit' => $product->inventory_debit_or_credit == 'debit' ? 0 : (int) $product->purchase_price * (int) $product_entry['qty'],
                'credit' => $product->inventory_debit_or_credit == 'credit' ? 0 : (int) $product->purchase_price * (int) $product_entry['qty'],
            ];
        }

        // update stock data
        foreach ($product_entries as $key => $product_entry) {
            $product = Product::findOrFail($product_entry['product_id']);

            $product->productStock()->create([
                'product_id' => $product->id,
                'journal_id' => $journal->id,
                'quantity' => $product_entry['qty'],
                'type' => 'out',
            ]);
        }


        // concat journal_data_purchase to journal_data
        $journal_data = collect(array_merge($journal_data, $journal_data_purchase, $journal_data_selling, $journal_data_inventory));

        // insert journal details
        $journal->journalDetails()->createMany($journal_data->toArray());

        $totalDebit = $journal_data->sum('debit');
        $totalCredit = $journal_data->sum('credit');


        return $sale;
    }

    public function update($id, $request)
    {
        $sale = Sale::findOrFail($id);

        $inputs = [
            'no_transaction' => $request->no_transaction,
            'date' => $request->date,
            'description' => $request->description,
            'customer_id' => $request->customer_id,
            'deposit_to_account_id' => $request->account_id,
        ];

        // if no transaction is empty, generate no transaction
        if (empty($inputs['no_transaction'])) {
            $inputs['no_transaction'] =  rand(100000, 999999);
        }

        $product_entries = $request->product_entries;

        // update journal entry
        $journal = Journal::findOrFail($sale->journal_id);
        $journal->update([
            'date' => $inputs['date'],
            'description' => $inputs['description'],
        ]);

        // get total prices
        $total_prices = 0;
        foreach ($product_entries as $product_entry) {
            $total_prices += $product_entry['qty'] * $product_entry['price'];
        }

        // check deposit_to_account_id is debit or credit
        $debit_or_credit = Account::findOrFail($inputs['deposit_to_account_id'])->AccountCategory->classification->debit_or_credit;

        // collect data journal details
        $journal_data = [
            // the amount in the deposit account increases 
            [
                'journal_id' => $journal->id,
                'account_id' => $inputs['deposit_to_account_id'],
                'debit' => $debit_or_credit == 'debit' ? $total_prices : 0,
                'credit' => $debit_or_credit == 'credit' ? $total_prices : 0,
            ],
        ];

        // update sale

        $sale->update(
            [
                'no_transaction' => $inputs['no_transaction'],
                'date' => $inputs['date'],
                'description' => $inputs['description'] ?? null,
                'customer_id' => $inputs['customer_id'],
                'deposit_to_account_id' => $inputs['deposit_to_account_id'],
                'journal_id' => $journal->id,
            ]
        );

        // delete old sale details
        $sale->sale_details()->delete();


        $journal_data_purchase = [];
        $journal_data_selling = [];
        $journal_data_inventory = [];

        foreach ($product_entries as $key => $product_entry) {
            $product = Product::findOrFail($product_entry['product_id']);

            $sale->sale_details()->create([
                'sale_id' => $sale->id,
                'product_id' => $product_entry['product_id'],
                'qty' => $product_entry['qty'],
                'price' => $product_entry['price'],
            ]);
            // add data journal details

            // purchase account increases (default debit)
            if (count($journal_data_purchase) > 0) {
                foreach ($journal_data_purchase as $key => $data) {
                    if ($data["account_id"] == $product->purchase_account) {

                        $journal_data_purchase[$key]["debit"] += $product->purchase_debit_or_credit == 'debit' ? (int) $product->purchase_price * (int) $product_entry['qty'] : 0;
                        $journal_data_purchase[$key]["credit"] += $product->purchase_debit_or_credit == 'credit' ? (int) $product->purchase_price * (int) $product_entry['qty'] : 0;

                        break;
                    } else {
                        $journal_data_purchase[] = [
                            'type' => 'purchase',
                            'journal_id' => $journal->id,
                            'account_id' => $product->purchase_account,
                            'debit' => $product->purchase_debit_or_credit == 'debit' ? (int) $product->purchase_price * (int) $product_entry['qty'] : 0,
                            'credit' => $product->purchase_debit_or_credit == 'credit' ? (int) $product->purchase_price * (int) $product_entry['qty'] : 0,
                        ];

                        break;
                    }
                }
            } else {
                $journal_data_purchase[] = [
                    'type' => 'purchase',
                    'journal_id' => $journal->id,
                    'account_id' => $product->purchase_account,
                    'debit' => $product->purchase_debit_or_credit == 'debit' ? (int) $product->purchase_price * (int) $product_entry['qty'] : 0,
                    'credit' => $product->purchase_debit_or_credit == 'credit' ? (int) $product->purchase_price * (int) $product_entry['qty'] : 0,
                ];
            }

            // sale account increases (default credit)
            if (count($journal_data_selling) > 0) {
                foreach ($journal_data_selling as $key => $data) {
                    if ($data["account_id"] == $product->sale_account) {

                        $journal_data_selling[$key]["debit"] += $product->sale_debit_or_credit == 'debit' ? (int) $product->sale_price * (int) $product_entry['qty'] : 0;
                        $journal_data_selling[$key]["credit"] += $product->sale_debit_or_credit == 'credit' ? (int) $product->sale_price * (int) $product_entry['qty'] : 0;

                        break;
                    } else {
                        $journal_data_selling[] = [
                            'type' => 'sale',
                            'journal_id' => $journal->id,
                            'account_id' => $product->sale_account,
                            'debit' => $product->sale_debit_or_credit == 'debit' ? (int) $product->sale_price * (int) $product_entry['qty'] : 0,
                            'credit' => $product->sale_debit_or_credit == 'credit' ? (int) $product->sale_price * (int) $product_entry['qty'] : 0,
                        ];

                        break;
                    }
                }
            } else {
                $journal_data_selling[] = [
                    'type' => 'sale',
                    'journal_id' => $journal->id,
                    'account_id' => $product->sale_account,
                    'debit' => $product->sale_debit_or_credit == 'debit' ? (int) $product->sale_price * (int) $product_entry['qty'] : 0,
                    'credit' => $product->sale_debit_or_credit == 'credit' ? (int) $product->sale_price * (int) $product_entry['qty'] : 0,
                ];
            }

            // inventory account increases (default credit)

            $journal_data_inventory[] = [
                'type' => 'inventory',
                'journal_id' => $journal->id,
                'account_id' => $product->inventory_account,
                'debit' => $product->inventory_debit_or_credit == 'debit' ? 0 : (int) $product->purchase_price * (int) $product_entry['qty'],
                'credit' => $product->inventory_debit_or_credit == 'credit' ? 0 : (int) $product->purchase_price * (int) $product_entry['qty'],
            ];

            // delete one stock data (old)
            $product->productStock()->where('journal_id', $journal->id)->delete();

            // add new stock data
            $product->productStock()->create([
                'product_id' => $product->id,
                'journal_id' => $journal->id,
                'quantity' => $product_entry['qty'],
                'type' => 'out',
            ]);
        }

        // delete old journal details
        $journal->journalDetails()->delete();

        // concat journal_data_purchase to journal_data
        $journal_data = collect(array_merge($journal_data, $journal_data_purchase, $journal_data_selling, $journal_data_inventory));

        // insert journal details
        $journal->journalDetails()->createMany($journal_data->toArray());

        $totalDebit = $journal_data->sum('debit');
        $totalCredit = $journal_data->sum('credit');


        return $sale;
    }

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);

        $journal = Journal::findOrFail($sale->journal_id);

        // delete stock product data where related with this journal
        foreach ($sale->sale_details as $key => $sale_detail) {
            $sale_detail->product->productStock()->where('journal_id', $journal->id)->delete();
        }

        // delete detail sale and journal
        $sale->sale_details()->delete();
        $journal->journalDetails()->delete();

        $journal->delete();
        $sale->delete();

        return $sale;
    }
}
