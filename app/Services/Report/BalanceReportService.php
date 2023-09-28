<?php

namespace App\Services\Report;

use App\Models\Account;

class BalanceReportService
{
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
}
