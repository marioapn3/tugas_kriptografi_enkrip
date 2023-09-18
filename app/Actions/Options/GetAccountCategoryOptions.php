<?php

namespace App\Actions\Options;

use App\Models\AccountCategory;
use App\Models\Classification;


class GetAccountCategoryOptions
{
    public function handle()
    {
        $account_category = AccountCategory::all();

        $new_account_category = [];
        foreach ($account_category as $data) {
            $new_account_category[$data->id] = $data->code . ' ' . $data->name;
        }

        return $new_account_category;
    }
}
