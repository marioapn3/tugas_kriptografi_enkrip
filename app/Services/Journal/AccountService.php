<?php

namespace App\Services\Journal;

use App\Models\Account;

class AccountService
{
    public function getData($request)
    {
        $search = $request->search;

        $query = Account::query();

        $query->when(request('search', false), function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        });

        return $query->paginate(10);
    }
}
