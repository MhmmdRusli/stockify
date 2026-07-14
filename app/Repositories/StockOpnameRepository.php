<?php

namespace App\Repositories;

use App\Models\StockOpname;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\Auth;

class StockOpnameRepository
{
    public function getAllWithRelations()
    {
        return StockOpname::with(['product', 'user'])->latest()->get();
    }

    public function createOpname(array $data)
    {
        return StockOpname::create($data);
    }

    public function updateProductStock($productId, $newStock, $difference)
    {
        // 1. UPDATE STOK DI TABEL PRODUCTS (Agar angka di sistem berubah)
        $product = Product::find($productId);
        if ($product) {
            $product->update([
                'stock' => $newStock 
            ]);
        }

        // 2. Jika tidak ada selisih, tidak perlu mencatat mutasi
        if ($difference == 0) {
            return null;
        }

        // 3. Tentukan tipe transaksi untuk log
        $type = $difference > 0 ? 'in' : 'out';

        // 4. Catat mutasi ke tabel StockTransaction
        return StockTransaction::create([
            'product_id' => $productId,
            'user_id'    => Auth::id(),
            'type'       => $type,
            'quantity'   => abs($difference),
            'date'       => now(),
            'status'     => $type === 'in' ? 'Diterima' : 'Dikeluarkan',
            'notes'      => 'Penyesuaian otomatis dari Stock Opname'
        ]);
    }
}