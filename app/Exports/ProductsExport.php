<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
     * Ambil semua data produk beserta relasi kategori & supplier-nya,
     * supaya kita bisa tampilkan NAMA-nya (bukan angka ID) di file Excel.
     */
    public function collection()
    {
        return Product::with(['category', 'supplier'])->get();
    }

    /**
     * Header kolom ini SENGAJA dibuat sama persis dengan kolom yang
     * dibutuhkan ProductsImport, supaya file hasil export ini bisa
     * langsung diedit lalu diupload ulang lewat fitur Import Massal.
     */
    public function headings(): array
    {
        return [
            'nama_produk',
            'sku',
            'kategori',
            'supplier',
            'harga_beli',
            'harga_jual',
            'stok_awal',
            'deskripsi',
        ];
    }

    /**
     * Petakan setiap baris produk ke urutan kolom di atas.
     */
    public function map($product): array
    {
        return [
            $product->name,
            $product->sku,
            $product->category->name ?? '',
            $product->supplier->name ?? '',
            $product->purchase_price,
            $product->selling_price,
            $product->minimum_stock,
            $product->description ?? '',
        ];
    }

    /**
     * Styling ringan biar header terlihat jelas (opsional, bisa dihapus
     * kalau tidak diperlukan).
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F59E0B'],
                ],
            ],
        ];
    }
}