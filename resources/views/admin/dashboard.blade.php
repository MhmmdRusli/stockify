@extends('layouts.dashboard')

@section('content')

<style>
    .rak-ticket {
        position: relative;
        border-left: 3px solid rgba(245,166,35,0.45);
    }
    .rak-tag {
        letter-spacing: 0.03em;
    }
    .barcode-strip span {
        display: inline-block;
        width: 1px;
        background-color: currentColor;
    }
</style>

<div class="p-5 bg-white border-b border-gray-100 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700/60">
    <div class="grid grid-cols-1 w-full">
        <div class="text-left">
            <h1 class="text-xl font-bold text-gray-900 sm:text-2xl dark:text-white tracking-tight">
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
{{-- Fallback aman: kalau controller belum mengirim $recentActivities, ambil di sini langsung --}}
@php
    $recentActivities = $recentActivities ?? \App\Models\StockTransaction::with(['product', 'user'])->latest()->take(4)->get();
@endphp

<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mt-4">
    {{-- KARTU 1: TOTAL PRODUK --}}
    <div class="rak-ticket group relative p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm hover:shadow-lg border border-gray-100 dark:border-gray-700/60 overflow-hidden transition-all duration-300 hover:-translate-y-1">
        <span class="rak-tag absolute top-0 right-0 bg-[#101826] text-amber-400 text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg">RAK-01</span>
        <p class="rak-tag text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase mb-2">Total Jenis Produk</p>
        <div class="flex items-end justify-between">
            <h3 class="text-4xl font-bold text-gray-900 dark:text-white tracking-tight">
                {{ number_format($totalProducts, 0, ',', '.') }}
                <span class="text-sm font-medium text-gray-400 align-middle">SKU</span>
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
            <h3 class="text-4xl font-bold text-teal-600 dark:text-teal-400 tracking-tight">
                +{{ number_format($totalFormatIn, 0, ',', '.') }}
                <span class="text-sm font-medium text-gray-400 align-middle">Pcs</span>
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
            <h3 class="text-4xl font-bold text-rose-600 dark:text-rose-400 tracking-tight">
                -{{ number_format($totalFormatOut, 0, ',', '.') }}
                <span class="text-sm font-medium text-gray-400 align-middle">Pcs</span>
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
                <h3 class="text-base font-bold text-gray-900 dark:text-white tracking-tight">Tren Transaksi 7 Hari Terakhir</h3>
            </div>
        </div>

        <div class="relative w-full mt-3">
            <div id="weekly-throughput-chart"
            data-labels='{{ json_encode($days ?? ["Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min"]) }}'
            data-in='{{ json_encode($dataIn ?? [0, 0, 0, 0, 0, 0, 0]) }}'
            data-out='{{ json_encode($dataOut ?? [0, 0, 0, 0, 0, 0, 0]) }}'
            style="min-height: 280px; width: 100%;"></div>
        </div>

        <div class="barcode-strip flex items-center gap-[2px] mt-4 opacity-40 text-gray-400 dark:text-gray-600">
            @for ($i = 0; $i < 60; $i++)
                <span style="height: {{ rand(4,12) }}px;"></span>
            @endfor
        </div>
    </div>

    {{-- 🆕 AKTIVITAS PENGGUNA TERBARU (menggantikan Monitoring Batas Stok) --}}
    <div class="rak-ticket p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 transition-shadow hover:shadow-md relative overflow-hidden" style="border-left-color: rgba(99,102,241,0.5)">
        <span class="rak-tag absolute top-0 right-0 bg-indigo-600 text-white text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg">LOG-USER</span>
        <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-1 tracking-tight">Aktivitas Pengguna Terbaru</h3>
        <p class="rak-tag text-[10px] text-gray-400 dark:text-gray-500 mb-3">Riwayat transaksi stok terbaru oleh seluruh staff</p>
        <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
            @forelse($recentActivities as $activity)
            @php
                $isIn = $activity->type === 'in';
                $actionLabel = match(true) {
                    $isIn && $activity->status === 'Pending'      => 'mengajukan barang masuk untuk',
                    $isIn && $activity->status === 'Diterima'     => 'barang masuk disetujui untuk',
                    $isIn && $activity->status === 'Ditolak'      => 'barang masuk ditolak untuk',
                    !$isIn && $activity->status === 'Pending'     => 'mengajukan barang keluar untuk',
                    !$isIn && $activity->status === 'Dikeluarkan' => 'barang keluar dikonfirmasi untuk',
                    !$isIn && $activity->status === 'Ditolak'     => 'barang keluar dibatalkan untuk',
                    default => 'memperbarui stok untuk',
                };
            @endphp
            <div class="flex items-center gap-3 py-2.5 px-1 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors duration-150">
                <span class="flex-shrink-0 w-9 h-9 rounded-lg {{ $isIn ? 'bg-teal-50 text-teal-600 dark:bg-teal-950/30 dark:text-teal-400' : 'bg-rose-50 text-rose-600 dark:bg-rose-950/30 dark:text-rose-400' }} flex items-center justify-center font-bold text-xs uppercase">
                    {{ substr($activity->user->name ?? '?', 0, 2) }}
                </span>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-700 dark:text-gray-300">
                        <span class="font-bold text-gray-900 dark:text-white">{{ $activity->user->name ?? 'Pengguna' }}</span>
                        {{ $actionLabel }}
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $activity->product->name ?? 'produk terhapus' }}</span>
                        <span class="{{ $isIn ? 'text-teal-600' : 'text-rose-600' }}">({{ $isIn ? '+' : '-' }}{{ $activity->quantity }} pcs)</span>
                    </p>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">{{ $activity->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-xs text-gray-400">Belum ada aktivitas transaksi.</div>
            @endforelse
        </div>
    </div>
</div>
@endif

{{-- ========================================================================= --}}
{{-- 📦 2. TAMPILAN DASHBOARD: MANAJER GUDANG (Sesuai Spec: Stok Menipis, Masuk & Keluar Hari Ini) --}}
{{-- ========================================================================= --}}
@if(Auth::user()->role === 'Manajer Gudang')
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mt-4">
    {{-- KARTU 1: STOK MENIPIS --}}
    <div class="rak-ticket group relative p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm hover:shadow-lg border border-gray-100 dark:border-gray-700/60 overflow-hidden transition-all duration-300 hover:-translate-y-1" style="border-left-color: rgba(245,166,35,0.5)">
        <span class="rak-tag absolute top-0 right-0 bg-[#101826] text-amber-400 text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg">MENIPIS</span>
        <p class="rak-tag text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase mb-2">Stok Menipis</p>
        <div class="flex items-end justify-between">
            <h3 class="text-4xl font-bold text-amber-500 dark:text-amber-400 tracking-tight">
                {{ $lowStockProducts->count() }}
                <span class="text-sm font-medium text-gray-400 align-middle">Item</span>
            </h3>
            <div class="p-3 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 text-white shadow-md group-hover:scale-105 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path></svg>
            </div>
        </div>
    </div>

    {{-- KARTU 2: BARANG MASUK HARI INI --}}
    <div class="rak-ticket group relative p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm hover:shadow-lg border border-gray-100 dark:border-gray-700/60 overflow-hidden transition-all duration-300 hover:-translate-y-1" style="border-left-color: rgba(20,184,166,0.5)">
        <span class="rak-tag absolute top-0 right-0 bg-teal-600 text-white text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg">IN-01</span>
        <p class="rak-tag text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase mb-2">Barang Masuk Hari Ini</p>
        <div class="flex items-end justify-between">
            <h3 class="text-4xl font-bold text-teal-600 dark:text-teal-400 tracking-tight">
                +{{ number_format($masukHariIni, 0, ',', '.') }}
                <span class="text-sm font-medium text-gray-400 align-middle">Pcs</span>
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
            <h3 class="text-4xl font-bold text-rose-600 dark:text-rose-400 tracking-tight">
                -{{ number_format($keluarHariIni, 0, ',', '.') }}
                <span class="text-sm font-medium text-gray-400 align-middle">Pcs</span>
            </h3>
            <div class="p-3 rounded-xl bg-gradient-to-br from-rose-500 to-rose-700 text-white shadow-md group-hover:scale-105 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
            </div>
        </div>
    </div>
</div>

{{-- DAFTAR PRODUK STOK MENIPIS (pelengkap kartu di atas) --}}
<div class="rak-ticket p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 transition-shadow hover:shadow-md relative overflow-hidden mt-5" style="border-left-color: rgba(245,166,35,0.5)">
    <span class="rak-tag absolute top-0 right-0 bg-[#101826] text-amber-400 text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg">MIN-STOK</span>
    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-1 tracking-tight">Monitoring Batas Stok Terbaru</h3>
    <p class="rak-tag text-[10px] text-gray-400 dark:text-gray-500 mb-3">Produk mendekati batas minimum, perlu tindakan restock</p>
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
        <div class="p-6 text-center text-xs text-gray-400">Stok gudang aman terkendali.</div>
        @endforelse
    </div>
</div>
@endif

{{-- ========================================================================= --}}
{{-- ⚡ 3. TAMPILAN DASHBOARD: STAFF GUDANG --}}
{{-- ========================================================================= --}}
@if(Auth::user()->role === 'Staff Gudang')
{{-- 🚚 TUGAS RESTOCK DARI MANAJER (tampil di dashboard Staff Gudang) --}}
@php
    $restockTasks = Auth::user()->unreadNotifications
        ->where('data.type', 'restock_task');
@endphp

@if($restockTasks->count() > 0)
<div class="rak-ticket p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 relative overflow-hidden mt-4" style="border-left-color: rgba(244,63,94,0.5)">
    <span class="rak-tag absolute top-0 right-0 bg-rose-600 text-white text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg">TASK-RESTOCK</span>
    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2 tracking-tight">
        <span class="w-2 h-2 bg-rose-500 rounded-full ring-4 ring-rose-100 dark:ring-rose-900/40 animate-pulse"></span>
        Tugas Restock Masuk
        <span class="rak-tag text-[10px] bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-300 px-2 py-0.5 rounded-full font-bold">{{ $restockTasks->count() }} BARU</span>
    </h3>

    <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
        @foreach($restockTasks as $notif)
        <div class="flex items-center gap-3 py-3 px-1">
            <span class="flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-[#1E293B] to-[#101826] text-amber-400 flex items-center justify-center">
                <span class="material-symbols-outlined text-lg">local_shipping</span>
            </span>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $notif->data['product_name'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Diminta oleh {{ $notif->data['requested_by'] }} &middot; <span class="rak-tag">{{ $notif->created_at->diffForHumans() }}</span></p>
            </div>
            <a href="{{ url('/barang-masuk/restock/' . $notif->data['product_id']) }}?notification_id={{ $notif->id }}"
               class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-2 bg-gradient-to-br from-[#1E293B] to-[#101826] text-amber-400 font-semibold rounded-xl text-xs shadow-sm hover:shadow-md transition-all duration-300">
                Isi Draf
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif
<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
    <div class="rak-ticket p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 transition-shadow hover:shadow-md relative overflow-hidden" style="border-left-color: rgba(20,184,166,0.5)">
        <span class="rak-tag absolute top-0 right-0 bg-teal-600 text-white text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg">TASK-IN</span>
        <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2 tracking-tight">
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

    <div class="rak-ticket p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 transition-shadow hover:shadow-md relative overflow-hidden" style="border-left-color: rgba(244,63,94,0.5)">
        <span class="rak-tag absolute top-0 right-0 bg-rose-600 text-white text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg">TASK-OUT</span>
        <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2 tracking-tight">
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
{{-- 🛠️ INJEKSI JAVASCRIPT APEXCHARTS UNTUK ADMIN --}}
{{-- ========================================================================= --}}
@push('scripts')
@if(Auth::user()->role === 'Admin')
<script>
    window.__weeklyThroughputChartInstance = window.__weeklyThroughputChartInstance || null;

    function initChart() {
        const chartElement = document.getElementById('weekly-throughput-chart');
        if (!chartElement) return;

        try {
            if (window.__weeklyThroughputChartInstance) {
                window.__weeklyThroughputChartInstance.destroy();
                window.__weeklyThroughputChartInstance = null;
            }

            chartElement.innerHTML = '';

            const labels = JSON.parse(chartElement.getAttribute('data-labels'));
            const dataIn = JSON.parse(chartElement.getAttribute('data-in'));
            const dataOut = JSON.parse(chartElement.getAttribute('data-out'));
            const netMovement = dataIn.map((num, idx) => num - dataOut[idx]);

            const options = {
                series: [
                    { name: 'Net Pergerakan', type: 'line', data: netMovement },
                    { name: 'Barang Masuk', type: 'column', data: dataIn },
                    { name: 'Barang Keluar', type: 'column', data: dataOut }
                ],
                chart: {
                    height: 280,
                    type: 'line',
                    stacked: false,
                    toolbar: { show: false },
                    animations: { enabled: false },
                    fontFamily: 'inherit',
                    redrawOnWindowResize: true,
                    redrawOnParentResize: true
                },
                stroke: { width: [3, 0, 0], curve: 'smooth' },
                plotOptions: { bar: { columnWidth: '35%', borderRadius: 4 } },
                colors: ['#f5a623', '#14b8a6', '#f43f5e'],
                fill: { opacity: [1, 0.85, 0.85] },
                labels: labels,
                markers: { size: [4, 0, 0] },
                xaxis: {
                    type: 'category',
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#9ca3af' } }
                },
                yaxis: { labels: { style: { colors: '#9ca3af' } } },
                tooltip: {
                    shared: true,
                    intersect: false,
                    theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'center',
                    labels: { colors: document.documentElement.classList.contains('dark') ? '#fff' : '#111827' }
                },
                grid: { borderColor: 'rgba(156, 163, 175, 0.1)', strokeDashArray: 4 }
            };

            const chart = new ApexCharts(chartElement, options);
            window.__weeklyThroughputChartInstance = chart;
            chart.render();

            setTimeout(() => {
                window.dispatchEvent(new Event('resize'));
            }, 300);

            console.log("ApexCharts: Render Berhasil!");

        } catch (error) {
            console.error("ApexCharts Error Parsing Data:", error);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => setTimeout(initChart, 100));
    } else {
        setTimeout(initChart, 100);
    }
</script>
@endif
@endpush

@endsection