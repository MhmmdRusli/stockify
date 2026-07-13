@extends('layouts.dashboard')

@section('content')
<div class="p-6 space-y-6 bg-gray-50/50 dark:bg-gray-950 min-h-screen">

    {{-- 1. HEADER HALAMAN --}}
    <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="text-left">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-2xl">analytics</span>
                Laporan Komprehensif Stok Barang
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">Diproses secara aktual untuk kebutuhan audit internal dan penataan komoditas gudang.</p>
        </div>
        <div class="flex flex-wrap gap-2 w-full lg:w-auto">
            <a href="{{ route('report.stock.excel') }}" class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 text-white bg-emerald-600 hover:bg-emerald-700 font-semibold rounded-xl text-sm px-4 py-2.5 shadow-xs transition-colors">
                <span class="material-symbols-outlined text-sm">description</span>
                Export Excel
            </a>
            <a href="{{ route('report.stock.pdf') }}" class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 text-white bg-slate-700 hover:bg-slate-800 font-semibold rounded-xl text-sm px-4 py-2.5 shadow-xs transition-colors">
                <span class="material-symbols-outlined text-sm">picture_as_pdf</span>
                Cetak PDF
            </a>
        </div>
    </div>

    {{-- 2. KARTU METRIK RINGKASAN --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Item Produk</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $products->count() }} <span class="text-xs font-normal text-gray-400">SKU</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <span class="material-symbols-outlined text-xl">inventory_2</span>
            </div>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Stok Kondisi Aman</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $products->where('minimum_stock', '>', 5)->count() }} <span class="text-xs font-normal text-gray-400">Item</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <span class="material-symbols-outlined text-xl">check_circle</span>
            </div>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Stok Kritis / Menipis</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $products->where('minimum_stock', '<=', 5)->where('minimum_stock', '>', 0)->count() }} <span class="text-xs font-normal text-gray-400">Item</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/40 flex items-center justify-center text-amber-600 dark:text-amber-400">
                <span class="material-symbols-outlined text-xl">warning</span>
            </div>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Stok Kosong</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $products->where('minimum_stock', '<=', 0)->count() }} <span class="text-xs font-normal text-gray-400">Item</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-950/40 flex items-center justify-center text-red-600 dark:text-red-400">
                <span class="material-symbols-outlined text-xl">block</span>
            </div>
        </div>
    </div>

    {{-- 3. TABEL + TOOLBAR --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs overflow-hidden">

        {{-- Toolbar Filter --}}
        <div class="p-5 bg-gray-50/75 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 flex flex-col xl:flex-row justify-between items-center gap-4">
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full xl:w-auto">
                <div class="relative w-full sm:w-72">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 dark:text-gray-500">
                        <span class="material-symbols-outlined text-lg">search</span>
                    </span>
                    <input type="text" id="searchInput" placeholder="Cari SKU atau nama produk..." class="w-full pl-11 pr-4 py-2.5 text-xs font-medium rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-2xs placeholder:text-gray-400">
                </div>
                
                {{-- Bagian Tombol Filter dengan Atribut Data Warna --}}
                <div class="flex items-center gap-2 w-full sm:w-auto overflow-x-auto py-1">
                    <button onclick="filterStatus('ALL')" data-status-type="ALL" class="status-filter-btn px-4 py-2 rounded-xl bg-blue-600 text-white font-bold text-xs uppercase tracking-wide transition shadow-xs">Semua</button>
                    <button onclick="filterStatus('AMAN')" data-status-type="AMAN" class="status-filter-btn px-4 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-bold text-xs uppercase tracking-wide transition">Aman</button>
                    <button onclick="filterStatus('MENIPIS')" data-status-type="MENIPIS" class="status-filter-btn px-4 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-bold text-xs uppercase tracking-wide transition">Menipis</button>
                    <button onclick="filterStatus('HABIS')" data-status-type="HABIS" class="status-filter-btn px-4 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-bold text-xs uppercase tracking-wide transition">Habis</button>
                </div>
            </div>
            <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 w-full xl:w-auto text-left xl:text-right bg-white dark:bg-gray-900 px-4 py-2 rounded-xl border border-gray-100 dark:border-gray-800">
                Menampilkan <span id="visibleCount" class="font-bold text-blue-600 dark:text-blue-400">{{ $products->count() }}</span> entitas komoditas aktif
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700 text-left min-w-[1000px]">
                <thead class="bg-gray-50/75 dark:bg-gray-700/50 text-gray-400 dark:text-gray-400 font-bold uppercase text-[11px] tracking-wider border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4 w-[25%]">Informasi Produk</th>
                        <th class="px-6 py-4 w-[15%]">Kategori</th>
                        <th class="px-6 py-4 w-[22%]">Indikator Batas</th>
                        <th class="px-6 py-4 w-[13%] text-right">Kuantitas Aktual</th>
                        <th class="px-6 py-4 w-[13%] text-center">Status Gudang</th>
                        <th class="px-6 py-4 w-[12%] text-center">Update Terakhir</th>
                    </tr>
                </thead>
                <tbody id="reportTableBody" class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($products as $product)
                    @php
                        if($product->minimum_stock <= 0) {
                            $statusType = 'HABIS';
                        } elseif($product->minimum_stock <= 5) {
                            $statusType = 'MENIPIS';
                        } else {
                            $statusType = 'AMAN';
                        }
                    @endphp
                    <tr class="table-row-item hover:bg-gray-50/40 dark:hover:bg-gray-700/20 transition-colors group" data-status="{{ $statusType }}">
                        <td class="px-6 py-5 whitespace-nowrap search-target">
                            <div class="flex flex-col gap-1">
                                <span class="font-bold text-gray-900 dark:text-white text-sm group-hover:text-blue-600 transition-colors target-name">{{ $product->name }}</span>
                                <span class="text-[10px] text-gray-400 dark:text-gray-500 font-mono tracking-wide target-sku">SKU-{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-1 rounded-lg dark:bg-gray-700 dark:text-gray-300">
                                {{ $product->category->name ?? 'Umum' }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col gap-2">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 h-2 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $product->minimum_stock <= 5 ? 'bg-amber-500' : 'bg-blue-500' }}" style="width: {{ min(($product->minimum_stock / 50) * 100, 100) }}%"></div>
                                </div>
                                <span class="text-[11px] text-gray-400 font-medium">Batas Maks: 50 Unit</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-right font-bold text-gray-900 dark:text-white text-sm font-mono">
                            {{ number_format($product->minimum_stock, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-center">
                            @if($product->minimum_stock <= 0)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-red-800 bg-red-50 dark:bg-red-950/40 dark:text-red-300 rounded-full border border-red-100 dark:border-red-900/30">
                                    <span class="w-2 h-2 bg-red-500 rounded-full"></span> Habis
                                </span>
                            @elseif($product->minimum_stock <= 5)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-amber-800 bg-amber-50 dark:bg-amber-950/40 dark:text-amber-300 rounded-full border border-amber-100 dark:border-amber-900/30 animate-pulse">
                                    <span class="w-2 h-2 bg-amber-500 rounded-full"></span> Menipis
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 dark:text-emerald-300 rounded-full border border-emerald-100 dark:border-emerald-900/30">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Aman
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-center text-gray-400 dark:text-gray-500 font-mono text-xs font-semibold">
                            {{ $product->updated_at ? $product->updated_at->format('d M Y') : 'Hari ini' }}
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="6" class="px-6 py-12 text-center text-sm font-medium text-gray-400 dark:text-gray-500">
                            <span class="material-symbols-outlined text-4xl block mb-2 opacity-40">inventory</span>
                            Belum ada rekapitulasi data komoditas produk yang terdaftar.
                        </td>
                    </tr>
                    @endforelse

                    <tr id="noSearchResultRow" class="hidden">
                        <td colspan="6" class="px-6 py-12 text-center text-sm font-medium text-gray-400 dark:text-gray-500">
                            <span class="material-symbols-outlined text-4xl block mb-2 opacity-40">search_off</span>
                            Data komoditas yang Anda cari tidak ditemukan.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let currentStatusFilter = 'ALL';

    document.getElementById('searchInput').addEventListener('input', function() {
        executeFiltering();
    });

    function filterStatus(status) {
        currentStatusFilter = status;

        // Map class warna Tailwind ketika tombol berstatus AKTIF sesuai dengan jenis filternya
        const activeStyles = {
            'ALL': 'bg-blue-600 text-white font-bold text-xs uppercase tracking-wide transition shadow-xs',
            'AMAN': 'bg-emerald-600 text-white font-bold text-xs uppercase tracking-wide transition shadow-xs',
            'MENIPIS': 'bg-amber-500 text-white font-bold text-xs uppercase tracking-wide transition shadow-xs',
            'HABIS': 'bg-red-600 text-white font-bold text-xs uppercase tracking-wide transition shadow-xs'
        };

        // Class standar ketika tombol TIDAK AKTIF
        const inactiveStyle = 'status-filter-btn px-4 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-bold text-xs uppercase tracking-wide transition';

        const buttons = document.querySelectorAll('.status-filter-btn');
        buttons.forEach(btn => {
            const btnType = btn.getAttribute('data-status-type');

            if(btnType === status) {
                // Pasang warna unik sesuai statusnya
                btn.className = `status-filter-btn px-4 py-2 rounded-xl ${activeStyles[status]}`;
            } else {
                // Kembalikan ke warna putih/abu-abu netral semula
                btn.className = inactiveStyle;
            }
        });

        executeFiltering();
    }

    function executeFiltering() {
        const query = document.getElementById('searchInput').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.table-row-item');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.querySelector('.target-name').innerText.toLowerCase();
            const sku = row.querySelector('.target-sku').innerText.toLowerCase();
            const status = row.getAttribute('data-status');

            const matchSearch = name.includes(query) || sku.includes(query);
            const matchStatus = (currentStatusFilter === 'ALL') || (status === currentStatusFilter);

            if(matchSearch && matchStatus) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });

        document.getElementById('visibleCount').innerText = visibleCount;

        const noResultRow = document.getElementById('noSearchResultRow');
        if (noResultRow) {
            if(visibleCount === 0 && rows.length > 0) {
                noResultRow.classList.remove('hidden');
            } else {
                noResultRow.classList.add('hidden');
            }
        }
    }
</script>
@endsection