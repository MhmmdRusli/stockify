<?php

namespace App\Repositories;

use App\Models\StockOpname;
use App\Models\Product;
use App\Models\StockTransaction; // Tambahkan model ini di atas
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

    // UBAH FUNGSI INI: Sekarang mencatat mutasi ke tabel StockTransaction
    public function updateProductStock($productId, $newStock, $difference)
    {
        // Jika tidak ada selisih, tidak perlu mencatat transaksi mutasi stok
        if ($difference == 0) {
            return null;
        }

        // Tentukan jenis tipe transaksi berdasarkan nilai selisih
        $type = $difference > 0 ? 'in' : 'out';

        // Catat mutasi stok baru
        // Cari bagian ini di dalam fungsi updateProductStock milik StockOpnameRepository:
return StockTransaction::create([
    'product_id' => $productId,
    'user_id'    => Auth::id(),
    'type'       => $type,
    'quantity'   => abs($difference),
    'date'       => now(),
    
    // SESUAIKAN BARIS INI:
    'status'     => $type === 'in' ? 'Diterima' : 'Dikeluarkan', 
    
    'notes'      => 'Penyesuaian otomatis dari Stock Opname'
]);
    }
}