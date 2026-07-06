<?php

namespace App\Repositories;

use App\Models\StockTransaction;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class StockTransactionRepository
{
    // Mengambil semua riwayat transaksi beserta relasi produknya
    public function getAll()
    {
        return StockTransaction::with('product')->orderBy('created_at', 'desc')->get();
    }

    // Menyimpan transaksi stok dan meng-update stok produk terkait
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            
            // Pengaman ekstra memaksa teks menjadi huruf kecil bersih
            $cleanType = strtolower(trim($data['type']));
            $cleanStatus = strtolower(trim($data['status'] ?? 'completed'));

            // Menggunakan sintaks insert Laravel yang paling aman dari pergeseran kolom
            $transaction = new StockTransaction();
            $transaction->product_id = (int) $data['product_id'];
            $transaction->user_id    = (int) ($data['user_id'] ?? 1);
            $transaction->type       = (string) $cleanType;
            $transaction->quantity   = (int) $data['quantity'];
            $transaction->date       = (string) ($data['date'] ?? now()->toDateString());
            $transaction->status     = (string) $cleanStatus; // Dipaksa string murni huruf kecil
            $transaction->notes      = isset($data['notes']) ? (string) $data['notes'] : null;
            $transaction->save(); // Method save() jauh lebih aman dari error pergeseran array fillable

            // Ambil data produk terkait untuk mutasi stok
            $product = Product::findOrFail($data['product_id']);

            if ($cleanType === 'in') {
                $product->increment('minimum_stock', (int) $data['quantity']);
            } elseif ($cleanType === 'out') {
                $product->decrement('minimum_stock', (int) $data['quantity']);
            }

            return $transaction;
        });
    }
}