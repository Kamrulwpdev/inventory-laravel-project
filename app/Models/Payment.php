<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Add this line to allow these fields to be saved during payment
    protected $fillable = ['customer_id', 'amount', 'method'];

    // Also add this relationship so the History table can show the customer name
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}