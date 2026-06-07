<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\TshirtImageFileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{tshirtImage}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/tshirt-images/{tshirtImage}/file', [TshirtImageFileController::class, 'show'])->name('tshirt-images.file');
