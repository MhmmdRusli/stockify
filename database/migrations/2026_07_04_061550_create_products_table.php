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
        Schema::create('products', function (Blueprint $table) {
    $table->id();
    // Relasi ke tabel categories dan suppliers
    $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
    $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
    
    $table->string('name');
    $table->string('sku')->unique(); // Stock Keeping Unit bersifat unik
    $table->text('description')->nullable();
    $table->decimal('purchase_price', 10, 2); // Harga beli
    $table->decimal('selling_price', 10, 2);  // Harga jual
    $table->string('image')->nullable();      // Path file gambar
    $table->integer('minimum_stock');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
