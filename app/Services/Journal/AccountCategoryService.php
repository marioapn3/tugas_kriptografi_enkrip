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
            $q->where('code', 'like', '%' . $search . '%')->orWhere('name', 'like', '%' . $search . '%');
        });

        return $query->paginate(10);
    }

    public function generateCode($id_classification)
    {
        $classification = AccountCategory::where('classification_id', $id_classification)->orderBy('code', 'desc')->first();
        if ($classification) {
            $code = (int) substr($classification->code, 2) + 1;
        } else {
            $code = 1;
        }

        return [
            'code' => $classification->classification_id . "-" . $code
        ];
    }


    public function createData($request)
    {
        $data = $request->only([
            'name',
            'code',
            'classification_id'
        ]);

        $account_category = AccountCategory::create($data);

        return $account_category;
    }

    public function updateData($account_category, $request)
    {

        $data = $request->only([
            'name',
            'code',
            'classification_id'
        ]);


        $account_category = AccountCategory::findOrFail($account_category->id);
        $account_category->update($data);

        return $account_category;
    }

    public function deleteData($id)
    {
        $account_category = AccountCategory::findOrFail($id);
        $account_category->delete();

        return $account_category;
    }
}
