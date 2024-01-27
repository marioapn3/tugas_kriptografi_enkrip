<?php

namespace App\Services\Report;

use App\Models\Account;

class BalanceReportService
{
    private $totalCredit;
    private $totalDebit;
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

        return $query->paginate(10);
    }
    public function getPdfData($start_date, $end_date)
    {
        $accounts = Account::with('journalDetails')->whereHas('journalDetails.journal', function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        })->get();
        $result = [];

        foreach ($accounts as $account) {
            $journalEntries = $account->journalDetails;
            if ($start_date && $end_date) {

                $journalEntries = $journalEntries->filter(function ($journal_detail) use ($start_date, $end_date) {
                    $journalDate = $journal_detail->journal->date;
                    return $journalDate >= $start_date && $journalDate <= $end_date;
                });
            }

            $debit = $journalEntries->reduce(function ($carry, $journal_detail) use ($account) {
                $debit = $journal_detail->debit;
                return $carry + $debit;
            }, 0);

            $credit = $journalEntries->reduce(function ($carry, $journal_detail) use ($account) {
                $credit = $journal_detail->credit;
                return $carry + $credit;
            }, 0);

            $this->totalDebit += $debit;
            $this->totalCredit += $credit;

            $result[] =  [
                'id' => $account->id,
                'name' => $account->name,
                'code' => $account->code,
                'description' => $account->description,
                'debit' => number_format($debit),
                'credit' => number_format($credit),
            ];
        }

        return [
            'accounts' => $result,
            'totalDebit' => number_format($this->totalDebit),
            'totalCredit' => number_format($this->totalCredit),
        ];
    }
    // public function getPdfData($start_date, $end_date)
    // {
    //     $accounts = Account::with('journalDetails.journal')
    //         ->whereHas('journalDetails.journal', function ($query) use ($start_date, $end_date) {
    //             $query->whereBetween('date', [$start_date, $end_date]);
    //         })
    //         ->get();

    //     $result = [];
    //     $totalDebit = 0;
    //     $totalCredit = 0;

    //     foreach ($accounts as $account) {
    //         $journalEntries = $account->journalDetails;

    //         $debit = $journalEntries->sum('debit');
    //         $credit = $journalEntries->sum('credit');

    //         $result[] = [
    //             'id' => $account->id,
    //             'name' => $account->name,
    //             'code' => $account->code,
    //             'description' => $account->description,
    //             'debit' => number_format($debit),
    //             'credit' => number_format($credit),
    //             'totalDebit' => number_format($debit),
    //             'totalCredit' => number_format($credit),
    //         ];

    //         $totalDebit += $debit;
    //         $totalCredit += $credit;
    //     }

    //     return [
    //         'accounts' => $result,
    //         'total_debit' => number_format($totalDebit),
    //         'total_credit' => number_format($totalCredit)
    //     ];
    // }
}
