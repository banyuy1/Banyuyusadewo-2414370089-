<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Jalur halaman dashboard utama dialihkan ke halaman analitik grafik
Route::get('/dashboard', [PosController::class, 'analyticsDashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/analytics', [PosController::class, 'analyticsDashboard'])->middleware(['auth', 'verified'])->name('analytics.index');

Route::middleware('auth')->group(function () {
    // Menu Kasir POS
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/add/{product}', [PosController::class, 'addToCart'])->name('pos.add');
    Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
    Route::get('/pos/clear', [PosController::class, 'clearCart'])->name('pos.clear');

    // CRUD
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';