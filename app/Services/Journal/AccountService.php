<?php

namespace App\Services\Journal;

use App\Models\Account;
use App\Models\AccountCategory;

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

    public function createData($request)
    {
        $data = $request->only([
            'name',
            'code',
            'account_category_id'
        ]);

        $account = Account::create($data);

        return $account;
    }

    public function updateData($account, $request)
    {

        $data = $request->only([
            'name',
            'code',
            'account_category_id'
        ]);


        $account = Account::findOrFail($account->id);
        $account->update($data);

        return $account;
    }

    public function deleteData($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        return $account;
    }
    public function generateCode($account_category_id)
    {
        $account = Account::where('account_category_id', $account_category_id)->first();

        if ($account) {
            // Jika akun ditemukan, ekstrak angka dari kode dan tambahkan 1
            $code = $account->code;
            $matches = [];
            if (preg_match('/(\d+)$/', $code, $matches)) {
                $number = (int)$matches[1];
                $number++;
                $newCode = preg_replace('/(\d+)$/', str_pad($number, strlen($matches[1]), '0', STR_PAD_LEFT), $code);
                return ['code' => $newCode];
            }
        } else {
            // Jika akun tidak ditemukan, cari kategori akun dan tambahkan "001"
            $category = AccountCategory::find($account_category_id);
            if ($category) {
                $newCode = $category->code . "001";
                return ['code' => $newCode];
            }
        }

        // Jika ada kesalahan atau kondisi tidak terpenuhi, kembalikan null atau pesan kesalahan.
        return null;
    }
}
