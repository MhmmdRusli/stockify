<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    // Mengambil semua produk beserta data kategori & supplier relasinya
    public function getAll()
    {
        return Product::with(['category', 'supplier'])->get();
    }

    // Menyimpan produk baru
    public function create(array $data)
    {
        return Product::create($data);
    }

    // Menghapus produk
    public function delete($id)
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }

    public function findById($id)
{
    return \App\Models\Product::findOrFail($id);
}

public function update($id, array $data)
{
    $product = $this->findById($id);
    $product->update($data);
    return $product;
}
}