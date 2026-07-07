<?php

namespace App\Exports;

use App\Models\Product;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Product::with('category')->get();
    }

    public function headings(): array
    {
        return [
            'ID Produk',
            'Nama Produk',
            'Kategori',
            'Stok Aktual',
            'Deskripsi'
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->category->name ?? 'Tanpa Kategori',
            $product->minimum_stock, 
            $product->description ?? '-',
        ];
    }
}