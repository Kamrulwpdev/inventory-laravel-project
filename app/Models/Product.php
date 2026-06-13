<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// REMOVE THIS: use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    // REMOVE THIS: use SoftDeletes;

 
    protected $fillable = [
    'name', 'slug', 'barcode', 'category_id', 'alert_threshold', 
    'price', 'stock', 'sku', 'tags', 'description', 'image'
];
    

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    

public function getRouteKeyName()
{
    return 'slug';
}

public function getImageUrlAttribute()
{
    if (!$this->image) return asset('no-image.png'); // Add a placeholder image in public/
    
    if (\Illuminate\Support\Str::startsWith($this->image, 'image/')) {
        return asset($this->image);
    }
    
    return asset('storage/' . $this->image);
}
}