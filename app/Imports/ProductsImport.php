<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Product([
            'name'          => $row['nama_produk'],
            'category_id'   => $row['id_kategori'],
            'minimum_stock' => $row['stok_awal'],
            'description'   => $row['deskripsi'] ?? null,
        ]);
    }
}