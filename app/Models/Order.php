<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_number', 'total_amount', 'discount_amount', 'tax_amount',
        'shipping_amount', 'final_amount', 'payment_method', 'payment_status',
        'order_status', 'shipping_address_id', 'billing_address_id',
        'prescription_path', 'prescription_status', 'prescription_remarks'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function shippingAddress() { return $this->belongsTo(Address::class, 'shipping_address_id'); }
    public function billingAddress() { return $this->belongsTo(Address::class, 'billing_address_id'); }
}