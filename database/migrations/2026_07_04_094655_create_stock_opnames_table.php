<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('stock_opnames', function (Blueprint $table) {
        $table->id();
        // Menghubungkan ke tabel produk
        $table->foreignId('product_id')->constrained()->onDelete('cascade');
        $table->integer('system_stock'); // Stok yang tercatat di aplikasi saat itu
        $table->integer('physical_stock'); // Stok asli hasil hitung manual di gudang
        $table->integer('difference'); // Selisihnya (Physical - System)
        $table->text('notes')->nullable(); // Alasan selisih (misal: "3 pcs pecah di rak")
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
    }
};
