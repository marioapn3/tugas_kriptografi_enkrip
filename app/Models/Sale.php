<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $with = ['sale_details'];

    public function customer()
    {
        return $this->belongsTo(Contact::class, 'customer_id', 'id');
    }

    public function deposit_to_account()
    {
        return $this->belongsTo(Account::class, 'deposit_to_account_id', 'id');
    }

    public function sale_details()
    {
        return $this->hasMany(SaleDetail::class, 'sale_id', 'id');
    }
}
