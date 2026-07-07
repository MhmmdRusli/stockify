@extends('layouts.dashboard')

@section('content')
<div class="p-4 md:p-6 max-w-[1600px] mx-auto w-full space-y-6 text-sm font-sans antialiased text-gray-700 dark:text-gray-300">

    {{-- TOP BAR: Title & Action Buttons --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xs">
        <div>
            <h1 class="text-2xl font-normal tracking-tight text-gray-900 dark:text-white flex items-center gap-3">
                <span class="material-symbols-outlined text-blue-600 text-3xl font-normal">analytics</span> 
                Laporan Komprehensif Stok Barang
            </h1>
            <p class="text-sm font-light text-gray-500 dark:text-gray-400 mt-1">Diproses secara aktual untuk kebutuhan audit internal dan penataan komoditas gudang.</p>
        </div>
        
        <div class="flex flex-wrap gap-3 w-full lg:w-auto">
            <a href="{{ route('report.stock.excel') }}" class="flex-1 lg:flex-none flex items-center justify-center gap-2 text-white bg-emerald-600 hover:bg-emerald-700 font-bold rounded-xl text-sm px-5 py-3 transition shadow-xs">
                <span class="material-symbols-outlined text-[20px]">description</span>
                Export Excel
            </a>
            <a href="{{ route('report.stock.pdf') }}" class="flex-1 lg:flex-none flex items-center justify-center gap-2 text-white bg-rose-600 hover:bg-rose-700 font-bold rounded-xl text-sm px-5 py-3 transition shadow-xs">
                <span class="material-symbols-outlined text-[20px]">picture_as_pdf</span>
                Cetak PDF
            </a>
        </div>
    </div>

    {{-- BENTO GRID: Ringkasan Status Stok --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xs flex items-center justify-between group hover:border-blue-500 transition-all">
            <div class="space-y-1">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Item Produk</p>
                <h3 class="text-3xl font-black text-gray-900 dark:text-white">{{ $products->count() }} <span class="text-sm font-normal text-gray-400">SKU</span></h3>
                <span class="inline-flex items-center text-xs font-semibold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/30 px-2 py-0.5 rounded-lg mt-1">
                    <span class="material-symbols-outlined text-[14px] mr-0.5 font-bold">trending_up</span> +12% bln ini
                </span>
            </div>
            <div class="p-4 bg-blue-50 dark:bg-gray-700 rounded-xl text-blue-600 dark:text-blue-400">
                <span class="material-symbols-outlined text-[32px]">inventory_2</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xs flex items-center justify-between group hover:border-emerald-500 transition-all">
            <div class="space-y-1">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Stok Kondisi Aman</p>
                <h3 class="text-3xl font-black text-emerald-600 dark:text-emerald-400">
                    {{ $products->where('minimum_stock', '>', 5)->count() }} <span class="text-sm font-normal text-gray-400">Item</span>
                </h3>
                <span class="inline-flex items-center text-xs font-semibold text-gray-500 bg-gray-100 dark:bg-gray-900 px-2 py-0.5 rounded-lg mt-1">
                    Kondisi Stabil
                </span>
            </div>
            <div class="p-4 bg-emerald-50 dark:bg-gray-700 rounded-xl text-emerald-600 dark:text-emerald-400">
                <span class="material-symbols-outlined text-[32px]">check_circle</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xs flex items-center justify-between group hover:border-amber-500 transition-all">
            <div class="space-y-1">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Stok Kritis / Menipis</p>
                <h3 class="text-3xl font-black text-amber-600 dark:text-amber-400">
                    {{ $products->where('minimum_stock', '<=', 5)->where('minimum_stock', '>', 0)->count() }} <span class="text-sm font-normal text-gray-400">Item</span>
                </h3>
                <span class="inline-flex items-center text-xs font-bold text-amber-600 bg-amber-50 dark:bg-amber-950/30 px-2 py-0.5 rounded-lg mt-1 animate-pulse">
                    Butuh Restock
                </span>
            </div>
            <div class="p-4 bg-amber-50 dark:bg-gray-700 rounded-xl text-amber-600 dark:text-amber-400">
                <span class="material-symbols-outlined text-[32px]">warning</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xs flex items-center justify-between group hover:border-rose-500 transition-all">
            <div class="space-y-1">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Stok Kosong</p>
                <h3 class="text-3xl font-black text-rose-600 dark:text-rose-400">
                    {{ $products->where('minimum_stock', '<=', 0)->count() }} <span class="text-sm font-normal text-gray-400">Item</span>
                </h3>
                <span class="inline-flex items-center text-xs font-semibold text-rose-600 bg-rose-50 dark:bg-rose-950/30 px-2 py-0.5 rounded-lg mt-1">
                    Terganggu
                </span>
            </div>
            <div class="p-4 bg-rose-50 dark:bg-gray-700 rounded-xl text-rose-600 dark:text-rose-400">
                <span class="material-symbols-outlined text-[32px]">block</span>
            </div>
        </div>
    </div>

    {{-- TABLE CONTAINER --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        
        {{-- Table Filter Header --}}
        <div class="p-5 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex flex-col xl:flex-row justify-between items-center gap-4">
            
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full xl:w-auto">
                <div class="relative w-full sm:w-80">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xl">search</span>
                    <input type="text" id="searchInput" placeholder="Cari SKU atau nama produk..." class="w-full pl-11 pr-4 py-2.5 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-gray-900 dark:text-white shadow-xs">
                </div>
                
                <div class="flex items-center gap-2 w-full sm:w-auto overflow-x-auto py-1">
                    <button onclick="filterStatus('ALL')" class="status-filter-btn px-4 py-2 rounded-xl bg-gray-900 text-white dark:bg-gray-200 dark:text-gray-900 font-bold text-xs uppercase tracking-wide transition shadow-sm">Semua</button>
                    <button onclick="filterStatus('AMAN')" class="status-filter-btn px-4 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-bold text-xs uppercase tracking-wide transition">Aman</button>
                    <button onclick="filterStatus('MENIPIS')" class="status-filter-btn px-4 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-bold text-xs uppercase tracking-wide transition">Menipis</button>
                    <button onclick="filterStatus('HABIS')" class="status-filter-btn px-4 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-bold text-xs uppercase tracking-wide transition">Habis</button>
                </div>
            </div>

            <div class="text-sm font-semibold text-gray-500 dark:text-gray-400 w-full xl:w-auto text-left xl:text-right bg-white dark:bg-gray-900 px-4 py-2 rounded-xl border border-gray-100 dark:border-gray-800">
                Menampilkan <span id="visibleCount" class="font-black text-blue-600 dark:text-blue-400">{{ $products->count() }}</span> entitas komoditas aktif
            </div>
        </div>

        {{-- Main Table Layout --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 tracking-wider">
                    <tr>
                        <th class="px-6 py-5 w-[25%]">Informasi Produk</th>
                        <th class="px-6 py-5 w-[15%]">Kategori</th>
                        <th class="px-6 py-5 w-[22%]">Indikator Batas</th>
                        <th class="px-6 py-5 w-[13%] text-right">Kuantitas Aktual</th>
                        <th class="px-6 py-5 w-[13%] text-center">Status Gudang</th>
                        <th class="px-6 py-5 w-[12%] text-center">Update Terakhir</th>
                    </tr>
                </thead>
                <tbody id="reportTableBody" class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
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
                    <tr class="table-row-item hover:bg-gray-50/60 dark:hover:bg-gray-900/40 transition-colors group" data-status="{{ $statusType }}">
                        
                        <td class="px-6 py-6 whitespace-nowrap search-target">
                            <div class="flex flex-col gap-1.5">
                                <span class="font-bold text-gray-900 dark:text-white text-base group-hover:text-blue-600 transition-colors target-name">{{ $product->name }}</span>
                                <span class="text-xs text-gray-400 dark:text-gray-500 font-mono tracking-wide target-sku">SKU-{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </td>
                        
                        <td class="px-6 py-6 whitespace-nowrap font-semibold text-gray-700 dark:text-gray-300">
                            {{ $product->category->name ?? 'Umum' }}
                        </td>
                        
                        <td class="px-6 py-6">
                            <div class="flex flex-col gap-2">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 h-2.5 rounded-full overflow-hidden shadow-inner">
                                    <div class="h-full rounded-full {{ $product->minimum_stock <= 5 ? 'bg-amber-500' : 'bg-blue-500' }}" style="width: {{ min(($product->minimum_stock / 50) * 100, 100) }}%"></div>
                                </div>
                                <span class="text-xs text-gray-400 font-medium">Batas Maks: 50 Unit</span>
                            </div>
                        </td>
                        
                        <td class="px-6 py-6 whitespace-nowrap text-right font-black text-gray-900 dark:text-white text-base font-mono">
                            {{ number_format($product->minimum_stock, 0, ',', '.') }}
                        </td>
                        
                        <td class="px-6 py-6 whitespace-nowrap text-center">
                            @if($product->minimum_stock <= 0)
                                <span class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold uppercase tracking-wider text-rose-800 bg-rose-100 dark:bg-rose-950 dark:text-rose-300 rounded-xl border border-rose-200 dark:border-rose-900">
                                    <span class="w-2 h-2 bg-rose-500 rounded-full"></span> Habis
                                </span>
                            @elseif($product->minimum_stock <= 5)
                                <span class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold uppercase tracking-wider text-amber-800 bg-amber-100 dark:bg-amber-950 dark:text-amber-300 rounded-xl border border-amber-200 dark:border-amber-900 animate-pulse">
                                    <span class="w-2 h-2 bg-amber-500 rounded-full"></span> Menipis
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold uppercase tracking-wider text-emerald-800 bg-emerald-100 dark:bg-emerald-950 dark:text-emerald-300 rounded-xl border border-emerald-200 dark:border-emerald-900">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Aman
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-6 whitespace-nowrap text-center text-gray-400 dark:text-gray-500 font-mono font-semibold">
                            {{ $product->updated_at ? $product->updated_at->format('d M Y') : 'Hari ini' }}
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="6" class="px-6 py-16 text-center text-gray-400 dark:text-gray-500 font-medium">
                            <span class="material-symbols-outlined text-5xl block mb-2 opacity-40">inventory</span>
                            Belum ada rekapitulasi data komoditas produk yang terdaftar.
                        </td>
                    </tr>
                    @endforelse

                    <tr id="noSearchResultRow" class="hidden">
                        <td colspan="6" class="px-6 py-16 text-center text-gray-400 dark:text-gray-500 font-medium">
                            <span class="material-symbols-outlined text-5xl block mb-2 opacity-40">search_off</span>
                            Data komoditas yang Anda cari tidak ditemukan.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- JAVASCRIPT RE-ALIGNED FOR UPDATED BUTTON CLASSES --}}
<script>
    let currentStatusFilter = 'ALL';

    document.getElementById('searchInput').addEventListener('input', function() {
        executeFiltering();
    });

    function filterStatus(status) {
        currentStatusFilter = status;
        
        const buttons = document.querySelectorAll('.status-filter-btn');
        buttons.forEach(btn => {
            if(btn.innerText.toUpperCase() === status || (status === 'ALL' && btn.innerText === 'SEMUA')) {
                btn.className = "status-filter-btn px-4 py-2 rounded-xl bg-gray-900 text-white dark:bg-gray-200 dark:text-gray-900 font-bold text-xs uppercase tracking-wide transition shadow-sm";
            } else {
                btn.className = "status-filter-btn px-4 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-bold text-xs uppercase tracking-wide transition";
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