<?php

namespace App\Services\Journal;

use App\Models\Account;
use App\Models\AccountCategory;

class AccountCategoryService
{
    public function getData($request)
    {
        $search = $request->search;

        $query = AccountCategory::query();

        $query->when(request('search', false), function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        });

        return $query->paginate(10);
    }


    public function createData($request)
    {
        $data = $request->only([
            'name',
            'code',
            'classification_id'
        ]);

        $contact = AccountCategory::create($data);

        return $contact;
    }

    public function updateData($account_category, $request)
    {

        $data = $request->only([
            'name',
            'code',
            'classification_id'
        ]);


        $contact = AccountCategory::findOrFail($account_category->id);
        $contact->update($data);

        return $contact;
    }

    public function deleteData($id)
    {
        $contact = AccountCategory::findOrFail($id);
        $contact->delete();

        return $contact;
    }
}
