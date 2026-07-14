<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Import facade View
use App\Models\StockTransaction;     // Import Model transaksi kamu

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Menyuntikkan data secara global ke semua view/halaman
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $user = auth()->user();
                
                // 1. Ambil data transaksi terbaru (untuk log aktivitas pengguna)
                $activities = StockTransaction::with(['user', 'product'])->latest()->get();
                
                // 2. Hitung jumlah pengajuan barang masuk yang statusnya masih 'Pending'
                // Menggunakan strtolower untuk menghindari ketidakcocokan huruf besar/kecil (case-sensitivity)
                $pendingMasukCount = 0;
                if (in_array(strtolower($user->role), ['admin', 'manajer gudang'])) {
                    $pendingMasukCount = StockTransaction::where('type', 'in')
                        ->where('status', 'Pending') 
                        ->count();
                }

                $view->with([
                    'activities' => $activities,
                    'pendingMasukCount' => $pendingMasukCount
                ]);
            } else {
                $view->with([
                    'activities' => collect(),
                    'pendingMasukCount' => 0
                ]);
            }
        });
    }
}