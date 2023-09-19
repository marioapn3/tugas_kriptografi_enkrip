<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Journal extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $with = ['journal_details'];

    public function journal_details()
    {
        return $this->hasMany(JournalDetail::class, 'journal_id', 'id');
    }
}
