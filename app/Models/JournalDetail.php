<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalDetail extends Model
{
    use
        HasFactory,SoftDeletes;

    protected $guarded = ['id'];

    protected $with = ['account'];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }
}
