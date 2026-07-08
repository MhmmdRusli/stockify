@extends('layouts.dashboard')

@section('content')
<div class="p-6 space-y-6 bg-gray-50/50 dark:bg-gray-950 min-h-screen">

    {{-- 1. HEADER HALAMAN --}}
    <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="text-left">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Manajemen Data Kategori</h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola pengelompokan produk, jenis inventaris, dan klasifikasi barang gudang.</p>
        </div>
        <div class="shrink-0">
            {{-- Tombol Tambah diganti warna Kuning Amber --}}
            <button type="button" data-modal-target="add-category-modal" data-modal-toggle="add-category-modal" class="inline-flex items-center gap-2 text-white bg-amber-500 hover:bg-amber-600 font-semibold rounded-xl text-sm px-4 py-2.5 shadow-xs transition-colors">
                <span class="material-symbols-outlined text-sm font-bold">add</span>
                Tambah Kategori
            </button>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="p-4 text-xs font-semibold text-green-700 bg-green-50 rounded-xl border border-green-100 dark:bg-gray-800 dark:text-green-400 dark:border-gray-700 shadow-2xs">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="p-4 text-xs font-semibold text-red-700 bg-red-50 rounded-xl border border-red-100 dark:bg-gray-800 dark:text-red-400 dark:border-gray-700 shadow-2xs">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 2. KARTU METRIK RINGKASAN --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Jenis Kategori</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $categories->count() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <span class="material-symbols-outlined text-xl">category</span>
            </div>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Kategori Terpadat</p>
                <p class="text-sm font-bold text-gray-900 dark:text-white truncate max-w-[180px]">
                    {{ $categories->sortByDesc(function($c) { return $c->products_count ?? $c->products->count(); })->first()->name ?? '-' }}
                </p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/40 flex items-center justify-center text-amber-600 dark:text-amber-400">
                <span class="material-symbols-outlined text-xl">leaderboard</span>
            </div>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Status Pemetaan</p>
                <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-1 mt-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> 100% Sinkron
                </p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <span class="material-symbols-outlined text-xl">verified_user</span>
            </div>
        </div>
    </div>

    {{-- 3. INPUT CARI DATA --}}
    <div class="relative w-full">
        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 dark:text-gray-500">
            <span class="material-symbols-outlined text-lg">search</span>
        </span>
        <input type="text" id="categorySearchInput" placeholder="Cari kelompok kategori barang..." class="w-full pl-11 pr-4 py-3 text-xs font-medium rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-2xs placeholder:text-gray-400">
    </div>

    {{-- 4. KONTEN TABEL DATA KATEGORI --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700 text-left">
                <thead class="bg-gray-50/75 dark:bg-gray-700/50 text-gray-400 dark:text-gray-400 font-bold uppercase text-[11px] tracking-wider border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4">Nama Kelompok Kategori</th>
                        <th class="px-6 py-4">Volume Produk Terhubung</th>
                        <th class="px-6 py-4 text-right pr-10 w-48">Aksi Pengelolaan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($categories as $category)
                    <tr class="hover:bg-gray-50/40 dark:hover:bg-gray-700/20 transition-colors">
                        {{-- Kolom Kategori dengan Icon Avatar BULAT BIRU --}}
                        <td class="px-6 py-5 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 dark:bg-blue-950/30 dark:text-blue-400 font-bold text-xs flex items-center justify-center uppercase shadow-xs shrink-0 border border-blue-100 dark:border-blue-900/50">
                                    {{ substr($category->name, 0, 2) }}
                                </div>
                                <div class="space-y-0.5">
                                    <span class="block text-sm font-bold text-gray-900 dark:text-white">{{ $category->name }}</span>
                                    <span class="block text-[10px] font-mono text-gray-400 dark:text-gray-500">CAT-ID-{{ $category->id }}</span>
                                </div>
                            </div>
                        </td>
                        {{-- Kolom Jumlah Produk --}}
                        <td class="px-6 py-5 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    {{ $category->products_count ?? $category->products->count() }}
                                </span>
                                <span class="text-xs text-gray-400 dark:text-gray-500">item terdaftar</span>
                            </div>
                        </td>
                        {{-- Kolom Aksi Premium Soft Background --}}
                        <td class="px-6 py-5 whitespace-nowrap text-right pr-8 space-x-1">
                            <button type="button" data-modal-target="edit-category-modal-{{ $category->id }}" data-modal-toggle="edit-category-modal-{{ $category->id }}" class="p-2 text-amber-600 hover:text-amber-700 bg-amber-50 hover:bg-amber-100/80 rounded-xl transition-colors inline-flex items-center justify-center border border-amber-100 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/50">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </button>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus kategori ini? Semua produk di dalam kategori ini akan terpengaruh.')" class="p-2 text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100/80 rounded-xl transition-colors inline-flex items-center justify-center border border-red-100 dark:bg-red-950/20 dark:text-red-400 dark:border-red-900/50">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>

                    {{-- 5. MODAL EDIT KATEGORI --}}
                    <div id="edit-category-modal-{{ $category->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full bg-gray-900/40 backdrop-blur-xs flex items-center justify-center">
                        <div class="relative w-full max-w-md max-h-full bg-white rounded-2xl shadow-xl dark:bg-gray-800 p-6 border border-gray-100 dark:border-gray-700 mx-auto mt-10">
                            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4 mb-5">
                                <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    <span class="material-symbols-outlined text-amber-500">edit_note</span> Modifikasi Nama Kategori
                                </h3>
                                <button type="button" data-modal-toggle="edit-category-modal-{{ $category->id }}" class="text-gray-400 hover:text-gray-500 flex items-center"><span class="material-symbols-outlined">close</span></button>
                            </div>
                            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="space-y-4 text-left">
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nama Kategori</label>
                                        <input type="text" name="name" value="{{ $category->name }}" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" required>
                                    </div>
                                </div>
                                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end space-x-2">
                                    <button type="button" data-modal-toggle="edit-category-modal-{{ $category->id }}" class="px-4 py-2.5 text-xs font-semibold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Batal</button>
                                    <button type="submit" class="px-4 py-2.5 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-sm font-medium text-gray-400 dark:text-gray-500">Belum ada data kategori tersimpan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- 6. MODAL TAMBAH KATEGORI BARU --}}
<div id="add-category-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full bg-gray-900/40 backdrop-blur-xs flex items-center justify-center">
    <div class="relative w-full max-w-md max-h-full bg-white rounded-2xl shadow-xl dark:bg-gray-800 p-6 border border-gray-100 dark:border-gray-700 mx-auto mt-10">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4 mb-5">
            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-500">playlist_add</span> Tambah Kategori Baru
            </h3>
            <button type="button" data-modal-toggle="add-category-modal" class="text-gray-400 hover:text-gray-500 flex items-center"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="space-y-4 text-left">
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nama Kategori</label>
                    <input type="text" name="name" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" placeholder="Contoh: Elektronik, Pakaian, Makanan" required>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end space-x-2">
                <button type="button" data-modal-toggle="add-category-modal" class="px-4 py-2.5 text-xs font-semibold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Batal</button>
                <button type="submit" class="px-4 py-2.5 text-xs font-semibold text-white bg-amber-500 hover:bg-amber-600 rounded-xl shadow-xs">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

{{-- 7. SCRIPT LIVE PENCARIAN REAL-TIME --}}
<script>
    document.getElementById('categorySearchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            let categoryName = row.cells[0] ? row.cells[0].innerText.toLowerCase() : '';
            if (categoryName.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection