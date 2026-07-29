<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'value', 'min_cart_value', 'expiry_date', 'usage_limit', 'times_used'
    ];
    
    protected $casts = [
        'expiry_date' => 'date',
    ];
}