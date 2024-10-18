<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'products';

    // Especifica los campos que se pueden llenar masivamente
    protected $fillable = ['name', 'descrition', 'price', 'stock', 'user_id', 'image']; 

    public function product()
    {
        return $this->belongsTo(User::class);
    }

    public function cartProduct():HasMany
    {
        return $this->hasMany(CartProduct::class);
    }
}

