<?php

namespace App\Http\Controllers;

use App\Services\StockTransactionService;
use App\Models\Product;
use Illuminate\Http\Request;

class StockTransactionController extends Controller
{
    protected $transactionService;

    public function __construct(StockTransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        $transactions = $this->transactionService->getAllTransactions();
        $products = Product::all(); 

        return view('admin.transactions.index', compact('transactions', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type'       => 'required|in:in,out,IN,OUT',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string',
        ]);

        // Mengubah "IN" atau "OUT" menjadi "in" atau "out" menggunakan strtolower
        $validated['type'] = strtolower($validated['type']);
        $validated['date'] = now()->toDateString();
        $validated['user_id'] = auth()->id() ?? 1; 

        // Sesuaikan status berdasarkan jenis transaksi agar masuk ke opsi ENUM
        if ($validated['type'] === 'in') {
            $validated['status'] = 'Diterima'; // Jika stok masuk
        } else {
            $validated['status'] = 'Dikeluarkan'; // Jika stok keluar
        }

        $this->transactionService->storeTransaction($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaksi stok berhasil dicatat!');
    }

    // Menggunakan satu fungsi 'print' yang bersih dan memanfaatkan Service
    public function print()
    {
        // Mengambil semua transaksi beserta data produknya dari Service
        $transactions = $this->transactionService->getAllTransactions();
        
        // Melempar data ke halaman view khusus cetak
        return view('admin.transactions.print', compact('transactions'));
    }
}