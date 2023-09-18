<?php

namespace App\Actions\Options;

use App\Models\Account;

class GetAccountOptions
{
    public function handle()
    {
        $account = Account::all();

        $new_account = [];
        foreach ($account as $data) {
            $new_account[$data->id] = $data->code . ' - ' . $data->name;
        }

        return $new_account;
    }
}
