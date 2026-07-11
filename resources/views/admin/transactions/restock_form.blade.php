@extends('layouts.dashboard')

@section('content')

<div class="p-6 max-w-2xl mx-auto space-y-5">

    <div class="p-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
            <span class="material-symbols-outlined text-teal-500">assignment_turned_in</span>
            Tugas Restock Barang
        </h1>
        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
            Produk dan supplier di bawah ini sudah ditentukan oleh Manajer Gudang dan <span class="font-semibold text-gray-700 dark:text-gray-300">tidak bisa diubah</span>. Anda hanya perlu mengisi jumlah barang yang datang.
        </p>
    </div>

    <div class="p-5 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2 flex items-center gap-3">
                <span class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    <span class="material-symbols-outlined">inventory_2</span>
                </span>
                <div>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $product->name }}</p>
                    <p class="text-[11px] text-gray-400">{{ $product->sku ?? '-' }}</p>
                </div>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-gray-400 uppercase mb-1">Stok Saat Ini</p>
                <p class="text-sm font-bold text-rose-600 dark:text-rose-400">{{ $product->stock }} Pcs</p>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-gray-400 uppercase mb-1">Batas Minimum</p>
                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $product->minimum_stock }} Pcs</p>
            </div>
            <div class="col-span-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                <p class="text-[11px] font-semibold text-gray-400 uppercase mb-1">Supplier</p>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $product->supplier->name ?? 'Belum ada supplier terdaftar' }}</p>
            </div>
        </div>
    </div>

    <div class="p-5 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
        <form action="{{ route('barang.masuk.restock.store') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="space-y-4">
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase mb-1.5">Jumlah Barang Datang</label>
                    <input type="number" name="quantity" min="1" required autofocus placeholder="Contoh: 30"
                        class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-4 focus:ring-amber-400/20 focus:border-amber-400 py-2.5">
                    @error('quantity')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase mb-1.5">Catatan (opsional)</label>
                    <textarea name="notes" rows="3" placeholder="Contoh: Barang sudah dicek fisik, kondisi baik."
                        class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-4 focus:ring-amber-400/20 focus:border-amber-400 py-2.5"></textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <a href="{{ route('barang.masuk.index') }}" class="px-4 py-2.5 text-xs font-semibold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-xs font-semibold text-white bg-amber-500 hover:bg-amber-600 rounded-xl shadow-sm transition-colors">
                    Kirim Draf ke Manajer
                </button>
            </div>
        </form>
    </div>

</div>
@endsection