<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $with = ['productStock'];

    public function productStock()
    {
        return $this->hasMany(ProductStock::class, 'product_id', 'id');
    }

    public function purchaseAccount()
    {
        return $this->belongsTo(Account::class, 'purchase_account', 'id');
    }

    public function saleAccount()
    {
        return $this->belongsTo(Account::class, 'sale_account', 'id');
    }

    public function inventoryAccount()
    {
        return $this->belongsTo(Account::class, 'inventory_account', 'id');
    }

    public function getPurchaseDebitOrCreditAttribute()
    {
        return $this->purchaseAccount()->first()->AccountCategory()->first()->classification->debit_or_credit;
    }

    public function getSaleDebitOrCreditAttribute()
    {
        return $this->saleAccount()->first()->AccountCategory()->first()->classification->debit_or_credit;
    }

    public function getInventoryDebitOrCreditAttribute()
    {
        return $this->inventoryAccount()->first()->AccountCategory()->first()->classification->debit_or_credit;
    }
}
