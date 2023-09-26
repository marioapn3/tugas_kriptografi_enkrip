<?php

namespace App\Actions\Options;

use App\Models\Account;

class GetAccountOptions
{
    /**
     * Get account options
     * params classification value is array
    */
    public function handle($classification = null)
    {
        $account = Account::query();

        if ($classification) {
            $account->whereHas('AccountCategory', function ($q) use ($classification) {
                $q->whereHas('classification', function ($q) use ($classification) {
                    $q->whereIn('name', $classification);
                });
            });
        }

        $new_account = [];
        foreach ($account->get() as $data) {
            $new_account[$data->id] = $data->code . ' - ' . $data->name;
        }

        return $new_account;
    }
}
