<?php

namespace App\Http\Controllers;

use App\Services\StockTransactionService;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

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
        // Menggunakan tipe 'in' sesuai database kamu
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
        $validated['status'] = 'Pending'; // SOP: Wajib Pending di awal

        StockTransaction::create($validated);

        return redirect()->route('barang.masuk.index')->with('success', 'Pengajuan barang masuk berhasil dibuat! Menunggu konfirmasi Staff.');
    }

    // ==========================================
    // 📦 FITUR: BARANG KELUAR
    // ==========================================
    public function keluarIndex()
    {
        // Menggunakan tipe 'out' sesuai database kamu
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

        // Opsional: Validasi tambahan sebelum diajukan (Biar Manajer tidak asal input kalau stok kosong)
        $product = Product::findOrFail($request->product_id);
        if ($product->stock < $request->quantity) {
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
    // ⚡ TOMBOL SOP: KONFIRMASI & TOLAK (Prosedur Staff)
    // ==========================================
    public function konfirmasi($id)
    {
        $transaction = StockTransaction::findOrFail($id);
        
        if ($transaction->status !== 'Pending') {
            return redirect()->back()->with('error', 'Transaksi ini sudah diproses sebelumnya!');
        }

        $product = Product::findOrFail($transaction->product_id);

        // Eksekusi perubahan stok berdasarkan tipe transaksi
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

        // Cukup ubah status menjadi Ditolak tanpa memanipulasi stok produk
        $transaction->update(['status' => 'Ditolak']);

        return redirect()->back()->with('success', 'Transaksi telah ditolak! Stok produk tidak berubah.');
    }

    public function print()
    {
        $transactions = $this->transactionService->getAllTransactions();
        return view('admin.transactions.print', compact('transactions'));
    }
}