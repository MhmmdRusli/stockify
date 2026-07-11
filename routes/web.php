<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\UserController; 
use App\Http\Middleware\CheckRole; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. RUTE GUEST (Sebelum Login)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ==========================================
// 2. RUTE GLOBAL (Semua Role Setelah Login)
// ==========================================
Route::middleware(['auth', CheckRole::class . ':Admin,Manajer Gudang,Staff Gudang,admin,manajer gudang,staff gudang'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Rute Produk Global & Export
    Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    
    Route::get('/barang-masuk', [StockTransactionController::class, 'masukIndex'])->name('barang.masuk.index');
    Route::get('/barang-keluar', [StockTransactionController::class, 'keluarIndex'])->name('barang.keluar.index');

    Route::get('/api/categories', function() {
        return response()->json(\App\Models\Category::all());
    })->name('api.categories.index');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ==========================================
// 3. RUTE BERSAMA: ADMIN & MANAJER GUDANG
// ==========================================
Route::middleware(['auth', CheckRole::class . ':Admin,Manajer Gudang,admin,manajer gudang'])->group(function () {
    Route::resource('products', ProductController::class)->except(['index']);
    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    
    // Fitur Laporan Bersama
    Route::get('report/stock', [StockTransactionController::class, 'stockReport'])->name('report.stock');
    Route::get('report/transactions', [StockTransactionController::class, 'transactionReport'])->name('report.transaction');

    // Rute Export & Import Laporan
    Route::get('report/stock/export-excel', [StockTransactionController::class, 'exportExcel'])->name('report.stock.excel');
    Route::get('report/stock/export-pdf', [StockTransactionController::class, 'exportPdf'])->name('report.stock.pdf');
    Route::post('report/stock/import', [StockTransactionController::class, 'importExcel'])->name('report.stock.import');
});

// ==========================================
// 4. SINKRONISASI AKSI TRANSAKSI (Admin, Manajer Gudang & Staff Gudang)
// ==========================================
Route::middleware(['auth', CheckRole::class . ':Admin,Manajer Gudang,Staff Gudang,admin,manajer gudang,staff gudang'])->group(function () {
    Route::post('/barang-masuk', [StockTransactionController::class, 'masukStore'])->name('barang.masuk.store');
    Route::post('/barang-keluar', [StockTransactionController::class, 'keluarStore'])->name('barang.keluar.store');
    
    Route::post('/transactions/{id}/konfirmasi', [StockTransactionController::class, 'konfirmasi'])->name('transactions.konfirmasi');
    Route::post('/transactions/{id}/tolak', [StockTransactionController::class, 'tolak'])->name('transactions.tolak');
    
    Route::post('/products/{product}/kirim-tugas-restock', [StockTransactionController::class, 'kirimTugasRestock'])->name('products.kirim-restock');
    Route::get('/barang-masuk/restock/{product}', [StockTransactionController::class, 'restockForm'])->name('barang.masuk.restock.form');
    Route::post('/barang-masuk/restock', [StockTransactionController::class, 'restockStore'])->name('barang.masuk.restock.store');
});

// ==========================================
// 5. RUTE KHUSUS MANAJER GUDANG (Murni Opname)
// ==========================================
Route::middleware(['auth', CheckRole::class . ':Admin,Manajer Gudang,manajer gudang'])->group(function () {
    Route::get('/opnames', [StockOpnameController::class, 'index'])->name('opnames.index');
    Route::post('/opnames', [StockOpnameController::class, 'store'])->name('opnames.store');
});

// ==========================================
// 6. RUTE KHUSUS ADMIN (Master Data, Manajemen User & Log Aktivitas)
// ==========================================
Route::middleware(['auth', CheckRole::class . ':Admin,admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index']); 

    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class); 

    Route::resource('suppliers', SupplierController::class)->except(['index']);

    Route::get('report/users-activity', [StockTransactionController::class, 'userActivityReport'])->name('report.user_activity');
    Route::get('transactions/print', [StockTransactionController::class, 'print'])->name('transactions.print');
    Route::get('admin/settings', [DashboardController::class, 'settings'])->name('admin.settings'); 
    Route::put('admin/settings', [DashboardController::class, 'updateSettings'])->name('admin.settings.update');
});

// ==========================================
// 7. RUTE LATIHAN / PRACTICE
// ==========================================
Route::get('/practice', function () {
    return view('pages.practice.index');
})->name('index-practice');

Route::name('practice.')->prefix('practice')->group(function () {
    Route::get('/1', function () { return view('pages.practice.1'); })->name('first');
    Route::get('/2', function () { return view('pages.practice.2'); })->name('second');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/report/user-activity', [UserActivityController::class, 'index'])
        ->name('report.user_activity.index');

    Route::get('/report/user-activity/print', [UserActivityController::class, 'print'])
        ->name('report.user_activity.print');
});