<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <--- ADD THIS LINE

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',    
        'customer_phone',   
        'customer_address', 
        'total_amount',
        'status',
        'items'
    ];

    protected $casts = [
        'items' => 'array',
    ];
    
    // Now this will work because BelongsTo is imported above
   public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    // Ensure this returns a proper relation
    return $this->belongsTo(\App\Models\User::class, 'user_id');
}
}
