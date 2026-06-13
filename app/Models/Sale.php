<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    // In app/Models/Sale.php

protected $fillable = [
    'total_amount',
    'manual_discount_amount',     // Add this
    'redemption_discount_amount', // Add this
    'user_id',
    'customer_id',
    'payment_status',
];

    // ADD THIS RELATIONSHIP
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Ensure you also have this for the receipt items
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
