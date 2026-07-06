<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Middleware\CheckRole; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. RUTE GUEST (Hanya sebelum login) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// --- 2. RUTE PROTECTED (Laravel 12 Direct Middleware) ---
Route::middleware(['auth', CheckRole::class . ':Manajer Gudang,Admin'])->group(function () {

    // --- 3. RUTE DASHBOARD UTAMA (Sudah masuk proteksi login) ---
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/admin/dashboard', [DashboardController::class, 'index']); // Tambahan rute /admin/dashboard agar serasi
    
    // Modul Stock Opname
    Route::get('/admin/opnames', [StockOpnameController::class, 'index'])->name('opnames.index');
    Route::post('/admin/opnames', [StockOpnameController::class, 'store'])->name('opnames.store');
    
    // Master Data
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('products', ProductController::class);

    // Transaksi Stok
    Route::get('transactions/print', [StockTransactionController::class, 'print'])->name('transactions.print');
    Route::resource('transactions', StockTransactionController::class);

    // Tombol Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// --- 4. RUTE LATIHAN / PRACTICE ---
Route::get('/practice', function () {
    return view('pages.practice.index');
})->name('index-practice');

Route::name('practice.')->group(function () {
    Route::name('first')->get('practice/1', function () {
        return view('pages.practice.1');
    });
    Route::name('second')->get('practice/2', function () {
        return view('pages.practice.2');
    });
});