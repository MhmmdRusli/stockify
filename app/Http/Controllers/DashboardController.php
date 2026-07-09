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
        // 📊 LOGIKA GRAFIK 7 HARI TERAKHIR (Khusus Admin)
        // ==========================================
        $days = [];
        $dataIn = [];
        $dataOut = [];

        if ($role === 'Admin') {
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

            $recentUsers = User::latest()->take(5)->get();

            return view('admin.dashboard', compact(
                'totalProducts', 'totalFormatIn', 'totalFormatOut',
                'lowStockProducts', 'days', 'dataIn', 'dataOut', 'recentUsers'
            ));
        }

        // 2. DATA KHUSUS MANAJER GUDANG (Sesuai Spec: Stok Menipis, Masuk & Keluar Hari Ini)
        elseif ($role === 'Manajer Gudang') {
            // Daftar & jumlah produk yang stoknya menipis (di bawah batas minimum)
            $lowStockProducts = Product::where('minimum_stock', '<', 10)->latest()->get();

            // Statistik transaksi hari ini
            $masukHariIni = StockTransaction::where('type', 'in')->whereDate('date', now()->toDateString())->sum('quantity');
            $keluarHariIni = StockTransaction::where('type', 'out')->whereDate('date', now()->toDateString())->sum('quantity');

            return view('admin.dashboard', compact(
                'lowStockProducts', 'masukHariIni', 'keluarHariIni'
            ));
        }

        // 3. DATA KHUSUS STAFF GUDANG (Daftar tugas konfirmasi barang masuk/keluar)
        else {
            $tugasMasuk = StockTransaction::where('type', 'in')->where('status', 'Pending')->with('product')->get();
            $tugasKeluar = StockTransaction::where('type', 'out')->where('status', 'Pending')->with('product')->get();

            return view('admin.dashboard', compact('tugasMasuk', 'tugasKeluar'));
        }
    }

    public function settings()
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'warehouse_location' => 'required|string|max:255',
            'default_min_stock' => 'required|integer|min:0',
            'sku_prefix' => 'required|string|max:10',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        \App\Models\Setting::updateOrCreate(['key' => 'app_name'], ['value' => $request->app_name]);
        \App\Models\Setting::updateOrCreate(['key' => 'warehouse_location'], ['value' => $request->warehouse_location]);
        \App\Models\Setting::updateOrCreate(['key' => 'default_min_stock'], ['value' => $request->default_min_stock]);
        \App\Models\Setting::updateOrCreate(['key' => 'sku_prefix'], ['value' => $request->sku_prefix]);

        if ($request->hasFile('app_logo')) {
            $path = $request->file('app_logo')->store('logos', 'public');
            \App\Models\Setting::updateOrCreate(['key' => 'app_logo'], ['value' => $path]);
        }

        return redirect()->back()->with('success', 'Pengaturan aplikasi dan logo berhasil diperbarui!');
    }
}