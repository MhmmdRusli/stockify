<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

        // ==========================================
        // 📊 LOGIKA GRAFIK 7 HARI TERAKHIR (Dipakai Admin & Manajer)
        // ==========================================
        $days = [];
        $dataIn = [];
        $dataOut = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $days[] = now()->subDays($i)->translatedFormat('d M'); 

            $dataIn[] = StockTransaction::where('type', 'in')
                ->whereDate('date', $date)
                ->sum('quantity');

            $dataOut[] = StockTransaction::where('type', 'out')
                ->whereDate('date', $date)
                ->sum('quantity');
        }

        // ==========================================
        // 🛡️ KONDISI PEMBAGIAN DATA BERDASARKAN ROLE
        // ==========================================
        
        // 1. DATA KHUSUS ADMIN
        if ($role === 'Admin') {
            $totalProducts = Product::count();
            $totalFormatIn = StockTransaction::where('type', 'in')->sum('quantity');
            $totalFormatOut = StockTransaction::where('type', 'out')->sum('quantity');
            $lowStockProducts = Product::where('minimum_stock', '<', 10)->latest()->take(5)->get();
            
            // Tambah data user untuk Admin (Aktivitas terbaru)
            $recentUsers = User::latest()->take(5)->get(); 

            return view('admin.dashboard', compact(
                'totalProducts', 'totalFormatIn', 'totalFormatOut', 
                'lowStockProducts', 'days', 'dataIn', 'dataOut', 'recentUsers'
            ));
        } 
        
        // 2. DATA KHUSUS MANAJER GUDANG
        elseif ($role === 'Manajer Gudang') {
            $totalProducts = Product::count();
            // Produk Kritis yang stoknya di bawah angka aman minimum_stock
            $lowStockProducts = Product::where('minimum_stock', '<', 10)->get(); 
            
            // Statistik Khusus Hari Ini
            $masukHariIni = StockTransaction::where('type', 'in')->whereDate('date', now()->toDateString())->sum('quantity');
            $keluarHariIni = StockTransaction::where('type', 'out')->whereDate('date', now()->toDateString())->sum('quantity');

            return view('admin.dashboard', compact(
                'totalProducts', 'lowStockProducts', 'masukHariIni', 'keluarHariIni', 'days', 'dataIn', 'dataOut'
            ));
        } 
        
        // 3. DATA KHUSUS STAFF GUDANG (Fokus pada List Tugas Pending)
        else {
            // Mengambil transaksi yang butuh tindakan/konfirmasi lapangan dari Staff
            $tugasMasuk = StockTransaction::where('type', 'in')->where('status', 'Pending')->with('product')->get();
            $tugasKeluar = StockTransaction::where('type', 'out')->where('status', 'Pending')->with('product')->get();

            return view('admin.dashboard', compact('tugasMasuk', 'tugasKeluar'));
        }
    }
}