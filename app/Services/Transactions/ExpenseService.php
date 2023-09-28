<?php

namespace App\Services\Transactions;

use App\Models\Expense;
use App\Models\Journal;
use App\Models\JournalDetail;

class ExpenseService
{
    public function getData($request)
    {
        $search = $request->search;
        $query = Expense::query();

        $query->when(request('search', false), function ($q) use ($search) {
            $q->where('no_transaction', 'like', '%' . $search . '%');
        });

        return $query->paginate(10);
    }

    public function store($request)
    {
        $inputs = [
            'no_transaction' => $request->no_transaction ?? null,
            'date' => $request->date,
            'description' => $request->description,
            'payment_account' => $request->payment_account,
        ];

        // if no transaction is empty, generate no transaction
        if (empty($inputs['no_transaction'])) {
            $inputs['no_transaction'] = rand(100000, 999999);
        }

        $expense_details = $request->expense_details;

        // journal entry
        $journal = Journal::create([
            'no_transaction' => 'J-' . $inputs['no_transaction'],
            'date' => $inputs['date'],
            'description' => $inputs['description'] == null ? null : $inputs['description'],
        ]);

        $expense =  Expense::create([
            'journal_id' => $journal->id,
            'no_transaction' => "EXPENSE-" .  $inputs['no_transaction'],
            'date' => $journal->date,
            'payment_account' => $inputs['payment_account'],
            'description' => $journal->description
        ]);

        foreach ($expense_details as $key => $expense_detail) {

            $expenseDetail = $expense->expense_details()->create([
                'expense_id' => $expense->id,
                'expense_account' => $expense_detail["expense_account"],
                'description' => $expense_detail["description"],
                'total_expense' => $expense_detail["total_expense"]
            ]);

            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $expense->payment_account,
                'debit' => 0,
                'credit' => $expenseDetail->total_expense,
                'description' => $expense->description,
            ]);
            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $expenseDetail->expense_account,
                'debit' => $expenseDetail->total_expense,
                'credit' => 0,
                'description' => $expense->description,
            ]);
        }



        return $expense;
    }

    public function getDataById($id)
    {
        $expense = Expense::with(['journal', 'expense_details'])->find($id);

        return $expense;
    }

    public function updateData($id, $request)
    {
        $inputs = [
            'no_transaction' => $request->no_transaction ?? null,
            'date' => $request->date,
            'description' => $request->description,
            'payment_account' => $request->payment_account,
        ];

        // if no transaction is empty, generate no transaction
        if (empty($inputs['no_transaction'])) {
            $inputs['no_transaction'] = rand(100000, 999999);
        }

        $expense_details = $request->expense_details;
        $expense = Expense::findOrFail($id);
        $expense->update($inputs);

        $journal = Journal::findOrFail($expense->journal_id);
        $journal->update(
            [
                'date' => $inputs['date'],
                'description' => $inputs['description'],
            ]

        );
        $expense->expense_details()->delete();
        $journal->journalDetails()->delete();
        foreach ($expense_details as $key => $expense_detail) {

            $expenseDetail = $expense->expense_details()->create([
                'expense_id' => $expense->id,
                'expense_account' => $expense_detail["expense_account"],
                'description' => $expense_detail["description"],
                'total_expense' => $expense_detail["total_expense"]
            ]);

            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $expense->payment_account,
                'debit' => 0,
                'credit' => $expenseDetail->total_expense,
                'description' => $expense->description,
            ]);
            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $expenseDetail->expense_account,
                'debit' => $expenseDetail->total_expense,
                'credit' => 0,
                'description' => $expense->description,
            ]);
        }



        return $expense;
    }
    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $journal = Journal::findOrFail($expense->journal_id);

        // delete detail expense and journal
        $expense->expense_details()->delete();
        $journal->journalDetails()->delete();

        $expense->delete();
        $journal->delete();

        return $expense;
    }
}
