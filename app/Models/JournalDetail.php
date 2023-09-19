<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['journal'];

    public function journal()
    {
        return $this->belongsTo(Journal::class, 'journal_id', 'id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }
}
