<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the expense that owns the ExpenseDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class, 'expense_id', 'id');
    }

    /**
     * Get the expense_account that owns the ExpenseDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function expense_account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'expense_account', 'id');
    }
}
