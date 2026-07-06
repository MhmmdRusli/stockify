<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'supplier_id', 'name', 'sku', 
        'description', 'purchase_price', 'selling_price', 
        'image', 'minimum_stock'
    ];

    // Relasi balik: Produk ini dimiliki oleh sebuah Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi balik: Produk ini disuplai oleh sebuah Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relasi: Satu Produk memiliki banyak Atribut (Warna, Ukuran, dll)
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    // Relasi: Satu Produk memiliki banyak riwayat Transaksi Stok
    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }
}