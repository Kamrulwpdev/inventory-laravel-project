<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    // This allows Purchase::create() to work with these specific fields
    protected $fillable = ['product_id', 'quantity', 'purchase_price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}