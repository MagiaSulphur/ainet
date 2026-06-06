<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TshirtImageFileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', [CatalogController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{tshirtImage}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/tshirt-images/{tshirtImage}/file', [TshirtImageFileController::class, 'show'])->name('tshirt-images.file');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{tshirtImage}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{key}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{key}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [OrderController::class, 'index'])->name('dashboard');
    Route::get('checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
    Route::patch('orders/{order}/close', [OrderController::class, 'close'])->name('orders.close');
    Route::patch('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// Route::middleware(['auth', 'role:A'])->group(function () {

//     Route::get('/admin-test', function () {
//         return 'Solo administradores';
//     });

// });

Route::middleware(['auth', 'role:A'])->group(function () {

    Route::get('/admin/users', [UserController::class, 'index'])
        ->name('users.index');

            Route::get('/admin/users/create',
    [UserController::class, 'create'])
    ->name('users.create');

Route::post('/admin/users',
    [UserController::class, 'store'])
    ->name('users.store');

    Route::get(
    '/admin/users/{user}/edit',
    [UserController::class, 'edit']
)->name('users.edit');

Route::patch(
    '/admin/users/{user}',
    [UserController::class, 'update']
)->name('users.update');

Route::delete(
    '/admin/users/{user}',
    [UserController::class, 'destroy']
)->name('users.destroy');

Route::patch('/admin/users/{user}/toggle-block',
    [UserController::class, 'toggleBlocked'])
    ->name('users.toggle-block');
});

require __DIR__.'/settings.php';
// require __DIR__.'/auth.php';
