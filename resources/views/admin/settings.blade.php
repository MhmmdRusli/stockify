@extends('layouts.dashboard')

@section('content')
<div class="p-6 space-y-6 bg-gray-50/50 dark:bg-gray-900 min-h-screen">
    
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-950 dark:text-white">Pengaturan Aplikasi</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola konfigurasi identitas aplikasi, logo, dan ambang batas stok gudang Anda.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/30">
            {{ session('success') }}
        </div>
    @endif

    {{-- Main Card Layout --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/70 rounded-2xl shadow-sm overflow-hidden">
        
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="divide-y divide-gray-200 dark:divide-gray-700">
            @csrf
            @method('PUT')

            {{-- Bagian 1: Identitas Aplikasi --}}
            <div class="p-6 space-y-6">
                <div class="flex items-center gap-2 text-amber-600 dark:text-amber-400">
                    <span class="material-symbols-outlined font-bold">tune</span>
                    <h2 class="text-lg font-bold">Umum & Identitas</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Aplikasi / Perusahaan</label>
                        {{-- ✨ VALUE DINAMIS DARI DATABASE --}}
                        <input type="text" name="app_name" value="{{ $settings['app_name'] ?? 'Flowbite' }}" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-950 dark:text-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gudang Utama (Lokasi)</label>
                        {{-- ✨ VALUE DINAMIS DARI DATABASE --}}
                        <input type="text" name="warehouse_location" value="{{ $settings['warehouse_location'] ?? 'Gudang Sentral Jakarta' }}" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-950 dark:text-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none">
                    </div>
                </div>

                {{-- Upload Logo --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo Aplikasi</label>
                    <div class="flex items-center gap-6 p-4 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl">
                        <div class="w-16 h-16 bg-amber-500 text-white flex items-center justify-center rounded-xl font-bold shadow-md">
                            <span class="material-symbols-outlined text-3xl">token</span>
                        </div>
                        <div class="space-y-1">
                            <input type="file" name="app_logo" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 dark:file:bg-amber-950/30 dark:file:text-amber-400">
                            <p class="text-xs text-gray-400">Format file PNG atau JPG. Maksimal 2MB.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian 2: Pengaturan Stok & Sistem --}}
            <div class="p-6 space-y-6">
                <div class="flex items-center gap-2 text-amber-600 dark:text-amber-400">
                    <span class="material-symbols-outlined font-bold">inventory_2</span>
                    <h2 class="text-lg font-bold">Aturan Logistik & Stok</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Batas Stok Minimum Global</label>
                        <div class="relative">
                            {{-- ✨ VALUE DINAMIS DARI DATABASE --}}
                            <input type="number" name="default_min_stock" value="{{ $settings['default_min_stock'] ?? '5' }}" class="w-full pl-4 pr-12 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-950 dark:text-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none">
                            <span class="absolute right-4 top-3 text-sm text-gray-400">Item</span>
                        </div>
                        <p class="text-xs text-gray-400">Garis pertahanan awal notifikasi stok menipis jika data produk tidak mengaturnya.</p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Format Kode SKU Otomatis</label>
                        {{-- ✨ VALUE DINAMIS DARI DATABASE --}}
                        <input type="text" name="sku_prefix" value="{{ $settings['sku_prefix'] ?? 'PRD-' }}" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-950 dark:text-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none">
                    </div>
                </div>
            </div>

            {{-- Bagian 3: Tombol Aksi Simpan --}}
            <div class="p-6 bg-gray-50 dark:bg-gray-800/40 flex justify-end gap-3">
                <button type="button" class="px-5 py-2.5 bg-white dark:bg-gray-700 hover:bg-gray-100 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 transition-all">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 shadow-md shadow-amber-500/10 rounded-xl text-sm font-bold text-white transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection