<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Stok AKTUAL di gudang -- berubah otomatis tiap ada transaksi
            // barang masuk/keluar yang dikonfirmasi. Terpisah dari
            // minimum_stock yang cuma ambang batas statis (reminder saja).
            $table->integer('stock')->default(0)->after('minimum_stock');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('stock');
        });
    }
};