<?php

namespace App\Http\Controllers;

use App\Services\StockTransactionService;
use App\Models\Product;
use App\Models\Category; 
use App\Models\StockTransaction;
use Illuminate\Http\Request;

// IMPORT UTUK EXCEL & PDF
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;

class StockTransactionController extends Controller
{
    protected $transactionService;

    public function __construct(StockTransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    // ==========================================
    // 🚚 FITUR: BARANG MASUK
    // ==========================================
    public function masukIndex()
    {
        // Mengambil semua transaksi masuk baik Pending, Diterima, maupun Ditolak
        $transactions = StockTransaction::where('type', 'in')->with('product')->latest()->get();
        $categories = Category::all();
        $products = Product::all();

        return view('admin.transactions.masuk', compact('transactions', 'products', 'categories'));
    }

    public function masukStore(Request $request)
    {
        // 1. Jalankan Validasi khusus untuk Ajukan Produk Baru dari Staff
        $request->validate([
            'new_product_name'  => 'required|string|max:255',
            'category_id'       => 'required',
            'quantity'          => 'required|integer|min:1',
        ]);

        try {
            $categoryId = $request->category_id;

            // 2. Cek apakah staff membuat kategori baru langsung dari modal
            if ($request->category_id === 'NEW_CATEGORY') {
                $request->validate(['new_category_name' => 'required|string|max:255']);

                $newCat = \App\Models\Category::create([
                    'name' => $request->new_category_name
                ]);
                $categoryId = $newCat->id;
            }

            // 3. Ambil Supplier ID default agar database tidak error
            // Kita ambil ID supplier pertama yang tersedia di database kamu
            $defaultSupplier = \App\Models\Supplier::first();
            $supplierId = $defaultSupplier ? $defaultSupplier->id : null;

            // Buat kode SKU otomatis jika dikosongkan
            $sku = $request->sku ?? 'SKU-' . strtoupper(uniqid());

            // 4. Buat data Produk Baru (sebagai draft)
            // Field harga & minimum stok sengaja diisi 0 dulu karena staff gudang
            // belum tentu tahu harga beli/jual — nanti manajer/admin yang
            // melengkapi via halaman Data Produk setelah draft disetujui.
            $newProduct = \App\Models\Product::create([
                'name'           => $request->new_product_name,
                'category_id'    => $categoryId,
                'supplier_id'    => $supplierId, // Mengatasi error 'supplier_id' doesn't have a default value
                'sku'            => $sku,
                'stock'          => 0, // Stok awal tetap 0 sebelum disetujui Manajer
                'purchase_price' => 0, // Mengatasi error 'purchase_price' doesn't have a default value
                'selling_price'  => 0, // Mengatasi error 'selling_price' doesn't have a default value
                'minimum_stock'  => 0, // Mengatasi error 'minimum_stock' doesn't have a default value
            ]);

            // 5. Simpan ke transaksi stok dengan status 'Pending'
            \App\Models\StockTransaction::create([
                'product_id'   => $newProduct->id,
                'user_id'      => auth()->id(),
                'type'         => 'in',
                'quantity'     => $request->quantity,
                'date'         => now()->toDateString(),
                'status'       => 'Pending', // Status pending untuk staff gudang
                'notes'        => $request->notes ?? 'Pengajuan barang masuk baru oleh Staff',
            ]);

            return redirect()->route('barang.masuk.index')->with('success', 'Draft barang masuk baru berhasil diajukan! Menunggu verifikasi manajer.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengajukan draft: ' . $e->getMessage());
        }
    }

    // ==========================================
    // 📦 FITUR: BARANG KELUAR
    // ==========================================
    public function keluarIndex()
    {
        $transactions = StockTransaction::where('type', 'out')->with('product')->latest()->get();
        $products = Product::all();

        return view('admin.transactions.keluar', compact('transactions', 'products'));
    }

    public function keluarStore(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Validasi kecukupan stok pakai kolom 'stock' (stok aktual)
        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Gagal mengajukan! Stok di gudang tidak mencukupi.');
        }

        $validated['type'] = 'out';
        $validated['date'] = now()->toDateString();
        $validated['user_id'] = auth()->id() ?? 1;
        $validated['status'] = 'Pending';

        StockTransaction::create($validated);

        // KOREKSI: Menggunakan redirect()->back()
        return redirect()->back()->with('success', 'Pengajuan barang keluar berhasil dibuat! Menunggu konfirmasi Staff.');
    }

    // ==========================================
    // ⚡ TOMBOL SOP: KONFIRMASI & TOLAK
    // ==========================================
    public function konfirmasi($id)
    {
        $transaction = StockTransaction::findOrFail($id);

        if ($transaction->status !== 'Pending') {
            return redirect()->back()->with('error', 'Transaksi ini sudah diproses sebelumnya!');
        }

        $product = Product::findOrFail($transaction->product_id);

        if ($transaction->type === 'in') {
            $product->increment('stock', $transaction->quantity);
            $transaction->update(['status' => 'Diterima']);
        } else if ($transaction->type === 'out') {
            if ($product->stock < $transaction->quantity) {
                return redirect()->back()->with('error', 'Gagal konfirmasi! Stok aktual di gudang tidak cukup.');
            }
            $product->decrement('stock', $transaction->quantity);
            $transaction->update(['status' => 'Dikeluarkan']);
        }

        return redirect()->back()->with('success', 'Transaksi Berhasil Dikonfirmasi! Stok telah diperbarui.');
    }

    public function tolak($id)
    {
        $transaction = StockTransaction::findOrFail($id);

        if ($transaction->status !== 'Pending') {
            return redirect()->back()->with('error', 'Transaksi ini sudah diproses sebelumnya!');
        }

        $transaction->update(['status' => 'Ditolak']);

        return redirect()->back()->with('success', 'Transaksi telah ditolak! Stok produk tidak berubah.');
    }

    public function print()
    {
        $transactions = $this->transactionService->getAllTransactions();
        return view('admin.transactions.print', compact('transactions'));
    }

    // ==========================================
    // 📊 FITUR LAPORAN
    // ==========================================
    public function stockReport()
    {
        $products = Product::with('category')->get();
        return view('report.stock', compact('products'));
    }

    public function transactionReport()
    {
        $transactions = StockTransaction::with(['product', 'user'])->latest()->get();
        return view('report.transaction', compact('transactions'));
    }

    public function userActivityReport()
    {
        $activities = StockTransaction::with(['user', 'product'])->latest()->get();
        return view('report.user_activity', compact('activities'));
    }

    // ==========================================
    // 🟢 EXPORT EXCEL
    // ==========================================
    public function exportExcel()
    {
        return Excel::download(new ProductsExport, 'laporan_stok_'.now()->format('Y-m-d').'.xlsx');
    }

    // ==========================================
    // 🔴 EXPORT PDF
    // ==========================================
    public function exportPdf()
    {
        $products = Product::with('category')->get();
        $pdf = Pdf::loadView('report.stock_pdf', compact('products'));
        return $pdf->download('laporan_stok_'.now()->format('Y-m-d').'.pdf');
    }

    // ==========================================
    // 🔵 IMPORT EXCEL
    // ==========================================
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|max:2048'
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data produk berhasil di-import massal!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data! Periksa kembali format kolom berkas Anda.');
        }
    }
}