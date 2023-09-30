<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['AccountCategory'];

    /**
     * Get the user that owns the Account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function AccountCategory(): BelongsTo
    {
        return $this->belongsTo(AccountCategory::class, 'account_category_id', 'id');
    }


    public function journalDetails(): HasMany
    {
        return $this->hasMany(JournalDetail::class, 'account_id', 'id');
    }

    public function getTotalAmount()
    {
        $classification = $this->AccountCategory->classification->debit_or_credit;
        $total = 0;
        foreach ($this->journalDetails as $detail) {
            $amount = 0;

            if ($classification === 'debit') {
                $amount = $detail->debit - $detail->credit;
            } elseif ($classification === 'credit') {
                $amount = $detail->credit - $detail->debit;
            }

            $total += $amount;
        }

        return $total;
    }
}
