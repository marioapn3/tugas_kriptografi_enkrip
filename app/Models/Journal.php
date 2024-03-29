<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Journal extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $with = ['journalDetails'];

    public function journalDetails()
    {
        return $this->hasMany(JournalDetail::class);
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    public function sales()
    {
        return $this->hasOne(Sale::class);
    }

    public function expense()
    {
        return $this->hasOne(Expense::class);
    }
}
