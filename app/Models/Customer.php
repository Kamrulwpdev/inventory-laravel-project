<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Add this line to allow these fields to be saved
    protected $fillable = ['name', 'phone', 'credit_limit', 'current_balance'];
}