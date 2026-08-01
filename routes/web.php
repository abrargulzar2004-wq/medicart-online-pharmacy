<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\PageController;

/*
|--------------------------------------------------------------------------
| Storefront
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('shop.show');

Route::get('/require-auth', function () {
    return redirect()->route('login')
        ->with('error', 'Please sign in or create an account to continue shopping.');
})->name('require.auth');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('auth.login.submit');

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('auth.register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('auth.register.submit');

Route::get('/otp-verify', [AuthController::class, 'showOtp'])
    ->name('auth.otp.show');

Route::post('/otp-verify', [AuthController::class, 'verifyOtp'])
    ->name('auth.otp.submit');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('auth.logout');

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'customer'])->group(function () {

    Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])
        ->name('customer.dashboard');

    Route::post('/customer/order/{order}/reupload', [CustomerController::class, 'reuploadPrescription'])
        ->name('customer.prescription.reupload');

    Route::get('/customer/order/{order}/invoice', [CustomerController::class, 'invoice'])
        ->name('customer.order.invoice');

    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/cart/add/{id}', [CartController::class, 'add'])
        ->name('cart.add');

    Route::get('/wishlist', [App\Http\Controllers\WishlistController::class, 'index'])
        ->name('wishlist.index');

    Route::post('/wishlist/add/{id}', [App\Http\Controllers\WishlistController::class, 'add'])
        ->name('wishlist.add');

    Route::post('/wishlist/remove/{id}', [App\Http\Controllers\WishlistController::class, 'remove'])
        ->name('wishlist.remove');

    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/checkout', [CheckoutController::class, 'process'])
        ->name('checkout.process');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        Route::resource('categories', CategoryController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('products', ProductController::class);

        Route::get('/inventory', [InventoryController::class, 'index'])
            ->name('inventory.index');
        Route::post('/inventory/{product}', [InventoryController::class, 'update'])
            ->name('inventory.update');

        Route::get('/customers', [App\Http\Controllers\Admin\CustomerController::class, 'index'])
            ->name('customers.index');
        Route::get('/customers/{user}/edit', [App\Http\Controllers\Admin\CustomerController::class, 'edit'])
            ->name('customers.edit');
        Route::put('/customers/{user}', [App\Http\Controllers\Admin\CustomerController::class, 'update'])
            ->name('customers.update');
        Route::delete('/customers/{user}', [App\Http\Controllers\Admin\CustomerController::class, 'destroy'])
            ->name('customers.destroy');

        Route::get('/contacts', [App\Http\Controllers\Admin\ContactController::class, 'index'])
            ->name('contacts.index');

        Route::get('/contacts/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'show'])
            ->name('contacts.show');

        Route::post('/contacts/{contact}/replied', [App\Http\Controllers\Admin\ContactController::class, 'markReplied'])
            ->name('contacts.replied');

        Route::delete('/contacts/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'destroy'])
            ->name('contacts.destroy');

        Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])
            ->name('settings.index');
        Route::post('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])
            ->name('settings.update');

        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [OrderController::class, 'show'])
            ->name('orders.show');

        Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])
            ->name('orders.update');

        Route::post('/orders/{order}/prescription', [OrderController::class, 'updatePrescription'])
            ->name('orders.prescription.update');
    });