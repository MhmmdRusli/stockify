<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasFactory;

    public $timestamps = true; 

    protected $fillable = [
        'product_id', 
        'user_id', 
        'type', 
        'quantity', 
        'date', 
        'status', 
        'notes',
        'approved_by',
        'is_new_product' // 🆕 WAJIB ditambahkan, tanpa ini update() akan diabaikan diam-diam
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }
}