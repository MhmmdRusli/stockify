@extends('layouts.dashboard')

@section('content')
    <style>
        .rak-ticket {
            position: relative;
            border-left: 3px solid rgba(245, 166, 35, 0.35);
        }

        .rak-tag {
            letter-spacing: 0.03em;
        }

        .barcode-strip span {
            display: inline-block;
            width: 1px;
            background-color: currentColor;
        }

        .mini-bar-track {
            background-color: rgba(156, 163, 175, 0.15);
        }
    </style>

    <div class="p-5 bg-white border-b border-gray-100 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700/60">
        <div class="grid grid-cols-1 w-full">
            <div class="text-left">
                <h1 class="text-xl font-bold text-gray-900 sm:text-2xl dark:text-white tracking-tight">
                    Dashboard <span class="text-amber-500 dark:text-amber-400">{{ Auth::user()->role }}</span>
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Selamat datang kembali, <span
                        class="font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>. Berikut
                    ringkasan aktivitas kerja Anda.
                </p>
            </div>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- 🛡️ 1. TAMPILAN DASHBOARD: ADMIN --}}
    {{-- ========================================================================= --}}
    @if (Auth::user()->role === 'Admin')
        @php
            $recentActivities =
                $recentActivities ??
                \App\Models\StockTransaction::with(['product', 'user'])
                    ->latest()
                    ->take(4)
                    ->get();

            // 🆕 Data tambahan (fallback aman — pindahkan ke controller kalau mau lebih rapi)
            $totalSuppliers = $totalSuppliers ?? \App\Models\Supplier::count();
            $totalCategories = $totalCategories ?? \App\Models\Category::count();
            $totalUsers = $totalUsers ?? \App\Models\User::count();
            $pendingTransactions =
                $pendingTransactions ?? \App\Models\StockTransaction::where('status', 'Pending')->count();

            $roleBreakdown =
                $roleBreakdown ??
                \App\Models\User::selectRaw('role, count(*) as total')->groupBy('role')->pluck('total', 'role');
            $maxRoleCount = $roleBreakdown->max() ?: 1;

            $topProducts =
                $topProducts ??
                \App\Models\StockTransaction::where('status', '!=', 'Pending')
                    ->select('product_id')
                    ->selectRaw('SUM(quantity) as total_qty')
                    ->whereDate('date', '>=', now()->subDays(7))
                    ->groupBy('product_id')
                    ->orderByDesc('total_qty')
                    ->with('product')
                    ->take(5)
                    ->get();
            $maxTopQty = $topProducts->max('total_qty') ?: 1;
        @endphp

        {{-- BARIS 1: KARTU METRIK UTAMA --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mt-4">
            <div
                class="rak-ticket group relative p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700/60 overflow-hidden transition-all duration-300 hover:-translate-y-0.5">
                <span
                    class="rak-tag absolute top-0 right-0 bg-amber-50 text-amber-700 text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg dark:bg-amber-950/30 dark:text-amber-300">RAK-01</span>
                <p class="rak-tag text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase mb-2">Total Jenis
                    Produk</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-4xl font-bold text-gray-900 dark:text-white tracking-tight">
                        {{ number_format($totalProducts, 0, ',', '.') }}
                        <span class="text-sm font-medium text-gray-400 align-middle">SKU</span>
                    </h3>
                    <div
                        class="p-3 rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-950/30 dark:text-amber-400 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rak-ticket group relative p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700/60 overflow-hidden transition-all duration-300 hover:-translate-y-0.5"
                style="border-left-color: rgba(20,184,166,0.35)">
                <span
                    class="rak-tag absolute top-0 right-0 bg-teal-50 text-teal-700 text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg dark:bg-teal-950/30 dark:text-teal-300">MASUK</span>
                <p class="rak-tag text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase mb-2">Akumulasi Stok
                    Masuk</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-4xl font-bold text-teal-600 dark:text-teal-400 tracking-tight">
                        +{{ number_format($totalFormatIn, 0, ',', '.') }}
                        <span class="text-sm font-medium text-gray-400 align-middle">Pcs</span>
                    </h3>
                    <div
                        class="p-3 rounded-xl bg-teal-50 text-teal-600 dark:bg-teal-950/30 dark:text-teal-400 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rak-ticket group relative p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700/60 overflow-hidden transition-all duration-300 hover:-translate-y-0.5"
                style="border-left-color: rgba(244,63,94,0.35)">
                <span
                    class="rak-tag absolute top-0 right-0 bg-rose-50 text-rose-700 text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg dark:bg-rose-950/30 dark:text-rose-300">KELUAR</span>
                <p class="rak-tag text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase mb-2">Akumulasi Stok
                    Keluar</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-4xl font-bold text-rose-600 dark:text-rose-400 tracking-tight">
                        -{{ number_format($totalFormatOut, 0, ',', '.') }}
                        <span class="text-sm font-medium text-gray-400 align-middle">Pcs</span>
                    </h3>
                    <div
                        class="p-3 rounded-xl bg-rose-50 text-rose-600 dark:bg-rose-950/30 dark:text-rose-400 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
            @php
                $stats = [
                    [
                        'title' => 'Supplier Mitra',
                        'value' => $totalSuppliers,
                        'color' => 'blue',
                        'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4M4 17h12m0 0l-4 4m4-4l-4-4',
                    ],
                    [
                        'title' => 'Kategori Aktif',
                        'value' => $totalCategories,
                        'color' => 'purple',
                        'icon' =>
                            'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                    ],
                    [
                        'title' => 'Total Pengguna',
                        'value' => $totalUsers,
                        'color' => 'indigo',
                        'icon' =>
                            'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-6.13a4 4 0 11-8 0 4 4 0 018 0zm6 3a4 4 0 10-8 0',
                    ],
                    [
                        'title' => 'Transaksi Pending',
                        'value' => $pendingTransactions,
                        'color' => 'amber',
                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                    ],
                ];
            @endphp

            @foreach ($stats as $stat)
                <div
                    class="group relative p-5 bg-white dark:bg-[#111826] rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 flex items-center gap-4">
                    <div
                        class="p-3 rounded-xl bg-{{ $stat['color'] }}-50 text-{{ $stat['color'] }}-600 dark:bg-{{ $stat['color'] }}-950/30 dark:text-{{ $stat['color'] }}-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}">
                            </path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $stat['title'] }}</p>
                        <p class="text-xl font-extrabold text-gray-900 dark:text-white leading-tight mt-0.5">
                            {{ $stat['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- BARIS 3: CHART + AKTIVITAS TERBARU --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mt-4">
            <div
                class="lg:col-span-2 p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60">
                <div class="flex items-center justify-between mb-1">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white tracking-tight">Tren Transaksi 7 Hari
                            Terakhir</h3>
                    </div>
                </div>

                <div class="relative w-full mt-3">
                    <div id="weekly-throughput-chart"
                        data-labels='{{ json_encode($days ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']) }}'
                        data-in='{{ json_encode($dataIn ?? [0, 0, 0, 0, 0, 0, 0]) }}'
                        data-out='{{ json_encode($dataOut ?? [0, 0, 0, 0, 0, 0, 0]) }}'
                        style="min-height: 280px; width: 100%;"></div>
                </div>

                <div class="barcode-strip flex items-center gap-[2px] mt-4 opacity-30 text-gray-400 dark:text-gray-600">
                    @for ($i = 0; $i < 60; $i++)
                        <span style="height: {{ rand(4, 12) }}px;"></span>
                    @endfor
                </div>
            </div>

            <div class="rak-ticket p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 transition-shadow hover:shadow-md relative overflow-hidden"
                style="border-left-color: rgba(99,102,241,0.35)">
                <span
                    class="rak-tag absolute top-0 right-0 bg-indigo-50 text-indigo-700 text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg dark:bg-indigo-950/30 dark:text-indigo-300">LOG-USER</span>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-1 tracking-tight">Aktivitas Pengguna Terbaru
                </h3>
                <p class="rak-tag text-[10px] text-gray-400 dark:text-gray-500 mb-3">Riwayat transaksi stok terbaru oleh
                    seluruh staff</p>
                <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
                    @forelse($recentActivities as $activity)
                        @php
                            $isIn = $activity->type === 'in';
                            $actionLabel = match (true) {
                                $isIn && $activity->status === 'Pending' => 'mengajukan barang masuk untuk',
                                $isIn && $activity->status === 'Diterima' => 'barang masuk disetujui untuk',
                                $isIn && $activity->status === 'Ditolak' => 'barang masuk ditolak untuk',
                                !$isIn && $activity->status === 'Pending' => 'mengajukan barang keluar untuk',
                                !$isIn && $activity->status === 'Dikeluarkan' => 'barang keluar dikonfirmasi untuk',
                                !$isIn && $activity->status === 'Ditolak' => 'barang keluar dibatalkan untuk',
                                default => 'memperbarui stok untuk',
                            };
                        @endphp
                        <div
                            class="flex items-center gap-3 py-2.5 px-1 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors duration-150">
                            <span
                                class="flex-shrink-0 w-9 h-9 rounded-lg {{ $isIn ? 'bg-teal-50 text-teal-600 dark:bg-teal-950/30 dark:text-teal-400' : 'bg-rose-50 text-rose-600 dark:bg-rose-950/30 dark:text-rose-400' }} flex items-center justify-center font-bold text-xs uppercase">
                                {{ substr($activity->user->name ?? '?', 0, 2) }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-700 dark:text-gray-300">
                                    <span
                                        class="font-bold text-gray-900 dark:text-white">{{ $activity->user->name ?? 'Pengguna' }}</span>
                                    {{ $actionLabel }}
                                    <span
                                        class="font-semibold text-gray-900 dark:text-white">{{ $activity->product->name ?? 'produk terhapus' }}</span>
                                    <span
                                        class="{{ $isIn ? 'text-teal-600' : 'text-rose-600' }}">({{ $isIn ? '+' : '-' }}{{ $activity->quantity }}
                                        pcs)</span>
                                </p>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">
                                    {{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-gray-400">Belum ada aktivitas transaksi.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- 🆕 BARIS 4: PRODUK PALING AKTIF + KOMPOSISI PENGGUNA --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mt-5">
            <div class="lg:col-span-2 rak-ticket p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60"
                style="border-left-color: rgba(245,166,35,0.4)">
                <span
                    class="rak-tag absolute top-0 right-0 bg-amber-50 text-amber-700 text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg dark:bg-amber-950/30 dark:text-amber-300">TOP-MOVE</span>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-1 tracking-tight">Produk Paling Aktif Bergerak
                </h3>
                <p class="rak-tag text-[10px] text-gray-400 dark:text-gray-500 mb-3">Berdasarkan volume transaksi 7 hari
                    terakhir</p>
                <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
                    @forelse($topProducts as $tp)
                        <div class="flex items-center gap-3 py-2.5 px-1">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-xs font-bold text-gray-800 dark:text-white truncate">
                                        {{ $tp->product->name ?? 'Produk Terhapus' }}</p>
                                    <span
                                        class="rak-tag text-xs font-bold text-amber-600 dark:text-amber-400 shrink-0 ml-2">{{ $tp->total_qty }}
                                        Pcs</span>
                                </div>
                                <div class="w-full mini-bar-track h-1.5 rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-400"
                                        style="width: {{ min(($tp->total_qty / $maxTopQty) * 100, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-gray-400">Belum ada aktivitas transaksi minggu ini.</div>
                    @endforelse
                </div>
            </div>

            <div class="rak-ticket p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60"
                style="border-left-color: rgba(59,130,246,0.35)">
                <span
                    class="rak-tag absolute top-0 right-0 bg-blue-50 text-blue-700 text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg dark:bg-blue-950/30 dark:text-blue-300">USR-MIX</span>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-1 tracking-tight">Komposisi Pengguna</h3>
                <p class="rak-tag text-[10px] text-gray-400 dark:text-gray-500 mb-3">Sebaran akun berdasarkan peran</p>
                <div class="space-y-3">
                    @forelse($roleBreakdown as $role => $count)
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span
                                    class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ $role }}</span>
                                <span
                                    class="rak-tag text-xs font-bold text-gray-500 dark:text-gray-400">{{ $count }}</span>
                            </div>
                            <div class="w-full mini-bar-track h-1.5 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-400"
                                    style="width: {{ min(($count / $maxRoleCount) * 100, 100) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-gray-400">Belum ada data pengguna.</div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    {{-- ========================================================================= --}}
    {{-- 📦 2. TAMPILAN DASHBOARD: MANAJER GUDANG (Sesuai Spec: Stok Menipis, Masuk & Keluar Hari Ini) --}}
    {{-- ========================================================================= --}}
    @if (Auth::user()->role === 'Manajer Gudang')
        @php
            // 🆕 Data tambahan (fallback aman)
            $totalProdukManajer = $totalProdukManajer ?? \App\Models\Product::count();
            $totalSupplierManajer = $totalSupplierManajer ?? \App\Models\Supplier::count();
            $pendingStaffCount =
                $pendingStaffCount ?? \App\Models\StockTransaction::where('status', 'Pending')->count();

            $topProductsManajer =
                $topProductsManajer ??
                \App\Models\StockTransaction::where('status', '!=', 'Pending')
                    ->select('product_id')
                    ->selectRaw('SUM(quantity) as total_qty')
                    ->whereDate('date', '>=', now()->subDays(7))
                    ->groupBy('product_id')
                    ->orderByDesc('total_qty')
                    ->with('product')
                    ->take(5)
                    ->get();
            $maxTopQtyManajer = $topProductsManajer->max('total_qty') ?: 1;
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mt-4">
            {{-- KARTU 1: STOK MENIPIS --}}
            <div class="rak-ticket group relative p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700/60 overflow-hidden transition-all duration-300 hover:-translate-y-0.5"
                style="border-left-color: rgba(245,166,35,0.4)">
                <span
                    class="rak-tag absolute top-0 right-0 bg-amber-50 text-amber-700 text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg dark:bg-amber-950/30 dark:text-amber-300">MENIPIS</span>
                <p class="rak-tag text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase mb-2">Stok Menipis
                </p>
                <div class="flex items-end justify-between">
                    <h3 class="text-4xl font-bold text-amber-500 dark:text-amber-400 tracking-tight">
                        {{ $lowStockProducts->count() }}
                        <span class="text-sm font-medium text-gray-400 align-middle">Item</span>
                    </h3>
                    <div
                        class="p-3 rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-950/30 dark:text-amber-400 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- KARTU 2: BARANG MASUK HARI INI --}}
            <div class="rak-ticket group relative p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700/60 overflow-hidden transition-all duration-300 hover:-translate-y-0.5"
                style="border-left-color: rgba(20,184,166,0.35)">
                <span
                    class="rak-tag absolute top-0 right-0 bg-teal-50 text-teal-700 text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg dark:bg-teal-950/30 dark:text-teal-300">IN-01</span>
                <p class="rak-tag text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase mb-2">Barang Masuk
                    Hari Ini</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-4xl font-bold text-teal-600 dark:text-teal-400 tracking-tight">
                        +{{ number_format($masukHariIni, 0, ',', '.') }}
                        <span class="text-sm font-medium text-gray-400 align-middle">Pcs</span>
                    </h3>
                    <div
                        class="p-3 rounded-xl bg-teal-50 text-teal-600 dark:bg-teal-950/30 dark:text-teal-400 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- KARTU 3: BARANG KELUAR HARI INI --}}
            <div class="rak-ticket group relative p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700/60 overflow-hidden transition-all duration-300 hover:-translate-y-0.5"
                style="border-left-color: rgba(244,63,94,0.35)">
                <span
                    class="rak-tag absolute top-0 right-0 bg-rose-50 text-rose-700 text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg dark:bg-rose-950/30 dark:text-rose-300">OUT-01</span>
                <p class="rak-tag text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase mb-2">Barang Keluar
                    Hari Ini</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-4xl font-bold text-rose-600 dark:text-rose-400 tracking-tight">
                        -{{ number_format($keluarHariIni, 0, ',', '.') }}
                        <span class="text-sm font-medium text-gray-400 align-middle">Pcs</span>
                    </h3>
                    <div
                        class="p-3 rounded-xl bg-rose-50 text-rose-600 dark:bg-rose-950/30 dark:text-rose-400 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- 🆕 BARIS MINI: RINGKASAN TAMBAHAN (Gaya Konsisten) --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
            @php
                $miniStats = [
                    [
                        'title' => 'Total Produk',
                        'value' => $totalProdukManajer,
                        'color' => 'amber',
                        'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                    ],
                    [
                        'title' => 'Supplier Mitra',
                        'value' => $totalSupplierManajer,
                        'color' => 'blue',
                        'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4M4 17h12m0 0l-4 4m4-4l-4-4',
                    ],
                    [
                        'title' => 'Menunggu Staff',
                        'value' => $pendingStaffCount,
                        'color' => 'rose',
                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                    ],
                ];
            @endphp

            @foreach ($miniStats as $stat)
                <div
                    class="group relative p-5 bg-white dark:bg-[#111826] rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 flex items-center gap-4">
                    <div
                        class="p-3 rounded-xl bg-{{ $stat['color'] }}-50 text-{{ $stat['color'] }}-600 dark:bg-{{ $stat['color'] }}-950/30 dark:text-{{ $stat['color'] }}-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="{{ $stat['icon'] }}"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $stat['title'] }}</p>
                        <p class="text-xl font-extrabold text-gray-900 dark:text-white leading-tight mt-0.5">
                            {{ $stat['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- BARIS: MONITORING STOK + PRODUK PALING AKTIF --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mt-5">
            <div class="rak-ticket p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 transition-shadow hover:shadow-md relative overflow-hidden"
                style="border-left-color: rgba(245,166,35,0.4)">
                <span
                    class="rak-tag absolute top-0 right-0 bg-amber-50 text-amber-700 text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg dark:bg-amber-950/30 dark:text-amber-300">MIN-STOK</span>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-1 tracking-tight">Monitoring Batas Stok Terbaru</h3>
                <p class="rak-tag text-[10px] text-gray-400 dark:text-gray-500 mb-3">Produk mendekati batas minimum, perlu tindakan restock</p>
                <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
                    @forelse($lowStockProducts as $product)
                        <div class="flex items-center justify-between gap-3 py-2.5 px-1 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors duration-150">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="flex-shrink-0 w-1.5 h-8 rounded-full bg-orange-400"></span>
                                <div class="min-w-0">
                                    <p class="rak-tag text-xs font-bold text-gray-800 dark:text-white">
                                        {{ $product->sku ?? 'NO-SKU' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[180px]">
                                        {{ $product->name }}
                                    </p>
                                </div>
                            </div>
                            {{-- 🎯 FIX DI SINI: Nampilin Sisa Stok Asli vs Batas Minimalnya --}}
                            <div class="text-right shrink-0">
                                <span class="rak-tag bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-400 text-xs font-extrabold px-2.5 py-1 rounded-full ring-1 ring-rose-100 dark:ring-rose-900/40">
                                    Sisa: {{ $product->stock }} Pcs
                                </span>
                                <p class="text-[9px] text-gray-400 dark:text-gray-500 mt-1 font-semibold">Min: {{ $product->minimum_stock }} Pcs</p>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-gray-400">Stok gudang aman terkendali.</div>
                    @endforelse
                </div>
            </div>

            {{-- 🆕 PRODUK PALING AKTIF --}}
            <div class="rak-ticket p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 relative overflow-hidden"
                style="border-left-color: rgba(20,184,166,0.35)">
                <span
                    class="rak-tag absolute top-0 right-0 bg-teal-50 text-teal-700 text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg dark:bg-teal-950/30 dark:text-teal-300">TOP-MOVE</span>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-1 tracking-tight">Produk Paling Aktif Bergerak</h3>
                <p class="rak-tag text-[10px] text-gray-400 dark:text-gray-500 mb-3">Volume transaksi 7 hari terakhir</p>
                <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
                    @forelse($topProductsManajer as $tp)
                        <div class="flex items-center gap-3 py-2.5 px-1">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-xs font-bold text-gray-800 dark:text-white truncate">
                                        {{ $tp->product->name ?? 'Produk Terhapus' }}</p>
                                    <span
                                        class="rak-tag text-xs font-bold text-teal-600 dark:text-teal-400 shrink-0 ml-2">{{ $tp->total_qty }}
                                        Pcs</span>
                                </div>
                                <div class="w-full mini-bar-track h-1.5 rounded-full overflow-hidden">
                                    <div class="h-full bg-teal-400"
                                        style="width: {{ min(($tp->total_qty / $maxTopQtyManajer) * 100, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-gray-400">Belum ada aktivitas transaksi minggu ini.</div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
    
    {{-- ========================================================================= --}}
    {{-- ⚡ 3. TAMPILAN DASHBOARD: STAFF GUDANG --}}
    {{-- ========================================================================= --}}
    @if (Auth::user()->role === 'Staff Gudang')
        @php
            $restockTasks = Auth::user()->unreadNotifications->where('data.type', 'restock_task');

            // 🆕 Ringkasan tugas selesai hari ini (fallback aman)
            $selesaiHariIni =
                $selesaiHariIni ??
                \App\Models\StockTransaction::where('approved_by', Auth::id())
                    ->whereDate('updated_at', now()->toDateString())
                    ->count();
            $totalPendingGabungan = ($tugasMasuk->count() ?? 0) + ($tugasKeluar->count() ?? 0);
        @endphp

        {{-- 🆕 RINGKASAN MINI STAFF (Gaya Konsisten) --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
            @php
                $staffStats = [
                    [
                        'title' => 'Tugas Selesai Hari Ini',
                        'value' => $selesaiHariIni,
                        'color' => 'emerald',
                        'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                    ],
                    [
                        'title' => 'Total Tugas Pending',
                        'value' => $totalPendingGabungan,
                        'color' => 'amber',
                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                    ],
                    [
                        'title' => 'Notifikasi Restock Baru',
                        'value' => $restockTasks->count(),
                        'color' => 'rose',
                        'icon' =>
                            'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
                    ],
                ];
            @endphp

            @foreach ($staffStats as $stat)
                <div
                    class="group relative p-5 bg-white dark:bg-[#111826] rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 flex items-center gap-4">
                    <div
                        class="p-3 rounded-xl bg-{{ $stat['color'] }}-50 text-{{ $stat['color'] }}-600 dark:bg-{{ $stat['color'] }}-950/30 dark:text-{{ $stat['color'] }}-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="{{ $stat['icon'] }}"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $stat['title'] }}</p>
                        <p class="text-xl font-extrabold text-gray-900 dark:text-white leading-tight mt-0.5">
                            {{ $stat['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- 🚚 TUGAS RESTOCK DARI MANAJER --}}
        @if ($restockTasks->count() > 0)
            <div class="rak-ticket p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 relative overflow-hidden mt-4"
                style="border-left-color: rgba(244,63,94,0.35)">
                <span
                    class="rak-tag absolute top-0 right-0 bg-rose-50 text-rose-700 text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg dark:bg-rose-950/30 dark:text-rose-300">TASK-RESTOCK</span>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2 tracking-tight">
                    <span class="w-2 h-2 bg-rose-400 rounded-full ring-4 ring-rose-100 dark:ring-rose-900/40"></span>
                    Tugas Restock Masuk
                    <span
                        class="rak-tag text-[10px] bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-300 px-2 py-0.5 rounded-full font-bold">{{ $restockTasks->count() }}
                        BARU</span>
                </h3>

                <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
                    @foreach ($restockTasks as $notif)
                        <div class="flex items-center gap-3 py-3 px-1">
                            <span
                                class="flex-shrink-0 w-10 h-10 rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-950/30 dark:text-amber-400 flex items-center justify-center">
                                <span class="material-symbols-outlined text-lg">local_shipping</span>
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $notif->data['product_name'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Diminta oleh
                                    {{ $notif->data['requested_by'] }} &middot; <span
                                        class="rak-tag">{{ $notif->created_at->diffForHumans() }}</span></p>
                            </div>
                            <a href="{{ url('/barang-masuk/restock/' . $notif->data['product_id']) }}?notification_id={{ $notif->id }}"
                                class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl text-xs shadow-sm transition-colors duration-200">
                                Isi Draf
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
            <div class="rak-ticket p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 transition-shadow hover:shadow-md relative overflow-hidden"
                style="border-left-color: rgba(20,184,166,0.35)">
                <span
                    class="rak-tag absolute top-0 right-0 bg-teal-50 text-teal-700 text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg dark:bg-teal-950/30 dark:text-teal-300">TASK-IN</span>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2 tracking-tight">
                    <span
                        class="w-2 h-2 bg-emerald-400 rounded-full ring-4 ring-emerald-100 dark:ring-emerald-900/40"></span>
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
                                <tr
                                    class="odd:bg-transparent even:bg-gray-50/60 dark:even:bg-gray-700/20 hover:bg-amber-50/50 dark:hover:bg-gray-700/40 transition-colors duration-150">
                                    <td class="py-2.5 px-3 font-semibold text-gray-800 dark:text-white rounded-l-lg">
                                        {{ $tr->product->name }}</td>
                                    <td class="py-2.5 px-3 text-center font-bold text-emerald-600">{{ $tr->quantity }} Pcs
                                    </td>
                                    <td class="py-2.5 px-3 text-center rounded-r-lg">
                                        @if ($tr->user_id !== auth()->id())
                                            <div class="flex items-center justify-center gap-1.5">
                                                <form action="{{ route('transactions.konfirmasi', $tr->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold px-3 py-1.5 rounded-lg ring-1 ring-emerald-200 hover:ring-emerald-300 transition-all duration-150 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-800">Terima</button>
                                                </form>
                                                <form action="{{ route('transactions.tolak', $tr->id) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold px-3 py-1.5 rounded-lg ring-1 ring-rose-200 hover:ring-rose-300 transition-all duration-150 dark:bg-rose-900/30 dark:text-rose-300 dark:ring-rose-800">Tolak</button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-[11px] text-gray-400 italic">Draf milik Anda sendiri</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-6 text-center text-gray-400">Tidak ada tugas barang masuk
                                        pending.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rak-ticket p-5 bg-white dark:bg-[#111826] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 transition-shadow hover:shadow-md relative overflow-hidden"
                style="border-left-color: rgba(244,63,94,0.35)">
                <span
                    class="rak-tag absolute top-0 right-0 bg-rose-50 text-rose-700 text-[10px] font-semibold px-2.5 py-1 rounded-bl-lg dark:bg-rose-950/30 dark:text-rose-300">TASK-OUT</span>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2 tracking-tight">
                    <span class="w-2 h-2 bg-rose-400 rounded-full ring-4 ring-rose-100 dark:ring-rose-900/40"></span>
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
                                <tr
                                    class="odd:bg-transparent even:bg-gray-50/60 dark:even:bg-gray-700/20 hover:bg-amber-50/50 dark:hover:bg-gray-700/40 transition-colors duration-150">
                                    <td class="py-2.5 px-3 font-semibold text-gray-800 dark:text-white rounded-l-lg">
                                        {{ $tr->product->name }}</td>
                                    <td class="py-2.5 px-3 text-center font-bold text-rose-600">{{ $tr->quantity }} Pcs
                                    </td>
                                    <td class="py-2.5 px-3 text-center rounded-r-lg">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <form action="{{ route('transactions.konfirmasi', $tr->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold px-3 py-1.5 rounded-lg ring-1 ring-blue-200 hover:ring-blue-300 transition-all duration-150 dark:bg-blue-900/30 dark:text-blue-300 dark:ring-blue-800">Kirim</button>
                                            </form>
                                            <form action="{{ route('transactions.tolak', $tr->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-gray-50 hover:bg-gray-100 text-gray-600 font-semibold px-3 py-1.5 rounded-lg ring-1 ring-gray-200 hover:ring-gray-300 transition-all duration-150 dark:bg-gray-700/40 dark:text-gray-300 dark:ring-gray-600">Batal</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-6 text-center text-gray-400">Tidak ada tugas barang keluar
                                        pending.</td>
                                </tr>
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
        @if (Auth::user()->role === 'Admin')
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
                            series: [{
                                    name: 'Net Pergerakan',
                                    type: 'line',
                                    data: netMovement
                                },
                                {
                                    name: 'Barang Masuk',
                                    type: 'column',
                                    data: dataIn
                                },
                                {
                                    name: 'Barang Keluar',
                                    type: 'column',
                                    data: dataOut
                                }
                            ],
                            chart: {
                                height: 280,
                                type: 'line',
                                stacked: false,
                                toolbar: {
                                    show: false
                                },
                                animations: {
                                    enabled: false
                                },
                                fontFamily: 'inherit',
                                redrawOnWindowResize: true,
                                redrawOnParentResize: true
                            },
                            stroke: {
                                width: [3, 0, 0],
                                curve: 'smooth'
                            },
                            plotOptions: {
                                bar: {
                                    columnWidth: '35%',
                                    borderRadius: 4
                                }
                            },
                            colors: ['#d97706', '#2dd4bf', '#fb7185'],
                            fill: {
                                opacity: [1, 0.7, 0.7]
                            },
                            labels: labels,
                            markers: {
                                size: [4, 0, 0]
                            },
                            xaxis: {
                                type: 'category',
                                axisBorder: {
                                    show: false
                                },
                                axisTicks: {
                                    show: false
                                },
                                labels: {
                                    style: {
                                        colors: '#9ca3af'
                                    }
                                }
                            },
                            yaxis: {
                                labels: {
                                    style: {
                                        colors: '#9ca3af'
                                    }
                                }
                            },
                            tooltip: {
                                shared: true,
                                intersect: false,
                                theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                            },
                            legend: {
                                position: 'top',
                                horizontalAlign: 'center',
                                labels: {
                                    colors: document.documentElement.classList.contains('dark') ? '#fff' : '#111827'
                                }
                            },
                            grid: {
                                borderColor: 'rgba(156, 163, 175, 0.1)',
                                strokeDashArray: 4
                            }
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
