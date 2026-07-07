@extends('layouts.dashboard')

@section('content')

<style>
    .rak-ticket {
        position: relative;
        border-left: 3px dashed rgba(245,166,35,0.45);
    }
    .rak-tag {
        font-family: 'JetBrains Mono', monospace;
        letter-spacing: 0.12em;
    }
    .font-display { font-family: 'Space Grotesk', sans-serif; }
    .barcode-strip span {
        display: inline-block;
        width: 1px;
        background-color: currentColor;
    }
</style>

<div class="p-5 bg-white border-b border-gray-100 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700/60">
    <div class="grid grid-cols-1 w-full">
        <div class="text-left">
            <h1 class="font-display text-xl font-bold text-gray-900 sm:text-2xl dark:text-white tracking-tight">
                Dashboard <span class="text-amber-500 dark:text-amber-400">{{ Auth::user()->role }}</span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Selamat datang kembali, <span class="font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>. Berikut ringkasan aktivitas kerja Anda.
            </p>
        </div>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- 🛡️ 1. TAMPILAN DASHBOARD: ADMIN --}}
{{-- ========================================================================= --}}
@if(Auth::user()->role === 'Admin')
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mt-4">
    {{-- KARTU 1: TOTAL PRODUK --}}
    <div class="rak-ticket group relative p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm hover:shadow-lg border border-gray-100 dark:border-gray-700/60 overflow-hidden transition-all duration-300 hover:-translate-y-1">
        <span class="rak-tag absolute top-0 right-0 bg-[#101826] text-amber-400 text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg">RAK-01</span>
        <p class="rak-tag text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase mb-2">Total Jenis Produk</p>
        <div class="flex items-end justify-between">
            <h3 class="font-display text-4xl font-bold text-gray-900 dark:text-white tracking-tight">
                {{ number_format($totalProducts, 0, ',', '.') }}
                <span class="rak-tag text-sm font-medium text-gray-400 align-middle">SKU</span>
            </h3>
            <div class="p-3 rounded-xl bg-gradient-to-br from-[#1E293B] to-[#101826] text-amber-400 shadow-md group-hover:scale-105 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
        </div>
    </div>

    {{-- KARTU 2: STOK MASUK --}}
    <div class="rak-ticket group relative p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm hover:shadow-lg border border-gray-100 dark:border-gray-700/60 overflow-hidden transition-all duration-300 hover:-translate-y-1" style="border-left-color: rgba(20,184,166,0.5)">
        <span class="rak-tag absolute top-0 right-0 bg-teal-600 text-white text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg">MASUK</span>
        <p class="rak-tag text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase mb-2">Akumulasi Stok Masuk</p>
        <div class="flex items-end justify-between">
            <h3 class="font-display text-4xl font-bold text-teal-600 dark:text-teal-400 tracking-tight">
                +{{ number_format($totalFormatIn, 0, ',', '.') }}
                <span class="rak-tag text-sm font-medium text-gray-400 align-middle">Pcs</span>
            </h3>
            <div class="p-3 rounded-xl bg-gradient-to-br from-teal-500 to-teal-700 text-white shadow-md group-hover:scale-105 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
        </div>
    </div>

    {{-- KARTU 3: STOK KELUAR --}}
    <div class="rak-ticket group relative p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm hover:shadow-lg border border-gray-100 dark:border-gray-700/60 overflow-hidden transition-all duration-300 hover:-translate-y-1" style="border-left-color: rgba(244,63,94,0.5)">
        <span class="rak-tag absolute top-0 right-0 bg-rose-600 text-white text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg">KELUAR</span>
        <p class="rak-tag text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase mb-2">Akumulasi Stok Keluar</p>
        <div class="flex items-end justify-between">
            <h3 class="font-display text-4xl font-bold text-rose-600 dark:text-rose-400 tracking-tight">
                -{{ number_format($totalFormatOut, 0, ',', '.') }}
                <span class="rak-tag text-sm font-medium text-gray-400 align-middle">Pcs</span>
            </h3>
            <div class="p-3 rounded-xl bg-gradient-to-br from-rose-500 to-rose-700 text-white shadow-md group-hover:scale-105 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path></svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mt-4">
    {{-- DISPATCH BOARD (CHART) --}}
    <div class="lg:col-span-2 p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60">
        <div class="flex items-center justify-between mb-1">
            <div>
                <p class="rak-tag text-[10px] font-semibold text-amber-500 uppercase">Log // Weekly Throughput</p>
                <h3 class="font-display text-base font-bold text-gray-900 dark:text-white tracking-tight">Tren Transaksi 7 Hari Terakhir</h3>
            </div>
        </div>
        <div class="relative w-full h-64 mt-3">
            <canvas id="weeklyChart"></canvas>
        </div>
        <div class="barcode-strip flex items-center gap-[2px] mt-4 opacity-40 text-gray-400 dark:text-gray-600">
            @for ($i = 0; $i < 60; $i++)
                <span style="height: {{ rand(4,12) }}px;"></span>
            @endfor
        </div>
    </div>

    {{-- MONITORING STOK --}}
    <div class="rak-ticket p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 transition-shadow hover:shadow-md relative overflow-hidden" style="border-left-color: rgba(245,166,35,0.5)">
        <span class="rak-tag absolute top-0 right-0 bg-[#101826] text-amber-400 text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg">MIN-STOK</span>
        <h3 class="font-display text-sm font-bold text-gray-900 dark:text-white mb-1 tracking-tight">Monitoring Batas Stok Terbaru</h3>
        <p class="rak-tag text-[10px] text-gray-400 dark:text-gray-500 mb-3">Produk mendekati batas minimum</p>
        <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
            @forelse($lowStockProducts as $product)
            <div class="flex items-center gap-3 py-2.5 px-1 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors duration-150">
                <span class="flex-shrink-0 w-1.5 h-8 rounded-full bg-gradient-to-b from-orange-400 to-orange-500"></span>
                <div class="flex-1 min-w-0">
                    <p class="rak-tag text-xs font-bold text-gray-800 dark:text-white">{{ $product->sku ?? '-' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[160px]">{{ $product->name }}</p>
                </div>
                <span class="rak-tag flex-shrink-0 bg-orange-50 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300 text-xs font-bold px-2.5 py-1 rounded-full ring-1 ring-orange-200 dark:ring-orange-800">{{ $product->minimum_stock }} Pcs</span>
            </div>
            @empty
            <div class="p-6 text-center text-xs text-gray-400">Belum ada data.</div>
            @endforelse
        </div>
    </div>
</div>
@endif

{{-- ========================================================================= --}}
{{-- 📦 2. TAMPILAN DASHBOARD: MANAJER GUDANG --}}
{{-- ========================================================================= --}}
@if(Auth::user()->role === 'Manajer Gudang')
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mt-4">
    {{-- KARTU 1: TOTAL MASTER PRODUK --}}
    <div class="rak-ticket group relative p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm hover:shadow-lg border border-gray-100 dark:border-gray-700/60 overflow-hidden transition-all duration-300 hover:-translate-y-1">
        <span class="rak-tag absolute top-0 right-0 bg-[#101826] text-amber-400 text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg">RAK-M1</span>
        <p class="rak-tag text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase mb-2">Total Master Produk</p>
        <div class="flex items-end justify-between">
            <h3 class="font-display text-4xl font-bold text-gray-900 dark:text-white tracking-tight">
                {{ $totalProducts }}
                <span class="rak-tag text-sm font-medium text-gray-400 align-middle">SKU</span>
            </h3>
            <div class="p-3 rounded-xl bg-gradient-to-br from-[#1E293B] to-[#101826] text-amber-400 shadow-md group-hover:scale-105 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
        </div>
    </div>

    {{-- KARTU 2: BARANG MASUK HARI INI --}}
    <div class="rak-ticket group relative p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm hover:shadow-lg border border-gray-100 dark:border-gray-700/60 overflow-hidden transition-all duration-300 hover:-translate-y-1" style="border-left-color: rgba(20,184,166,0.5)">
        <span class="rak-tag absolute top-0 right-0 bg-teal-600 text-white text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg">IN-01</span>
        <p class="rak-tag text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase mb-2">Barang Masuk Hari Ini</p>
        <div class="flex items-end justify-between">
            <h3 class="font-display text-4xl font-bold text-teal-600 dark:text-teal-400 tracking-tight">
                +{{ number_format($masukHariIni, 0, ',', '.') }}
                <span class="rak-tag text-sm font-medium text-gray-400 align-middle">Pcs</span>
            </h3>
            <div class="p-3 rounded-xl bg-gradient-to-br from-teal-500 to-teal-700 text-white shadow-md group-hover:scale-105 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
        </div>
    </div>

    {{-- KARTU 3: BARANG KELUAR HARI INI --}}
    <div class="rak-ticket group relative p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm hover:shadow-lg border border-gray-100 dark:border-gray-700/60 overflow-hidden transition-all duration-300 hover:-translate-y-1" style="border-left-color: rgba(244,63,94,0.5)">
        <span class="rak-tag absolute top-0 right-0 bg-rose-600 text-white text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg">OUT-01</span>
        <p class="rak-tag text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase mb-2">Barang Keluar Hari Ini</p>
        <div class="flex items-end justify-between">
            <h3 class="font-display text-4xl font-bold text-rose-600 dark:text-rose-400 tracking-tight">
                -{{ number_format($keluarHariIni, 0, ',', '.') }}
                <span class="rak-tag text-sm font-medium text-gray-400 align-middle">Pcs</span>
            </h3>
            <div class="p-3 rounded-xl bg-gradient-to-br from-rose-500 to-rose-700 text-white shadow-md group-hover:scale-105 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mt-4">
    {{-- DISPATCH BOARD (CHART) --}}
    <div class="lg:col-span-2 p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60">
        <div class="flex items-center justify-between mb-1">
            <div>
                <p class="rak-tag text-[10px] font-semibold text-amber-500 uppercase">Log // Weekly Throughput</p>
                <h3 class="font-display text-base font-bold text-gray-900 dark:text-white tracking-tight">Tren Logistik Sepekan</h3>
            </div>
        </div>
        <div class="relative w-full h-64 mt-3">
            <canvas id="weeklyChart"></canvas>
        </div>
        <div class="barcode-strip flex items-center gap-[2px] mt-4 opacity-40 text-gray-400 dark:text-gray-600">
            @for ($i = 0; $i < 60; $i++)
                <span style="height: {{ rand(4,12) }}px;"></span>
            @endfor
        </div>
    </div>

    {{-- PERINGATAN STOK MENIPIS --}}
    <div class="rak-ticket p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 transition-shadow hover:shadow-md relative overflow-hidden" style="border-left-color: rgba(244,63,94,0.5)">
        <span class="rak-tag absolute top-0 right-0 bg-rose-600 text-white text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg">ALERT</span>
        <h3 class="font-display text-sm font-bold text-rose-600 dark:text-rose-400 mb-1 flex items-center gap-1.5 tracking-tight">
            <span class="inline-block w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
            Peringatan Stok Menipis
        </h3>
        <p class="rak-tag text-[10px] text-gray-400 dark:text-gray-500 mb-3">Perlu tindakan restock</p>
        <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
            @forelse($lowStockProducts as $product)
            <div class="flex items-center gap-3 py-2.5 px-1 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors duration-150">
                <span class="flex-shrink-0 w-1.5 h-8 rounded-full bg-gradient-to-b from-rose-400 to-rose-600"></span>
                <p class="flex-1 min-w-0 text-xs font-semibold text-gray-800 dark:text-white truncate">{{ $product->name }}</p>
                <span class="rak-tag flex-shrink-0 bg-rose-50 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300 text-xs font-bold px-2.5 py-1 rounded-full ring-1 ring-rose-200 dark:ring-rose-800">{{ $product->minimum_stock }} Pcs</span>
            </div>
            @empty
            <div class="p-6 text-center text-xs text-gray-400">Stok gudang aman terkendali.</div>
            @endforelse
        </div>
    </div>
</div>
@endif

{{-- ========================================================================= --}}
{{-- ⚡ 3. TAMPILAN DASHBOARD: STAFF GUDANG (DAFTAR TUGAS LAPANGAN PENDING) --}}
{{-- ========================================================================= --}}
@if(Auth::user()->role === 'Staff Gudang')
<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">

    {{-- TRANSAKSI MASUK PENDING --}}
    <div class="rak-ticket p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 transition-shadow hover:shadow-md relative overflow-hidden" style="border-left-color: rgba(20,184,166,0.5)">
        <span class="rak-tag absolute top-0 right-0 bg-teal-600 text-white text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg">TASK-IN</span>
        <h3 class="font-display text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2 tracking-tight">
            <span class="w-2 h-2 bg-emerald-500 rounded-full ring-4 ring-emerald-100 dark:ring-emerald-900/40"></span>
            Periksa Barang Masuk Baru
        </h3>
        <div class="overflow-x-auto -mx-1">
            <table class="min-w-full text-xs">
                <thead class="rak-tag text-gray-400 dark:text-gray-500 uppercase font-semibold">
                    <tr>
                        <th class="py-2 px-3 text-left">Produk</th>
                        <th class="py-2 px-3 text-center">Qty</th>
                        <th class="py-2 px-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tugasMasuk as $tr)
                    <tr class="odd:bg-transparent even:bg-gray-50/60 dark:even:bg-gray-700/20 hover:bg-amber-50/50 dark:hover:bg-gray-700/40 transition-colors duration-150">
                        <td class="py-2.5 px-3 font-semibold text-gray-800 dark:text-white rounded-l-lg">{{ $tr->product->name }}</td>
                        <td class="py-2.5 px-3 text-center font-bold text-emerald-600">{{ $tr->quantity }} Pcs</td>
                        <td class="py-2.5 px-3 text-center rounded-r-lg">
                            <div class="flex items-center justify-center gap-1.5">
                                <form action="{{ route('transactions.konfirmasi', $tr->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold px-3 py-1.5 rounded-lg ring-1 ring-emerald-200 hover:ring-emerald-300 transition-all duration-150 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-800">Terima</button>
                                </form>
                                <form action="{{ route('transactions.tolak', $tr->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold px-3 py-1.5 rounded-lg ring-1 ring-rose-200 hover:ring-rose-300 transition-all duration-150 dark:bg-rose-900/30 dark:text-rose-300 dark:ring-rose-800">Tolak</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="p-6 text-center text-gray-400">Tidak ada tugas barang masuk pending.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TRANSAKSI KELUAR PENDING --}}
    <div class="rak-ticket p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 transition-shadow hover:shadow-md relative overflow-hidden" style="border-left-color: rgba(244,63,94,0.5)">
        <span class="rak-tag absolute top-0 right-0 bg-rose-600 text-white text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg">TASK-OUT</span>
        <h3 class="font-display text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2 tracking-tight">
            <span class="w-2 h-2 bg-rose-500 rounded-full ring-4 ring-rose-100 dark:ring-rose-900/40"></span>
            Siapkan Barang Keluar
        </h3>
        <div class="overflow-x-auto -mx-1">
            <table class="min-w-full text-xs">
                <thead class="rak-tag text-gray-400 uppercase font-semibold">
                    <tr>
                        <th class="py-2 px-3 text-left">Produk</th>
                        <th class="py-2 px-3 text-center">Qty</th>
                        <th class="py-2 px-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tugasKeluar as $tr)
                    <tr class="odd:bg-transparent even:bg-gray-50/60 dark:even:bg-gray-700/20 hover:bg-amber-50/50 dark:hover:bg-gray-700/40 transition-colors duration-150">
                        <td class="py-2.5 px-3 font-semibold text-gray-800 dark:text-white rounded-l-lg">{{ $tr->product->name }}</td>
                        <td class="py-2.5 px-3 text-center font-bold text-rose-600">{{ $tr->quantity }} Pcs</td>
                        <td class="py-2.5 px-3 text-center rounded-r-lg">
                            <div class="flex items-center justify-center gap-1.5">
                                <form action="{{ route('transactions.konfirmasi', $tr->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold px-3 py-1.5 rounded-lg ring-1 ring-blue-200 hover:ring-blue-300 transition-all duration-150 dark:bg-blue-900/30 dark:text-blue-300 dark:ring-blue-800">Kirim</button>
                                </form>
                                <form action="{{ route('transactions.tolak', $tr->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-gray-50 hover:bg-gray-100 text-gray-600 font-semibold px-3 py-1.5 rounded-lg ring-1 ring-gray-200 hover:ring-gray-300 transition-all duration-150 dark:bg-gray-700/40 dark:text-gray-300 dark:ring-gray-600">Batal</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="p-6 text-center text-gray-400">Tidak ada tugas barang keluar pending.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endif

{{-- ========================================================================= --}}
{{-- 📊 SCRIPT CHART.JS (HANYA DI-RENDER JIKALAU YANG LOGIN ADMIN / MANAJER) --}}
{{-- ========================================================================= --}}
@if(Auth::user()->role === 'Admin' || Auth::user()->role === 'Manajer Gudang')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('weeklyChart').getContext('2d');

        const labels = @json($days);
        const dataIn = @json($dataIn);
        const dataOut = @json($dataOut);
        const dataNet = dataIn.map((v, i) => v - dataOut[i]);

        const gradientIn = ctx.createLinearGradient(0, 0, 0, 260);
        gradientIn.addColorStop(0, '#2DD4BF');
        gradientIn.addColorStop(1, 'rgba(45, 212, 191, 0.15)');

        const gradientOut = ctx.createLinearGradient(0, 0, 0, 260);
        gradientOut.addColorStop(0, '#FB7185');
        gradientOut.addColorStop(1, 'rgba(251, 113, 133, 0.15)');

        new Chart(ctx, {
            data: {
                labels: labels,
                datasets: [
                    {
                        type: 'bar',
                        label: 'Barang Masuk',
                        data: dataIn,
                        backgroundColor: gradientIn,
                        borderRadius: 6,
                        borderSkipped: false,
                        barPercentage: 0.55,
                        categoryPercentage: 0.6,
                        order: 2
                    },
                    {
                        type: 'bar',
                        label: 'Barang Keluar',
                        data: dataOut.map(v => -v),
                        backgroundColor: gradientOut,
                        borderRadius: 6,
                        borderSkipped: false,
                        barPercentage: 0.55,
                        categoryPercentage: 0.6,
                        order: 2
                    },
                    {
                        type: 'line',
                        label: 'Net Pergerakan',
                        data: dataNet,
                        borderColor: '#F5A623',
                        borderWidth: 2,
                        borderDash: [4, 3],
                        pointBackgroundColor: '#F5A623',
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        tension: 0.35,
                        fill: false,
                        order: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 7,
                            boxHeight: 7,
                            color: '#64748b',
                            font: { size: 11, weight: '500', family: "'JetBrains Mono', monospace" }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#101826',
                        titleColor: '#F5A623',
                        bodyColor: '#e5e7eb',
                        padding: 10,
                        cornerRadius: 8,
                        titleFont: { size: 12, weight: '600', family: "'Space Grotesk', sans-serif" },
                        bodyFont: { size: 12, family: "'JetBrains Mono', monospace" },
                        callbacks: {
                            label: (item) => `${item.dataset.label}: ${Math.abs(item.raw)} Pcs`
                        }
                    }
                },
                scales: {
                    x: {
                        border: { display: false },
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 11, family: "'JetBrains Mono', monospace" } }
                    },
                    y: {
                        border: { display: false },
                        grid: { color: 'rgba(148, 163, 184, 0.12)', drawTicks: false, borderDash: [3, 3] },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 11, family: "'JetBrains Mono', monospace" },
                            padding: 8,
                            callback: (v) => Math.abs(v)
                        }
                    }
                }
            }
        });
    });
</script>
@endif

@endsection