<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

        // ==========================================
        // 📊 LOGIKA GRAFIK 7 HARI TERAKHIR (Khusus Admin saja)
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

            // Tambah data user untuk Admin (Aktivitas terbaru)
            $recentUsers = User::latest()->take(5)->get();

            return view('admin.dashboard', compact(
                'totalProducts', 'totalFormatIn', 'totalFormatOut',
                'lowStockProducts', 'days', 'dataIn', 'dataOut', 'recentUsers'
            ));
        }

        // 2. DATA KHUSUS MANAJER GUDANG (Fokus: Approval & Pengawasan Operasional)
        elseif ($role === 'Manajer Gudang') {
            // Status stok: Aman / Menipis / Habis
            $stokAman = Product::where('minimum_stock', '>', 5)->count();
            $stokMenipis = Product::where('minimum_stock', '<=', 5)->where('minimum_stock', '>', 0)->count();
            $stokHabis = Product::where('minimum_stock', '<=', 0)->count();

            // Daftar produk menipis/habis untuk ditampilkan (bukan hanya angka)
            $criticalProducts = Product::where('minimum_stock', '<=', 5)->orderBy('minimum_stock', 'asc')->take(6)->get();

            // Transaksi pending yang butuh persetujuan Manajer
            $tugasMasuk = StockTransaction::where('type', 'in')->where('status', 'Pending')->with('product')->latest()->get();
            $tugasKeluar = StockTransaction::where('type', 'out')->where('status', 'Pending')->with('product')->latest()->get();

            // Ringkasan performa supplier & produk
            $totalSuppliers = Supplier::count();
            $topProducts = StockTransaction::where('status', '!=', 'Pending')
                ->select('product_id')
                ->selectRaw('SUM(quantity) as total_qty')
                ->whereDate('date', '>=', now()->subDays(7))
                ->groupBy('product_id')
                ->orderByDesc('total_qty')
                ->with('product')
                ->take(5)
                ->get();

            return view('admin.dashboard', compact(
                'stokAman', 'stokMenipis', 'stokHabis', 'criticalProducts',
                'tugasMasuk', 'tugasKeluar',
                'totalSuppliers', 'topProducts'
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

    public function settings()
    {
        // Mengambil semua pengaturan dari database dan menjadikannya key-value array
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
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi foto
        ]);

        // 1. Simpan Teks (Nama, Lokasi, dll)
        \App\Models\Setting::updateOrCreate(['key' => 'app_name'], ['value' => $request->app_name]);
        \App\Models\Setting::updateOrCreate(['key' => 'warehouse_location'], ['value' => $request->warehouse_location]);
        \App\Models\Setting::updateOrCreate(['key' => 'default_min_stock'], ['value' => $request->default_min_stock]);
        \App\Models\Setting::updateOrCreate(['key' => 'sku_prefix'], ['value' => $request->sku_prefix]);

        // 2. Logika Simpan Foto Logo
        if ($request->hasFile('app_logo')) {
            // Simpan file ke folder storage/app/public/logos
            $path = $request->file('app_logo')->store('logos', 'public');

            // Simpan path file ke database settings
            \App\Models\Setting::updateOrCreate(['key' => 'app_logo'], ['value' => $path]);
        }

        return redirect()->back()->with('success', 'Pengaturan aplikasi dan logo berhasil diperbarui!');
    }
}