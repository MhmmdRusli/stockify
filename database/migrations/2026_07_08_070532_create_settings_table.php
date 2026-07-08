<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('settings', function (Blueprint $table) {
        $table->id();
        $table->string('key')->unique(); // Contoh: 'app_name', 'warehouse_location'
        $table->text('value')->nullable();
        $table->timestamps();
    });

    // Masukkan data awal (default) agar tidak kosong saat pertama kali dibuka
    DB::table('settings')->insert([
        ['key' => 'app_name', 'value' => 'Flowbite'],
        ['key' => 'warehouse_location', 'value' => 'Gudang Sentral Jakarta'],
        ['key' => 'default_min_stock', 'value' => '5'],
        ['key' => 'sku_prefix', 'value' => 'PRD-'],
    ]);
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
