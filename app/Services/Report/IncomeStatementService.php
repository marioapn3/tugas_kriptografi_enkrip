<?php

namespace App\Services\Report;

use App\Models\Account;

class IncomeStatementService
{
    private $totalIncome;
    private $totalExpense;
    public function getData($request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $query = Account::with(['AccountCategory', 'journalDetails']);

        $query->when(request('start_date', false) && request('end_date', false), function ($q) use ($start_date, $end_date) {
            $q->whereHas('journalDetails.journal', function ($subQuery) use ($start_date, $end_date) {
                $subQuery->whereBetween('date', [$start_date, $end_date]);
            });
        });

        $query->whereHas('AccountCategory.classification', function ($subQuery) {
            $subQuery->whereIn('id', [4, 5]);
        });

        return $query->paginate(10);
    }
    public function getPdfData($start_date, $end_date)
    {
        $this->totalIncome = 0;
        $this->totalExpense = 0;
        $accounts = Account::with('journalDetails')->whereHas('journalDetails.journal', function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        })->get();

        // Filter the accounts based on your criteria (e.g., AccountCategory classification).
        $filteredAccounts = $accounts->filter(function ($account) {
            return in_array($account->AccountCategory->classification->id, [4, 5]);
        });



        $result = [];

        foreach ($filteredAccounts as $account) {
            $journalEntries = $account->journalDetails;

            if ($start_date && $end_date) {
                $journalEntries = $journalEntries->filter(function ($journal_detail) use ($start_date, $end_date) {
                    $journalDate = $journal_detail->journal->date;
                    return $journalDate >= $start_date && $journalDate <= $end_date;
                });
            }

            $totalAmount = $journalEntries->reduce(function ($carry, $journal_detail) use ($account) {
                if ($account->AccountCategory->classification->debit_or_credit === 'debit') {
                    $amount = $journal_detail->debit - $journal_detail->credit;
                    $this->totalExpense += $amount;
                } elseif ($account->AccountCategory->classification->debit_or_credit === 'credit') {
                    $amount = $journal_detail->credit - $journal_detail->debit;
                    $this->totalIncome += $amount;
                }

                return $carry + $amount;
            }, 0);

            $result[] = [
                'id' => $account->id,
                'name' => $account->name,
                'code' => $account->code,
                'description' => $account->description,
                'expense' => number_format($account->AccountCategory->classification->debit_or_credit === 'debit' ? $totalAmount : 0),
                'income' => number_format($account->AccountCategory->classification->debit_or_credit === 'credit' ? $totalAmount : 0),
            ];
        }

        return [
            'accounts' => $result,
            'totalIncome' => number_format($this->totalIncome),
            'totalExpense' => number_format($this->totalExpense),
        ];
    }
}
