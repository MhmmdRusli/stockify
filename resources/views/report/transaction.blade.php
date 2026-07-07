@extends('layouts.dashboard')

@section('content')
<div class="p-4 md:p-6 max-w-[1400px] mx-auto w-full space-y-6 font-sans antialiased text-base text-gray-700 dark:text-gray-300">

    {{-- TOP BAR: Title & Metadata Area --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
        <div>
            <h1 class="text-2xl font-normal tracking-tight text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600 font-normal text-3xl">swap_horizontal_circle</span> 
                Laporan Arus Log Barang Masuk & Keluar
            </h1>
            <p class="text-sm font-light text-gray-500 dark:text-gray-400 mt-1.5">Sistem rekam log mutasi, penyesuaian kuantitas stok, dan status validasi SOP gudang.</p>
        </div>
        
        <div class="text-sm text-gray-400 dark:text-gray-500 font-medium bg-gray-50 dark:bg-gray-700/50 px-4 py-2.5 rounded-lg border border-gray-100 dark:border-gray-600">
            Zona Waktu: <span class="text-gray-700 dark:text-gray-200 font-semibold">WIB (Jakarta)</span>
        </div>
    </div>

    {{-- BENTO GRID: Ringkasan Aktivitas Mutasi --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Transaksi Log</p>
                <h3 class="text-3xl font-black text-gray-900 dark:text-white">{{ $transactions->count() }} <span class="text-sm font-normal text-gray-400">Log</span></h3>
                <span class="text-xs text-gray-400 font-medium">Akumulasi keseluruhan</span>
            </div>
            <div class="p-3.5 bg-blue-50 dark:bg-gray-700 rounded-lg text-blue-600 dark:text-blue-400">
                <span class="material-symbols-outlined text-[32px]">receipt_long</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Aktivitas Masuk (In)</p>
                <h3 class="text-3xl font-black text-emerald-600 dark:text-emerald-400">
                    {{ $transactions->where('type', 'in')->count() }} <span class="text-sm font-normal text-gray-400">SOP</span>
                </h3>
                <span class="inline-flex items-center text-xs text-emerald-600 font-semibold bg-emerald-50 dark:bg-emerald-950/30 px-2 py-0.5 rounded">
                    +{{ $transactions->where('type', 'in')->sum('quantity') }} Item
                </span>
            </div>
            <div class="p-3.5 bg-emerald-50 dark:bg-gray-700 rounded-lg text-emerald-600 dark:text-emerald-400">
                <span class="material-symbols-outlined text-[32px]">login</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Aktivitas Keluar (Out)</p>
                <h3 class="text-3xl font-black text-rose-600 dark:text-rose-400">
                    {{ $transactions->where('type', 'out')->count() }} <span class="text-sm font-normal text-gray-400">SOP</span>
                </h3>
                <span class="inline-flex items-center text-xs text-rose-600 font-semibold bg-rose-50 dark:bg-rose-950/30 px-2 py-0.5 rounded">
                    -{{ $transactions->where('type', 'out')->sum('quantity') }} Item
                </span>
            </div>
            <div class="p-3.5 bg-rose-50 dark:bg-gray-700 rounded-lg text-rose-600 dark:text-rose-400">
                <span class="material-symbols-outlined text-[32px]">logout</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Belum Terverifikasi</p>
                <h3 class="text-3xl font-black text-amber-500 dark:text-amber-400">
                    {{ $transactions->where('status', 'Pending')->count() }} <span class="text-sm font-normal text-gray-400">Antrean</span>
                </h3>
                <span class="text-xs text-amber-500 font-semibold animate-pulse">Butuh pengecekan staff</span>
            </div>
            <div class="p-3.5 bg-amber-50 dark:bg-gray-700 rounded-lg text-amber-500 dark:text-amber-400">
                <span class="material-symbols-outlined text-[32px]">hourglass_empty</span>
            </div>
        </div>
    </div>

    {{-- MAIN TABLE CONTAINER WITH TOOLBAR --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        
        {{-- Toolbar Filter Terintegrasi --}}
        <div class="p-5 bg-gray-50/70 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 flex flex-col lg:flex-row justify-between items-center gap-4">
            
            {{-- Search & Tipe Filter Button --}}
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full lg:w-auto">
                <div class="relative w-full sm:w-72">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xl">search</span>
                    <input type="text" id="trxSearchInput" placeholder="Cari nama produk..." class="w-full pl-10 pr-4 py-2 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-gray-700 dark:text-gray-200 shadow-xs">
                </div>
                
                {{-- Quick Type Filters --}}
                <div class="flex items-center gap-2 w-full sm:w-auto overflow-x-auto">
                    <button onclick="filterType('ALL')" class="type-filter-btn px-4 py-2 rounded-lg bg-gray-900 text-white dark:bg-gray-200 dark:text-gray-900 text-xs font-bold uppercase tracking-wider transition shadow-sm">Semua Arus</button>
                    <button onclick="filterType('MASUK')" class="type-filter-btn px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 text-xs font-bold uppercase tracking-wider transition">Masuk</button>
                    <button onclick="filterType('KELUAR')" class="type-filter-btn px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 text-xs font-bold uppercase tracking-wider transition">Keluar</button>
                </div>
            </div>

            {{-- Counter --}}
            <div class="text-sm text-gray-500 dark:text-gray-400 w-full lg:w-auto text-left lg:text-right font-semibold bg-white dark:bg-gray-900 px-3 py-1.5 rounded-lg border border-gray-100 dark:border-gray-800">
                Menampilkan <span id="visibleTrxCount" class="font-black text-blue-600 dark:text-blue-400">{{ $transactions->count() }}</span> rekaman mutasi
            </div>
        </div>

        {{-- Table Layout --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400 border-collapse min-w-[1000px]">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700 border-b border-gray-100 dark:border-gray-700 tracking-wider font-bold">
                    <tr>
                        <th class="px-6 py-5 w-[20%]">Waktu / Tanggal</th>
                        <th class="px-6 py-5 w-[35%]">Identitas Komoditas</th>
                        <th class="px-6 py-5 text-center w-[15%]">Tipe Arus</th>
                        <th class="px-6 py-5 text-right w-[15%]">Volume Jumlah</th>
                        <th class="px-6 py-5 text-center w-[15%]">Status Verifikasi</th>
                    </tr>
                </thead>
                <tbody id="trxTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($transactions as $trx)
                    @php
                        $rawType = $trx->type === 'in' ? 'MASUK' : 'KELUAR';
                    @endphp
                    <tr class="trx-row-item hover:bg-gray-50/80 dark:hover:bg-gray-700/30 transition-colors group" data-type="{{ $rawType }}">
                        
                        <td class="px-6 py-6 whitespace-nowrap font-semibold text-gray-600 dark:text-gray-400">
                            {{ $trx->date }}
                        </td>
                        
                        <td class="px-6 py-6 whitespace-nowrap">
                            <div class="flex flex-col gap-1.5">
                                <span class="font-bold text-gray-900 dark:text-white text-base group-hover:text-blue-600 transition-colors target-product-name">{{ $trx->product->name ?? 'Produk Terhapus' }}</span>
                                <span class="text-xs text-gray-400 dark:text-gray-500 font-mono tracking-wide">ID-TRX #{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </td>
                        
                        <td class="px-6 py-6 whitespace-nowrap text-center">
                            @if($trx->type === 'in')
                                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 text-xs font-bold uppercase rounded-lg bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900">
                                    <span class="material-symbols-outlined text-[14px] font-bold">arrow_downward</span> Masuk
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 text-xs font-bold uppercase rounded-lg bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-400 border border-rose-200 dark:border-rose-900">
                                    <span class="material-symbols-outlined text-[14px] font-bold">arrow_upward</span> Keluar
                                </span>
                            @endif
                        </td>
                        
                        <td class="px-6 py-6 whitespace-nowrap text-right font-black text-base {{ $trx->type === 'in' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $trx->type === 'in' ? '+' : '-' }}{{ number_format($trx->quantity, 0, ',', '.') }}
                        </td>
                        
                        <td class="px-6 py-6 whitespace-nowrap text-center">
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-xl
                                {{ $trx->status === 'Diterima' || $trx->status === 'Dikeluarkan' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900' : ($trx->status === 'Pending' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border border-amber-200 dark:border-amber-900 animate-pulse' : 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300 border border-rose-200 dark:border-rose-900') }}">
                                <span class="w-2 h-2 rounded-full {{ $trx->status === 'Diterima' || $trx->status === 'Dikeluarkan' ? 'bg-emerald-500' : ($trx->status === 'Pending' ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                                {{ $trx->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-gray-400 dark:text-gray-500 font-medium">
                            <span class="material-symbols-outlined text-5xl block mb-2 opacity-40">swap_horizontal_circle</span>
                            Belum ada riwayat aktivitas mutasi barang masuk atau keluar.
                        </td>
                    </tr>
                    @endforelse

                    <tr id="trxNoResultRow" class="hidden">
                        <td colspan="5" class="px-6 py-16 text-center text-gray-400 dark:text-gray-500 font-medium">
                            <span class="material-symbols-outlined text-5xl block mb-2 opacity-40">search_off</span>
                            Data log mutasi barang tidak ditemukan.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let activeTypeFilter = 'ALL';

    document.getElementById('trxSearchInput').addEventListener('input', function() {
        runLogFiltering();
    });

    function filterType(type) {
        activeTypeFilter = type;
        
        const buttons = document.querySelectorAll('.type-filter-btn');
        buttons.forEach(btn => {
            if(btn.innerText.toUpperCase().includes(type) || (type === 'ALL' && btn.innerText.includes('SEMUA'))) {
                btn.className = "type-filter-btn px-4 py-2 rounded-lg bg-gray-900 text-white dark:bg-gray-200 dark:text-gray-900 text-xs font-bold uppercase tracking-wider transition shadow-sm";
            } else {
                btn.className = "type-filter-btn px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 text-xs font-bold uppercase tracking-wider transition";
            }
        });

        runLogFiltering();
    }

    function runLogFiltering() {
        const query = document.getElementById('trxSearchInput').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.trx-row-item');
        let counter = 0;

        rows.forEach(row => {
            const productName = row.querySelector('.target-product-name').innerText.toLowerCase();
            const rowType = row.getAttribute('data-type');

            const matchSearch = productName.includes(query);
            const matchType = (activeTypeFilter === 'ALL') || (rowType === activeTypeFilter);

            if(matchSearch && matchType) {
                row.classList.remove('hidden');
                counter++;
            } else {
                row.classList.add('hidden');
            }
        });

        document.getElementById('visibleTrxCount').innerText = counter;

        const fallbackRow = document.getElementById('trxNoResultRow');
        if (fallbackRow) {
            if (counter === 0 && rows.length > 0) {
                fallbackRow.classList.remove('hidden');
            } else {
                fallbackRow.classList.add('hidden');
            }
        }
    }
</script>
@endsection