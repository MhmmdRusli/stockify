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
        // 🚨 DATA GLOBAL: UNTUK MONITORING BATAS STOK TERBARU
        // ==========================================
        // Logika yang bener: Ambil produk yang stoknya saat ini LEBIH KECIL ATAU SAMA DENGAN batas minimum stoknya
        $lowStockProducts = Product::whereColumn('stock', '<=', 'minimum_stock')
            ->latest()
            ->take(5)
            ->get();


        // ==========================================
        // 🛡️ KONDISI PEMBAGIAN DATA BERDASARKAN ROLE
        // ==========================================

        // 1. DATA KHUSUS ADMIN
        if ($role === 'Admin') {
            $totalProducts = Product::count();
            $totalFormatIn = StockTransaction::where('type', 'in')->sum('quantity');
            $totalFormatOut = StockTransaction::where('type', 'out')->sum('quantity');

            $recentUsers = User::latest()->take(5)->get();

            return view('admin.dashboard', compact(
                'totalProducts', 'totalFormatIn', 'totalFormatOut',
                'lowStockProducts', 'days', 'dataIn', 'dataOut', 'recentUsers'
            ));
        }

        // 2. DATA KHUSUS MANAJER GUDANG
        elseif ($role === 'Manajer Gudang') {
            // Statistik transaksi hari ini
            $masukHariIni = StockTransaction::where('type', 'in')->whereDate('date', now()->toDateString())->sum('quantity');
            $keluarHariIni = StockTransaction::where('type', 'out')->whereDate('date', now()->toDateString())->sum('quantity');

            // 🆕 Draft produk baru dari Staff yang menunggu verifikasi Manajer
            $draftDariStaff = StockTransaction::where('type', 'in')
                ->where('status', 'Pending')
                ->whereHas('user', function ($q) {
                    $q->where('role', 'Staff Gudang');
                })
                ->with('product')
                ->get();

            return view('admin.dashboard', compact(
                'lowStockProducts', 'masukHariIni', 'keluarHariIni', 'draftDariStaff'
            ));
        }

        // 3. DATA KHUSUS STAFF GUDANG
        else {
            // 🆕 FIX: hanya ambil transaksi yang DIBUAT OLEH MANAJER (butuh aksi Staff)
            $tugasMasuk = StockTransaction::where('type', 'in')
                ->where('status', 'Pending')
                ->whereHas('user', function ($q) {
                    $q->where('role', 'Manajer Gudang');
                })
                ->with('product')
                ->get();

            $tugasKeluar = StockTransaction::where('type', 'out')->where('status', 'Pending')->with('product')->get();

            // SelesaiHariIni fallback buat ringkasan mini staff gudang
            $selesaiHariIni = StockTransaction::where('approved_by', Auth::id())
                ->whereDate('updated_at', now()->toDateString())
                ->count();

            return view('admin.dashboard', compact('tugasMasuk', 'tugasKeluar', 'lowStockProducts', 'selesaiHariIni'));
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