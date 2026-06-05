<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin', 'verified'])->group(function () {

    Route::get('/admin', function () {
        return 'Panel Administrador';
    });

});

Route::middleware(['auth', 'employee', 'verified'])->group(function () {

    Route::get('/employee', function () {
        return 'Employee Dashboard';
    });

});

Route::middleware(['auth', 'customer', 'verified'])->group(function () {

    Route::get('/customer', function () {
        return 'Customer Dashboard';
    });

});

require __DIR__.'/auth.php';
