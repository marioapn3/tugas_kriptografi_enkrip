<?php

namespace App\Actions\Options;

use App\Models\Account;

class GetExpenseAccountOptions
{
    public function handle()
    {
        $accounts = Account::all();

        $new_account = [];

        foreach ($accounts as $account) {
            // Akses relasi AccountCategory pada setiap objek Account
            $accountCategory = $account->AccountCategory;

            // Pastikan relasi AccountCategory ada sebelum melanjutkan
            if ($accountCategory) {
                // Akses relasi classification pada setiap objek AccountCategory
                $classification = $accountCategory->classification;

                // Pastikan relasi classification ada sebelum melanjutkan
                if ($classification) {
                    // Cari objek dengan ID 5 dalam relasi classification
                    $classificationItem = $classification->findOrFail(5);

                    // Tambahkan ke array hasil jika ditemukan
                    if ($classificationItem) {
                        $new_account[$account->id] = $account->code . ' - ' . $account->name;
                    }
                }
            }
        }


        return $new_account;
    }
}
