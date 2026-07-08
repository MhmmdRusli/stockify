<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        // Cari category_id & supplier_id berdasarkan NAMA (bukan angka ID),
        // karena rules() sudah memastikan nama ini ada di database.
        $category = Category::where('name', $row['kategori'])->first();
        $supplier = Supplier::where('name', $row['supplier'])->first();

        return new Product([
            'name'            => $row['nama_produk'],
            'sku'             => $row['sku'] ?? ('SKU-' . strtoupper(uniqid())),
            'category_id'     => $category->id,
            'supplier_id'     => $supplier->id,
            'purchase_price'  => $row['harga_beli'],
            'selling_price'   => $row['harga_jual'],
            'minimum_stock'   => $row['stok_awal'],
            'description'     => $row['deskripsi'] ?? null,
        ]);
    }

    /**
     * Kolom wajib di file .xlsx (heading row):
     * nama_produk | sku (opsional) | kategori | supplier | harga_beli | harga_jual | stok_awal | deskripsi (opsional)
     *
     * "kategori" dan "supplier" diisi dengan NAMA persis seperti yang ada di menu
     * Data Kategori / Data Supplier (bukan angka ID).
     */
    public function rules(): array
    {
        return [
            'nama_produk' => 'required|string|max:255',
            'sku'         => 'nullable|string|max:50|unique:products,sku',
            'kategori'    => 'required|string|exists:categories,name',
            'supplier'    => 'required|string|exists:suppliers,name',
            'harga_beli'  => 'required|numeric|min:0',
            'harga_jual'  => 'required|numeric|min:0',
            'stok_awal'   => 'required|integer|min:0',
        ];
    }

    public function customValidationAttributes()
    {
        return [
            'nama_produk' => 'Nama Produk',
            'kategori'    => 'Kategori',
            'supplier'    => 'Supplier',
            'harga_beli'  => 'Harga Beli',
            'harga_jual'  => 'Harga Jual',
            'stok_awal'   => 'Stok Awal',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'kategori.exists' => 'Nama kategori tidak ditemukan di sistem. Pastikan penulisannya persis sama seperti di menu Data Kategori.',
            'supplier.exists' => 'Nama supplier tidak ditemukan di sistem. Pastikan penulisannya persis sama seperti di menu Data Supplier.',
        ];
    }
}