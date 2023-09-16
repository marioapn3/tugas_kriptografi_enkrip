<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classification extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get all of the comments for the Classification
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function account_categories(): HasMany
    {
        return $this->hasMany(AccountCategory::class, 'classification_id', 'id');
    }
}
