<?php

namespace App\Services\Journal;

use App\Models\Journal;

class JournalService
{
    public function createData($request)
    {
        $inputs = $request->only(['no_transaction', 'date', 'description']);

        // if no transaction is empty, generate no transaction
        if (empty($inputs['no_transaction'])) {
            $inputs['no_transaction'] = 'J-' . date('YmdHis') . rand(100, 999);
        }

        $journal_entries = $request->journal_entries;

        $journal = Journal::create($inputs);

        foreach ($journal_entries as $journal_entry) {
            $data = [
                'journal_id' => $journal->id,
                'account_id' => $journal_entry['account_id'],
                'debit' => $journal_entry['debit'],
                'credit' => $journal_entry['credit'],
                'description' => $journal_entry['description']
            ];
            $journal->journal_details()->create($data);
        }

        return $journal;
    }
}
