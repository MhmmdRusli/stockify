<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Menentukan kolom apa saja yang boleh diisi secara massal
    protected $fillable = ['name', 'description'];

    // Relasi: Satu Kategori mempunyai Banyak Produk (One to Many)
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}