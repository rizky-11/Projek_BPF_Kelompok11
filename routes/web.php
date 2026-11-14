<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/* ---------- Publik ---------- */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang', [CartController::class, 'store'])->name('cart.store');
Route::patch('/keranjang/{variant}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{variant}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('throttle:10,1')->name('checkout.store');

Route::get('/lacak-pesanan', [OrderController::class, 'trackForm'])->name('orders.track');
Route::post('/lacak-pesanan', [OrderController::class, 'track'])->middleware('throttle:10,1')->name('orders.search');
Route::get('/pesanan/{order:order_code}', [OrderController::class, 'show'])->name('orders.show');
Route::post('/pesanan/{order:order_code}/pembayaran', [OrderController::class, 'pay'])->middleware('throttle:10,1')->name('orders.pay');

Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/hubungi-kami', [ContactController::class, 'create'])->name('contact.create');
Route::post('/hubungi-kami', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');

/* ---------- Admin ---------- */
Route::get('/admin/login', [Admin\LoginController::class, 'create'])->middleware('guest')->name('login');
Route::post('/admin/login', [Admin\LoginController::class, 'store'])->middleware(['guest', 'throttle:5,1'])->name('login.store');
Route::post('/admin/logout', [Admin\LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');

    Route::resource('categories', Admin\CategoryController::class)->except('show');
    Route::resource('products', Admin\ProductController::class)->except('show');
    Route::post('products/{product}/images', [Admin\ProductImageController::class, 'store'])->name('products.images.store');
    Route::delete('images/{image}', [Admin\ProductImageController::class, 'destroy'])->name('images.destroy');
    Route::post('products/{product}/variants', [Admin\ProductVariantController::class, 'store'])->name('products.variants.store');
    Route::put('variants/{variant}', [Admin\ProductVariantController::class, 'update'])->name('variants.update');
    Route::delete('variants/{variant}', [Admin\ProductVariantController::class, 'destroy'])->name('variants.destroy');

    Route::resource('orders', Admin\OrderController::class)->only(['index', 'show', 'update']);
    Route::post('payments/{payment}/verify', [Admin\PaymentController::class, 'verify'])->name('payments.verify');
    Route::post('payments/{payment}/reject', [Admin\PaymentController::class, 'reject'])->name('payments.reject');
    Route::get('payments/{payment}/proof', [Admin\PaymentController::class, 'proof'])->name('payments.proof');

    Route::resource('messages', Admin\MessageController::class)->only(['index', 'show', 'destroy']);
    Route::resource('faqs', Admin\FaqController::class)->except('show');

    Route::get('settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');
    Route::get('password', [Admin\PasswordController::class, 'edit'])->name('password.edit');
    Route::put('password', [Admin\PasswordController::class, 'update'])->name('password.update');
});
