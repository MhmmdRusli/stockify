@extends('layouts.dashboard')

@section('content')
<div class="p-6 space-y-6 bg-gray-50/50 dark:bg-gray-950 min-h-screen">

    {{-- 1. HEADER HALAMAN & TOMBOL AKSI --}}
    <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="text-left">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Manajemen Data Produk</h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola stok, harga, dan informasi produk gudang Anda.</p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            
            {{-- FIX: Sembunyikan tombol Export Excel jika user adalah Staff Gudang --}}
            @if(Auth::check() && Auth::user()->role !== 'Staff Gudang')
                <button type="button" onclick="window.location.href='{{ route('products.export') }}'" class="inline-flex items-center gap-2 text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 font-semibold rounded-xl text-sm px-4 py-2.5 border border-gray-200 shadow-2xs transition-colors">
                    <span class="material-symbols-outlined text-sm font-bold text-gray-500 dark:text-gray-400">download</span>
                    Export Excel
                </button>
            @endif

            @if(Auth::check() && Auth::user()->role !== 'Staff Gudang')
                {{-- Tombol Tambah Produk - TETAP KUNING / AMBER --}}
                <button type="button" data-modal-target="add-product-modal" data-modal-toggle="add-product-modal" class="inline-flex items-center gap-2 text-white bg-amber-500 hover:bg-amber-600 font-semibold rounded-xl text-sm px-4 py-2.5 shadow-xs transition-colors">
                    <span class="material-symbols-outlined text-sm font-bold">add</span>
                    Tambah Produk Manual
                </button>
            @endif
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
    @php
        $marginAvg = $products->count() > 0
            ? $products->avg(function ($p) {
                return $p->selling_price > 0 ? (($p->selling_price - $p->purchase_price) / $p->selling_price) * 100 : 0;
            })
            : 0;
        $kategoriTerhubung = $products->pluck('category_id')->unique()->count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Produk Terdaftar</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $products->count() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <span class="material-symbols-outlined text-xl">inventory_2</span>
            </div>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Kategori Terhubung</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $kategoriTerhubung }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/40 flex items-center justify-center text-amber-600 dark:text-amber-400">
                <span class="material-symbols-outlined text-xl">category</span>
            </div>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Rata-rata Margin</p>
                <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($marginAvg, 1) }}%</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <span class="material-symbols-outlined text-xl">trending_up</span>
            </div>
        </div>
    </div>

    {{-- 3. IMPORT MASSAL --}}
    @if(Auth::check() && Auth::user()->role !== 'Staff Gudang')
    <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                <span class="material-symbols-outlined text-xl">upload_file</span>
            </div>
            <div>
                <h2 class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Import Massal</h2>
                <p class="text-[11px] text-gray-400 dark:text-gray-500">Unggah format file .xlsx</p>
            </div>
        </div>
        <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="w-full sm:w-auto flex items-center gap-2">
            @csrf
            <input type="file" name="file" required class="block text-xs text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 focus:outline-none p-1 max-w-[200px] sm:max-w-xs">
            <button type="submit" class="inline-flex items-center justify-center text-white bg-emerald-600 hover:bg-emerald-700 font-semibold rounded-lg text-xs px-3 py-2 transition-colors">
                Unggah
            </button>
        </form>
    </div>
    @endif

    {{-- 4. INPUT CARI DATA --}}
    <div class="relative w-full">
        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 dark:text-gray-500">
            <span class="material-symbols-outlined text-lg">search</span>
        </span>
        <input type="text" id="productSearchInput" placeholder="Cari produk berdasarkan nama atau SKU..." class="w-full pl-11 pr-4 py-3 text-xs font-medium rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-2xs placeholder:text-gray-400">
    </div>

    {{-- 5. KONTEN TABEL DATA PRODUK --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700 text-left">
                <thead class="bg-gray-50/75 dark:bg-gray-700/50 text-gray-400 dark:text-gray-400 font-bold uppercase text-[11px] tracking-wider border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4">Nama Produk</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Supplier</th>
                        <th class="px-6 py-4 text-right">Harga Beli</th>
                        <th class="px-6 py-4 text-right">Harga Jual</th>
                        <th class="px-6 py-4 text-center">Min. Stok</th>
                        @if(Auth::check() && Auth::user()->role !== 'Staff Gudang')
                        <th class="px-6 py-4 text-right pr-10 w-44">Aksi Pengelolaan</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50/40 dark:hover:bg-gray-700/20 transition-colors">
                        <td class="px-6 py-5 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 dark:bg-blue-950/30 dark:text-blue-400 font-bold text-xs flex items-center justify-center uppercase shadow-xs shrink-0 border border-blue-100 dark:border-blue-900/50">
                                    {{ substr($product->name, 0, 2) }}
                                </div>
                                <div class="space-y-0.5">
                                    <span class="block text-sm font-bold text-gray-900 dark:text-white">{{ $product->name }}</span>
                                    <span class="block text-[10px] font-mono text-gray-400 dark:text-gray-500">{{ $product->sku }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-1 rounded-lg dark:bg-gray-700 dark:text-gray-300">
                                {{ $product->category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 truncate max-w-[150px]">
                            {{ $product->supplier->name ?? '-' }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-right">
                            Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-sm font-semibold text-emerald-600 dark:text-emerald-400 text-right">
                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-center">
                            <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-full bg-amber-50 text-amber-800 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-100/60 dark:border-amber-900/30">
                                {{ $product->minimum_stock }}
                            </span>
                        </td>
                        @if(Auth::check() && Auth::user()->role !== 'Staff Gudang')
                        <td class="px-6 py-5 whitespace-nowrap text-right pr-8 space-x-1">
                            <button type="button" data-modal-target="edit-product-modal-{{ $product->id }}" data-modal-toggle="edit-product-modal-{{ $product->id }}" class="p-2 text-amber-600 hover:text-amber-700 bg-amber-50 hover:bg-amber-100/80 rounded-xl transition-colors inline-flex items-center justify-center border border-amber-100 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/50">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </button>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus produk ini?')" class="p-2 text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100/80 rounded-xl transition-colors inline-flex items-center justify-center border border-red-100 dark:bg-red-950/20 dark:text-red-400 dark:border-red-900/50">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::check() && Auth::user()->role === 'Staff Gudang' ? '6' : '7' }}" class="px-6 py-12 text-center text-sm font-medium text-gray-400 dark:text-gray-500">
                            Belum ada data produk yang terdaftar di sistem.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(Auth::check() && Auth::user()->role !== 'Staff Gudang')
{{-- 6. MODAL EDIT PRODUK --}}
@foreach($products as $product)
<div id="edit-product-modal-{{ $product->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full bg-gray-900/40 backdrop-blur-xs flex items-center justify-center">
    <div class="relative w-full max-w-2xl max-h-full bg-white rounded-2xl shadow-xl dark:bg-gray-800 p-6 border border-gray-100 dark:border-gray-700 mx-auto mt-10">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4 mb-5">
            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-500">edit_note</span>
                Edit Produk: <span class="text-blue-600 dark:text-blue-400 font-mono text-sm">{{ $product->sku }}</span>
            </h3>
            <button type="button" data-modal-toggle="edit-product-modal-{{ $product->id }}" class="text-gray-400 hover:text-gray-500 flex items-center"><span class="material-symbols-outlined">close</span></button>
        </div>

        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid gap-4 mb-5 grid-cols-1 sm:grid-cols-2 text-left">
                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nama Produk</label>
                    <input type="text" name="name" value="{{ $product->name }}" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" required>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Kategori</label>
                    <select name="category_id" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Supplier</label>
                    <select name="supplier_id" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" required>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Harga Beli (Rp)</label>
                    <input type="number" name="purchase_price" value="{{ $product->purchase_price }}" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" required>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Harga Jual (Rp)</label>
                    <input type="number" name="selling_price" value="{{ $product->selling_price }}" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" required>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Minimum Stok</label>
                    <input type="number" name="minimum_stock" value="{{ $product->minimum_stock }}" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" required>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-300 dark:text-gray-500 uppercase tracking-wider mb-1.5">SKU (Permanen)</label>
                    <input type="text" name="sku" value="{{ $product->sku }}" class="w-full rounded-xl border-gray-100 text-xs bg-gray-50 text-gray-400 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-500 py-2.5 font-mono cursor-not-allowed" readonly>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end space-x-2">
                <button type="button" data-modal-toggle="edit-product-modal-{{ $product->id }}" class="px-4 py-2.5 text-xs font-semibold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Batal</button>
                <button type="submit" class="px-4 py-2.5 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- 7. MODAL TAMBAH PRODUK BARU --}}
<div id="add-product-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full bg-gray-900/40 backdrop-blur-xs flex items-center justify-center">
    <div class="relative w-full max-w-lg max-h-full bg-white rounded-2xl shadow-xl dark:bg-gray-800 p-6 border border-gray-100 dark:border-gray-700 mx-auto mt-10">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4 mb-5">
            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-500">playlist_add</span> Tambah Produk Baru
            </h3>
            <button type="button" data-modal-toggle="add-product-modal" class="text-gray-400 hover:text-gray-500 flex items-center"><span class="material-symbols-outlined">close</span></button>
        </div>

        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            <div class="grid gap-4 mb-5 grid-cols-1 sm:grid-cols-2 text-left">
                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nama Produk</label>
                    <input type="text" name="name" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" placeholder="Contoh: Kulkas Sharp 2 Pintu" required>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Kategori</label>
                    <select name="category_id" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Supplier</label>
                    <select name="supplier_id" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" required>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Harga Beli</label>
                    <input type="number" name="purchase_price" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" placeholder="3000000" required>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Harga Jual</label>
                    <input type="number" name="selling_price" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" placeholder="3500000" required>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Minimum Stok</label>
                    <input type="number" name="minimum_stock" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" placeholder="5" required>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">SKU (Opsional)</label>
                    <input type="text" name="sku" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5 font-mono" placeholder="Otomatis jika kosong">
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end space-x-2">
                <button type="button" data-modal-toggle="add-product-modal" class="px-4 py-2.5 text-xs font-semibold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Batal</button>
                <button type="submit" class="px-4 py-2.5 text-xs font-semibold text-white bg-amber-500 hover:bg-amber-600 rounded-xl shadow-xs">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- 8. SCRIPT LIVE PENCARIAN --}}
<script>
    document.getElementById('productSearchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>
@endsection