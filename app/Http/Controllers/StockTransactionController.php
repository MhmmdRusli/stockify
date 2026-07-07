<?php

namespace App\Http\Controllers;

use App\Services\StockTransactionService;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

// 🟢 KOREKSI: WAJIB DI-IMPORT AGAR FITUR EXCEL & PDF TIDAK ERROR
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
        $transactions = StockTransaction::where('type', 'in')->with('product')->latest()->get();
        $products = Product::all(); 

        return view('admin.transactions.masuk', compact('transactions', 'products'));
    }

    public function masukStore(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string',
        ]);

        $validated['type'] = 'in';
        $validated['date'] = now()->toDateString();
        $validated['user_id'] = auth()->id() ?? 1; 
        $validated['status'] = 'Pending'; 

        StockTransaction::create($validated);

        return redirect()->route('barang.masuk.index')->with('success', 'Pengajuan barang masuk berhasil dibuat! Menunggu konfirmasi Staff.');
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
        if ($product->minimum_stock < $request->quantity) {
            return redirect()->back()->with('error', 'Gagal mengajukan! Stok di gudang tidak mencukupi.');
        }

        $validated['type'] = 'out';
        $validated['date'] = now()->toDateString();
        $validated['user_id'] = auth()->id() ?? 1; 
        $validated['status'] = 'Pending'; 

        StockTransaction::create($validated);

        return redirect()->route('barang.keluar.index')->with('success', 'Pengajuan barang keluar berhasil dibuat! Menunggu konfirmasi Staff.');
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
            $product->increment('minimum_stock', $transaction->quantity);
            $transaction->update(['status' => 'Diterima']);
        } else if ($transaction->type === 'out') {
            if ($product->minimum_stock < $transaction->quantity) {
                return redirect()->back()->with('error', 'Gagal konfirmasi! Stok aktual di gudang tidak cukup.');
            }
            $product->decrement('minimum_stock', $transaction->quantity);
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
            // 🟢 KOREKSI OPTIMASI: Ditambahkan ekstensi agar pembacaan file .xlsx lebih toleran di beberapa operating system
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