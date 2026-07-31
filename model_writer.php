<?php
$dir = __DIR__ . '/app/Models';
$models = [
    'User' => <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected \$fillable = [
        'name', 'email', 'password', 'role', 'otp', 'otp_expires_at', 'email_verified_at'
    ];

    protected \$hidden = [
        'password', 'remember_token', 'otp'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_expires_at' => 'datetime',
        ];
    }
    
    public function addresses() { return \$this->hasMany(Address::class); }
    public function orders() { return \$this->hasMany(Order::class); }
    public function cart() { return \$this->hasOne(Cart::class); }
    public function wishlists() { return \$this->hasMany(Wishlist::class); }
    public function reviews() { return \$this->hasMany(Review::class); }
}
PHP,
    'Category' => <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected \$fillable = ['name', 'slug', 'description', 'image', 'status'];

    public function products() { return \$this->hasMany(Product::class); }
}
PHP,
    'Brand' => <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected \$fillable = ['name', 'slug', 'image', 'status'];

    public function products() { return \$this->hasMany(Product::class); }
}
PHP,
    'Product' => <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected \$fillable = [
        'category_id', 'brand_id', 'name', 'slug', 'description', 'ingredients',
        'dosage', 'manufacturer', 'expiry_date', 'price', 'discount_price',
        'stock_quantity', 'sku', 'barcode', 'requires_prescription', 'status'
    ];

    protected \$casts = [
        'expiry_date' => 'date',
        'requires_prescription' => 'boolean',
        'status' => 'boolean',
    ];

    public function category() { return \$this->belongsTo(Category::class); }
    public function brand() { return \$this->belongsTo(Brand::class); }
    public function images() { return \$this->hasMany(ProductImage::class); }
    public function reviews() { return \$this->hasMany(Review::class); }
}
PHP,
    'ProductImage' => <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected \$fillable = ['product_id', 'image_path', 'is_primary'];

    public function product() { return \$this->belongsTo(Product::class); }
}
PHP,
    'Cart' => <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected \$fillable = ['user_id', 'session_id'];

    public function user() { return \$this->belongsTo(User::class); }
    public function items() { return \$this->hasMany(CartItem::class); }
}
PHP,
    'CartItem' => <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected \$fillable = ['cart_id', 'product_id', 'quantity'];

    public function cart() { return \$this->belongsTo(Cart::class); }
    public function product() { return \$this->belongsTo(Product::class); }
}
PHP,
    'Address' => <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected \$fillable = [
        'user_id', 'type', 'address_line1', 'city', 'state', 'zip_code', 'country', 'phone'
    ];

    public function user() { return \$this->belongsTo(User::class); }
}
PHP,
    'Order' => <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected \$fillable = [
        'user_id', 'order_number', 'total_amount', 'discount_amount', 'tax_amount',
        'shipping_amount', 'final_amount', 'payment_method', 'payment_status',
        'order_status', 'shipping_address_id', 'billing_address_id',
        'prescription_path', 'prescription_status', 'prescription_remarks'
    ];

    public function user() { return \$this->belongsTo(User::class); }
    public function items() { return \$this->hasMany(OrderItem::class); }
    public function shippingAddress() { return \$this->belongsTo(Address::class, 'shipping_address_id'); }
    public function billingAddress() { return \$this->belongsTo(Address::class, 'billing_address_id'); }
}
PHP,
    'OrderItem' => <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected \$fillable = ['order_id', 'product_id', 'quantity', 'price'];

    public function order() { return \$this->belongsTo(Order::class); }
    public function product() { return \$this->belongsTo(Product::class); }
}
PHP,
    'Coupon' => <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected \$fillable = [
        'code', 'type', 'value', 'min_cart_value', 'expiry_date', 'usage_limit', 'times_used'
    ];
    
    protected \$casts = [
        'expiry_date' => 'date',
    ];
}
PHP,
    'Review' => <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected \$fillable = ['product_id', 'user_id', 'rating', 'comment', 'is_approved'];

    public function product() { return \$this->belongsTo(Product::class); }
    public function user() { return \$this->belongsTo(User::class); }
}
PHP,
    'Contact' => <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected \$fillable = ['name', 'email', 'subject', 'message', 'is_replied'];
}
PHP,
    'Wishlist' => <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected \$fillable = ['user_id', 'product_id'];

    public function user() { return \$this->belongsTo(User::class); }
    public function product() { return \$this->belongsTo(Product::class); }
}
PHP,
    'Setting' => <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected \$fillable = ['key', 'value'];
}
PHP,
];

foreach ($models as $name => $content) {
    file_put_contents($dir . '/' . $name . '.php', $content);
    echo "Updated $name\n";
}
echo "Models generated.\n";
