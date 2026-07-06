<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung total jenis produk
        $totalProducts = Product::count();

        // 2. Hitung total akumulasi kuantitas stok masuk & keluar
        $totalFormatIn = StockTransaction::where('type', 'in')->sum('quantity');
        $totalFormatOut = StockTransaction::where('type', 'out')->sum('quantity');

        // 3. Ambil produk untuk peringatan stok (Ambil 5 produk terbaru untuk dipantau di dashboard)
        $lowStockProducts = Product::latest()->take(5)->get(); 

        // =========================================================================
        // FIX & SINKRONISASI: Data Grafik Transaksi (7 Hari Terakhir)
        // =========================================================================
        $days = [];
        $dataIn = [];
        $dataOut = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            
            // Format nama hari & tanggal untuk label grafik (Contoh: "06 Jul")
            $days[] = now()->subDays($i)->translatedFormat('d M'); 

            // Hitung total kuantitas barang MASUK berdasarkan kolom 'date'
            $dataIn[] = StockTransaction::where('type', 'in')
                ->whereDate('date', $date) // Diubah ke kolom 'date' agar sinkron
                ->sum('quantity');

            // Hitung total kuantitas barang KELUAR berdasarkan kolom 'date'
            $dataOut[] = StockTransaction::where('type', 'out')
                ->whereDate('date', $date) // Diubah ke kolom 'date' agar sinkron
                ->sum('quantity');
        }
        // =========================================================================

        // PERBAIKAN: Menggunakan string (nama variabel diapit tanda petik) tanpa tanda $
        return view('admin.dashboard', compact(
            'totalProducts', 
            'totalFormatIn', 
            'totalFormatOut', 
            'lowStockProducts',
            'days',      
            'dataIn',    
            'dataOut'    
        ));
    }
}