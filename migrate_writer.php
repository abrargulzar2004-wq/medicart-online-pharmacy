<?php

$dir = __DIR__ . '/database/migrations';
$files = scandir($dir);

$schemas = [
    'users' => <<<PHP
            \$table->id();
            \$table->string('name');
            \$table->string('email')->unique();
            \$table->timestamp('email_verified_at')->nullable();
            \$table->string('password');
            \$table->enum('role', ['admin', 'customer'])->default('customer');
            \$table->string('otp')->nullable();
            \$table->timestamp('otp_expires_at')->nullable();
            \$table->rememberToken();
            \$table->timestamps();
PHP,
    'categories' => <<<PHP
            \$table->id();
            \$table->string('name');
            \$table->string('slug')->unique();
            \$table->text('description')->nullable();
            \$table->string('image')->nullable();
            \$table->boolean('status')->default(1);
            \$table->timestamps();
PHP,
    'brands' => <<<PHP
            \$table->id();
            \$table->string('name');
            \$table->string('slug')->unique();
            \$table->string('image')->nullable();
            \$table->boolean('status')->default(1);
            \$table->timestamps();
PHP,
    'products' => <<<PHP
            \$table->id();
            \$table->foreignId('category_id')->constrained()->onDelete('cascade');
            \$table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            \$table->string('name');
            \$table->string('slug')->unique();
            \$table->text('description');
            \$table->text('ingredients')->nullable();
            \$table->text('dosage')->nullable();
            \$table->string('manufacturer')->nullable();
            \$table->date('expiry_date')->nullable();
            \$table->decimal('price', 10, 2);
            \$table->decimal('discount_price', 10, 2)->nullable();
            \$table->integer('stock_quantity')->default(0);
            \$table->string('sku')->unique();
            \$table->string('barcode')->nullable();
            \$table->boolean('requires_prescription')->default(0);
            \$table->boolean('status')->default(1);
            \$table->timestamps();
PHP,
    'product_images' => <<<PHP
            \$table->id();
            \$table->foreignId('product_id')->constrained()->onDelete('cascade');
            \$table->string('image_path');
            \$table->boolean('is_primary')->default(0);
            \$table->timestamps();
PHP,
    'carts' => <<<PHP
            \$table->id();
            \$table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            \$table->string('session_id')->nullable();
            \$table->timestamps();
PHP,
    'cart_items' => <<<PHP
            \$table->id();
            \$table->foreignId('cart_id')->constrained()->onDelete('cascade');
            \$table->foreignId('product_id')->constrained()->onDelete('cascade');
            \$table->integer('quantity');
            \$table->timestamps();
PHP,
    'addresses' => <<<PHP
            \$table->id();
            \$table->foreignId('user_id')->constrained()->onDelete('cascade');
            \$table->enum('type', ['billing', 'shipping'])->default('shipping');
            \$table->string('address_line1');
            \$table->string('city');
            \$table->string('state');
            \$table->string('zip_code');
            \$table->string('country');
            \$table->string('phone');
            \$table->timestamps();
PHP,
    'orders' => <<<PHP
            \$table->id();
            \$table->foreignId('user_id')->constrained()->onDelete('cascade');
            \$table->string('order_number')->unique();
            \$table->decimal('total_amount', 10, 2);
            \$table->decimal('discount_amount', 10, 2)->default(0);
            \$table->decimal('tax_amount', 10, 2)->default(0);
            \$table->decimal('shipping_amount', 10, 2)->default(0);
            \$table->decimal('final_amount', 10, 2);
            \$table->string('payment_method');
            \$table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            \$table->enum('order_status', ['Pending', 'Confirmed', 'Preparing', 'Shipped', 'Delivered', 'Cancelled'])->default('Pending');
            \$table->foreignId('shipping_address_id')->nullable()->constrained('addresses')->onDelete('set null');
            \$table->foreignId('billing_address_id')->nullable()->constrained('addresses')->onDelete('set null');
            \$table->string('prescription_path')->nullable();
            \$table->enum('prescription_status', ['not_required', 'pending', 'approved', 'rejected'])->default('not_required');
            \$table->text('prescription_remarks')->nullable();
            \$table->timestamps();
PHP,
    'order_items' => <<<PHP
            \$table->id();
            \$table->foreignId('order_id')->constrained()->onDelete('cascade');
            \$table->foreignId('product_id')->constrained()->onDelete('cascade');
            \$table->integer('quantity');
            \$table->decimal('price', 10, 2);
            \$table->timestamps();
PHP,
    'coupons' => <<<PHP
            \$table->id();
            \$table->string('code')->unique();
            \$table->enum('type', ['percentage', 'fixed']);
            \$table->decimal('value', 10, 2);
            \$table->decimal('min_cart_value', 10, 2)->nullable();
            \$table->date('expiry_date');
            \$table->integer('usage_limit')->nullable();
            \$table->integer('times_used')->default(0);
            \$table->timestamps();
PHP,
    'reviews' => <<<PHP
            \$table->id();
            \$table->foreignId('product_id')->constrained()->onDelete('cascade');
            \$table->foreignId('user_id')->constrained()->onDelete('cascade');
            \$table->integer('rating');
            \$table->text('comment');
            \$table->boolean('is_approved')->default(0);
            \$table->timestamps();
PHP,
    'contacts' => <<<PHP
            \$table->id();
            \$table->string('name');
            \$table->string('email');
            \$table->string('subject');
            \$table->text('message');
            \$table->boolean('is_replied')->default(0);
            \$table->timestamps();
PHP,
    'wishlists' => <<<PHP
            \$table->id();
            \$table->foreignId('user_id')->constrained()->onDelete('cascade');
            \$table->foreignId('product_id')->constrained()->onDelete('cascade');
            \$table->timestamps();
PHP,
    'settings' => <<<PHP
            \$table->id();
            \$table->string('key')->unique();
            \$table->text('value')->nullable();
            \$table->timestamps();
PHP
];

foreach ($files as $file) {
    if (strpos($file, '.php') === false) continue;

    $filePath = $dir . '/' . $file;
    $content = file_get_contents($filePath);

    foreach ($schemas as $tableName => $schemaCode) {
        if (strpos($file, 'create_' . $tableName . '_table') !== false) {
            
            if ($tableName === 'users') {
                $target = "            \$table->id();\n            \$table->string('name');\n            \$table->string('email')->unique();\n            \$table->timestamp('email_verified_at')->nullable();\n            \$table->string('password');\n            \$table->rememberToken();\n            \$table->timestamps();";
                $content = str_replace($target, $schemaCode, $content);
            } else {
                $target = "            \$table->id();\n            \$table->timestamps();";
                $content = str_replace($target, $schemaCode, $content);
            }
            
            file_put_contents($filePath, $content);
            echo "Updated $file\n";
        }
    }
}
echo "Done.\n";
