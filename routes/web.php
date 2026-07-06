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

// ==========================================
// 1. RUTE GUEST (Hanya Diakses Sebelum Login)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ==========================================
// 2. RUTE GLOBAL (Bisa Diakses Semua Role Setelah Login)
// ==========================================
Route::middleware(['auth', CheckRole::class . ':Admin,Manajer Gudang,Staff Gudang'])->group(function () {
    
    // Halaman Utama Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Halaman Riwayat Transaksi (Semua role bisa melihat daftar tabelnya)
    Route::get('/barang-masuk', [StockTransactionController::class, 'masukIndex'])->name('barang.masuk.index');
    Route::get('/barang-keluar', [StockTransactionController::class, 'keluarIndex'])->name('barang.keluar.index');

    // Proses Keluar Aplikasi
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ==========================================
// 3. RUTE BERSAMA: ADMIN & MANAJER GUDANG
// ==========================================
Route::middleware(['auth', CheckRole::class . ':Admin,Manajer Gudang'])->group(function () {
    // Dipindah ke sini agar Manajer Gudang & Admin sama-sama punya akses CRUD Produk
    Route::resource('products', ProductController::class);
});

// ==========================================
// 4. RUTE KHUSUS MANAJER GUDANG
// ==========================================
Route::middleware(['auth', CheckRole::class . ':Manajer Gudang'])->group(function () {
    // Hak Akses membuat Pengajuan Barang Baru (Store)
    Route::post('/barang-masuk', [StockTransactionController::class, 'masukStore'])->name('barang.masuk.store');
    Route::post('/barang-keluar', [StockTransactionController::class, 'keluarStore'])->name('barang.keluar.store');

    // Stock Opname
    Route::prefix('admin')->group(function () {
        Route::get('/opnames', [StockOpnameController::class, 'index'])->name('opnames.index');
        Route::post('/opnames', [StockOpnameController::class, 'store'])->name('opnames.store');
    });
});

// ==========================================
// 5. RUTE KHUSUS STAFF GUDANG (Aksi Eksekusi Lapangan)
// ==========================================
Route::middleware(['auth', CheckRole::class . ':Staff Gudang'])->group(function () {
    // Jalur Aksi Approval Transaksi (Hanya boleh diklik oleh Staff Gudang)
    Route::post('/transactions/{id}/konfirmasi', [StockTransactionController::class, 'konfirmasi'])->name('transactions.konfirmasi');
    Route::post('/transactions/{id}/tolak', [StockTransactionController::class, 'tolak'])->name('transactions.tolak');
});

// ==========================================
// 6. RUTE KHUSUS ADMIN (Akses Master Data & Audit)
// ==========================================
Route::middleware(['auth', CheckRole::class . ':Admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index']); 

    // Master Data Manajemen selain Produk (CRUD)
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    
    // Fitur Cetak Laporan Global
    Route::get('transactions/print', [StockTransactionController::class, 'print'])->name('transactions.print');
});

// ==========================================
// 7. RUTE LATIHAN / PRACTICE
// ==========================================
Route::get('/practice', function () {
    return view('pages.practice.index');
})->name('index-practice');

Route::name('practice.')->prefix('practice')->group(function () {
    Route::get('/1', function () {
        return view('pages.practice.1');
    })->name('first');
    
    Route::get('/2', function () {
        return view('pages.practice.2');
    })->name('second');
});