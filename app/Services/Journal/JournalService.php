<?php

namespace App\Services\Journal;

use App\Models\Journal;

class JournalService
{
    public function getData($request)
    {
        $search = $request->search;
        $query = Journal::query();

        $query->when(request('search', false), function ($q) use ($search) {
            $q->where('no_transaction', 'like', '%' . $search . '%');
        });

        return $query->paginate(10);
    }

    public function createData($request)
    {
        $inputs = $request->only(['no_transaction', 'date', 'description']);

        // if no transaction is empty, generate no transaction
        if (empty($inputs['no_transaction'])) {
            $inputs['no_transaction'] = 'J-' . rand(100000, 999999);
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
            $journal->journalDetails()->create($data);
        }

        return $journal;
    }

    public function destroy($id)
    {
        $journal = Journal::findOrFail($id);
        $journal->delete();

        return $journal;
    }
}
