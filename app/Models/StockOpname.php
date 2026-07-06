<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'system_stock',
        'physical_stock',
        'difference',
        'notes'
    ];

    // 1. Relasi ke Product (Pastikan ini sudah ada)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // 2. TAMBAHKAN INI: Relasi ke User agar error-nya hilang
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}