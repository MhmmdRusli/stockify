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
</style>

<div class="p-6 space-y-5 bg-gray-50/50 dark:bg-gray-950 min-h-screen">

    {{-- 1. HEADER HALAMAN --}}
    <div class="rak-ticket p-6 bg-white dark:bg-[#111826] rounded-xl border border-gray-100 dark:border-gray-700/60 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4" style="border-left-color: rgba(244,63,94,0.5)">
        <div class="text-left">
            <h1 class="font-display text-xl font-bold text-gray-900 dark:text-white tracking-tight">Barang <span class="text-rose-500 dark:text-rose-400">Keluar</span></h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola pengeluaran stok barang untuk distribusi toko, pesanan, atau mutasi eksternal.</p>
        </div>
        <div class="shrink-0 flex items-center gap-2 w-full sm:w-auto">
            {{-- Export Report disembunyikan dari Staff Gudang. Staff hanya bertugas
                 menyiapkan & mengirimkan barang keluar (Setujui/Tolak), bukan melihat laporan. --}}
            @if(strtolower(auth()->user()->role) !== 'staff gudang')
                <button type="button" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 text-gray-600 bg-white hover:bg-gray-50 border border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 font-semibold rounded-xl text-sm px-4 py-2.5 shadow-sm transition-colors">
                    <span class="material-symbols-outlined text-sm">download</span>
                    Export Report
                </button>
            @endif

            {{-- Hanya Manajer Gudang yang boleh membuat pengajuan Barang Keluar baru,
                 sesuai spek "Mengeluarkan barang dan mencatat data pengeluaran".
                 Admin hanya berhak melihat riwayat/laporan (read-only). --}}
            @if(strtolower(auth()->user()->role) === 'manajer gudang')
                <button type="button" data-modal-target="add-barang-keluar-modal" data-modal-toggle="add-barang-keluar-modal" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-gradient-to-br from-[#1E293B] to-[#101826] text-amber-400 font-semibold rounded-xl text-sm px-4 py-2.5 shadow-md hover:shadow-lg transition-all duration-300">
                    <span class="material-symbols-outlined text-sm font-bold">add</span>
                    Tambah Barang Keluar
                </button>
            @endif
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="p-4 text-xs font-semibold text-teal-700 bg-teal-50 rounded-xl border border-teal-100 dark:bg-teal-950/20 dark:text-teal-400 dark:border-teal-900/40 shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 text-xs font-semibold text-rose-700 bg-rose-50 rounded-xl border border-rose-100 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/40 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- 2. KARTU METRIK RINGKASAN --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rak-ticket p-5 bg-white dark:bg-[#111826] border border-gray-100 dark:border-gray-700/60 rounded-xl shadow-sm flex items-center justify-between" style="border-left-color: rgba(244,63,94,0.5)">
            <span class="rak-tag absolute top-0 right-0 bg-rose-600 text-white text-[9px] font-semibold px-2 py-1 rounded-bl-lg">OUT-ALL</span>
            <div class="space-y-1">
                <p class="rak-tag text-[10px] font-semibold text-gray-400 uppercase">Total Barang Keluar</p>
                <p class="font-display text-2xl font-bold text-rose-600 dark:text-rose-400 tracking-tight">{{ $transactions->sum('quantity') }} <span class="text-xs font-normal text-gray-400">Item</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-rose-700 flex items-center justify-center text-white shadow-md">
                <span class="material-symbols-outlined text-xl">call_made</span>
            </div>
        </div>
        <div class="rak-ticket p-5 bg-white dark:bg-[#111826] border border-gray-100 dark:border-gray-700/60 rounded-xl shadow-sm flex items-center justify-between" style="border-left-color: rgba(245,166,35,0.45)">
            <span class="rak-tag absolute top-0 right-0 bg-[#101826] text-amber-400 text-[9px] font-semibold px-2 py-1 rounded-bl-lg">OUT-VAR</span>
            <div class="space-y-1">
                <p class="rak-tag text-[10px] font-semibold text-gray-400 uppercase">Variasi Produk Keluar</p>
                <p class="font-display text-2xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $transactions->unique('product_id')->count() }} <span class="text-xs font-normal text-gray-400">Produk</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#1E293B] to-[#101826] flex items-center justify-center text-amber-400 shadow-md">
                <span class="material-symbols-outlined text-xl">storefront</span>
            </div>
        </div>
        <div class="rak-ticket p-5 bg-white dark:bg-[#111826] border border-gray-100 dark:border-gray-700/60 rounded-xl shadow-sm flex items-center justify-between" style="border-left-color: rgba(245,166,35,0.45)">
            <span class="rak-tag absolute top-0 right-0 bg-amber-500 text-white text-[9px] font-semibold px-2 py-1 rounded-bl-lg">PENDING</span>
            <div class="space-y-1">
                <p class="rak-tag text-[10px] font-semibold text-gray-400 uppercase">Menunggu Verifikasi</p>
                <p class="font-display text-2xl font-bold text-amber-600 dark:text-amber-400 tracking-tight">{{ $transactions->where('status', 'Pending')->count() }} <span class="text-xs font-normal text-gray-400">Transaksi</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/40 flex items-center justify-center text-amber-600 dark:text-amber-400 shadow-sm">
                <span class="material-symbols-outlined text-xl">pending_actions</span>
            </div>
        </div>
    </div>

    {{-- 3. INPUT CARI DATA --}}
    <div class="relative w-full">
        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 dark:text-gray-500">
            <span class="material-symbols-outlined text-lg">search</span>
        </span>
        <input id="table-search" type="text" placeholder="Cari data barang keluar..." class="w-full pl-11 pr-4 py-3 text-xs font-medium rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-4 focus:ring-amber-400/20 focus:border-amber-400 transition-all shadow-sm placeholder:text-gray-400">
    </div>

    {{-- 4. KONTEN TABEL DATA TRANSAKSI --}}
    <div class="rak-ticket bg-white dark:bg-[#111826] rounded-xl border border-gray-100 dark:border-gray-700/60 shadow-sm overflow-hidden" style="border-left-color: rgba(244,63,94,0.5)">
        <span class="rak-tag absolute top-0 right-0 bg-rose-600 text-white text-[9px] font-semibold px-2.5 py-1 rounded-bl-lg z-10">OUT-TBL</span>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700/60 text-left">
                <thead class="rak-tag bg-gray-50/75 dark:bg-gray-800/60 text-gray-400 dark:text-gray-500 font-bold uppercase text-[10px] border-b border-gray-100 dark:border-gray-700/60">
                    <tr>
                        <th class="px-6 py-4">Waktu Keluar</th>
                        <th class="px-6 py-4">ID Transaksi</th>
                        <th class="px-6 py-4">Nama Produk</th>
                        <th class="px-6 py-4">SKU</th>
                        <th class="px-6 py-4 text-center">Tipe</th>
                        <th class="px-6 py-4 text-right">Jumlah</th>
                        <th class="px-6 py-4">Keterangan / Tujuan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right pr-10">Aksi SOP</th>
                    </tr>
                </thead>
                <tbody id="transaction-table" class="divide-y divide-gray-100 dark:divide-gray-700/60 bg-white dark:bg-[#111826]">
                    @forelse($transactions as $item)
                    <tr class="hover:bg-amber-50/40 dark:hover:bg-gray-700/20 transition-colors duration-150">
                        <td class="px-6 py-5 whitespace-nowrap">
                            <p class="font-display text-sm font-bold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->date)->format('M d, Y') }}</p>
                            <p class="rak-tag text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">{{ $item->created_at->format('H:i A') }}</p>
                        </td>
                        <td class="rak-tag px-6 py-5 whitespace-nowrap text-xs font-bold text-rose-600 dark:text-rose-400">
                            #OUT-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-5 font-display text-sm font-bold text-gray-900 dark:text-white">
                            {{ $item->product->name ?? 'Produk Terhapus' }}
                        </td>
                        <td class="rak-tag px-6 py-5 whitespace-nowrap text-xs text-gray-400 dark:text-gray-500">
                            {{ $item->product->sku ?? '-' }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-center">
                            <span class="rak-tag inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 border border-rose-100 text-[10px] font-bold uppercase dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/50">
                                <span class="material-symbols-outlined text-xs">arrow_upward</span> Keluar
                            </span>
                        </td>
                        <td class="font-display px-6 py-5 whitespace-nowrap text-right font-bold text-rose-600 dark:text-rose-400 text-sm">
                            -{{ $item->quantity }}
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-500 dark:text-gray-400 truncate max-w-[180px]">
                            {{ $item->notes ?? 'Tidak ada catatan' }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-center">
                            @if($item->status === 'Pending')
                                <span class="rak-tag inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-800 dark:bg-amber-950/30 dark:text-amber-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                </span>
                            @elseif($item->status === 'Dikeluarkan' || $item->status === 'Diterima')
                                <span class="rak-tag inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-teal-50 text-teal-800 dark:bg-teal-950/30 dark:text-teal-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span> Selesai
                                </span>
                            @else
                                <span class="rak-tag inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-800 dark:bg-rose-950/30 dark:text-rose-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-right pr-8">
                            <div class="flex items-center justify-end gap-1.5">
                                {{-- 🆕 TOMBOL LIHAT DETAIL: tersedia untuk semua role, buka modal info lengkap --}}
                                <button type="button" data-modal-target="detail-keluar-modal-{{ $item->id }}" data-modal-toggle="detail-keluar-modal-{{ $item->id }}" class="p-2 text-amber-600 hover:text-amber-700 bg-amber-50 hover:bg-amber-100/80 rounded-xl transition-colors inline-flex items-center justify-center border border-amber-100 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/50" title="Lihat Detail">
                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                </button>

                                @if($item->status === 'Pending')
                                    {{-- Setujui/Tolak khusus Staff Gudang, sesuai spek
                                         "Menyiapkan dan mengirimkan barang keluar". Admin & Manajer
                                         Gudang (pembuat pengajuan) tidak menampilkan tombol ini. --}}
                                    @if(strtolower(auth()->user()->role) === 'staff gudang')
                                        <button type="button"
                                            onclick="konfirmasiAksi('{{ route('transactions.konfirmasi', $item->id) }}', 'Setujui')"
                                            class="px-3 py-2 text-teal-700 bg-teal-50 hover:bg-teal-100/80 font-semibold rounded-xl text-[11px] border border-teal-100 dark:bg-teal-950/20 dark:text-teal-400 dark:border-teal-900/50 transition-colors">
                                            Setujui
                                        </button>
                                        <button type="button"
                                            onclick="konfirmasiAksi('{{ route('transactions.tolak', $item->id) }}', 'Tolak')"
                                            class="px-3 py-2 text-rose-700 bg-rose-50 hover:bg-rose-100/80 font-semibold rounded-xl text-[11px] border border-rose-100 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/50 transition-colors">
                                            Tolak
                                        </button>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500 italic text-[11px]">Menunggu verifikasi Staff</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 italic text-[11px]">Sudah diproses</span>
                                @endif
                            </div>
                        </td>
                    </tr>

                    {{-- 🆕 MODAL DETAIL TRANSAKSI: menampilkan keterangan/catatan lengkap tanpa terpotong --}}
                    <div id="detail-keluar-modal-{{ $item->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full bg-gray-900/40 backdrop-blur-sm flex items-center justify-center">
                        <div class="rak-ticket relative w-full max-w-lg max-h-full bg-white rounded-xl shadow-xl dark:bg-[#111826] p-6 border border-gray-100 dark:border-gray-700/60 mx-auto mt-10 overflow-hidden" style="border-left-color: rgba(244,63,94,0.5)">
                            <span class="rak-tag absolute top-0 right-0 bg-rose-600 text-white text-[9px] font-semibold px-2.5 py-1 rounded-bl-lg">OUT-DETAIL</span>
                            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700/60 pb-4 mb-5">
                                <h3 class="font-display text-base font-bold text-gray-900 dark:text-white flex items-center gap-2 tracking-tight">
                                    <span class="material-symbols-outlined text-rose-500">receipt_long</span>
                                    Detail Transaksi <span class="rak-tag text-rose-600 dark:text-rose-400 text-sm">#OUT-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </h3>
                                <button type="button" data-modal-toggle="detail-keluar-modal-{{ $item->id }}" class="text-gray-400 hover:text-gray-500 flex items-center"><span class="material-symbols-outlined">close</span></button>
                            </div>

                            <div class="space-y-4 text-left">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="rak-tag text-[10px] font-bold text-gray-400 uppercase mb-1">Nama Produk</p>
                                        <p class="font-display text-sm font-bold text-gray-900 dark:text-white">{{ $item->product->name ?? 'Produk Terhapus' }}</p>
                                    </div>
                                    <div>
                                        <p class="rak-tag text-[10px] font-bold text-gray-400 uppercase mb-1">SKU</p>
                                        <p class="rak-tag text-sm text-gray-700 dark:text-gray-300">{{ $item->product->sku ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="rak-tag text-[10px] font-bold text-gray-400 uppercase mb-1">Waktu Keluar</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</p>
                                        <p class="rak-tag text-[10px] text-gray-400 dark:text-gray-500">{{ $item->created_at->format('H:i A') }}</p>
                                    </div>
                                    <div>
                                        <p class="rak-tag text-[10px] font-bold text-gray-400 uppercase mb-1">Jumlah</p>
                                        <p class="font-display text-sm font-bold text-rose-600 dark:text-rose-400">-{{ $item->quantity }} Pcs</p>
                                    </div>
                                    <div>
                                        <p class="rak-tag text-[10px] font-bold text-gray-400 uppercase mb-1">Status</p>
                                        @if($item->status === 'Pending')
                                            <span class="rak-tag inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-800 dark:bg-amber-950/30 dark:text-amber-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                            </span>
                                        @elseif($item->status === 'Dikeluarkan' || $item->status === 'Diterima')
                                            <span class="rak-tag inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-teal-50 text-teal-800 dark:bg-teal-950/30 dark:text-teal-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span> Selesai
                                            </span>
                                        @else
                                            <span class="rak-tag inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-800 dark:bg-rose-950/30 dark:text-rose-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditolak
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="rak-tag text-[10px] font-bold text-gray-400 uppercase mb-1">Tipe</p>
                                        <span class="rak-tag inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 border border-rose-100 text-[10px] font-bold uppercase dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/50">
                                            <span class="material-symbols-outlined text-xs">arrow_upward</span> Keluar
                                        </span>
                                    </div>
                                </div>

                                <div class="pt-2 border-t border-gray-100 dark:border-gray-700/60">
                                    <p class="rak-tag text-[10px] font-bold text-gray-400 uppercase mb-1.5">Keterangan / Tujuan Distribusi</p>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 p-3 rounded-xl leading-relaxed whitespace-pre-line">{{ $item->notes ?? 'Tidak ada catatan' }}</p>
                                </div>
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700/60 flex justify-end">
                                <button type="button" data-modal-toggle="detail-keluar-modal-{{ $item->id }}" class="px-4 py-2.5 text-xs font-semibold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600">Tutup</button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-sm font-medium text-gray-400 dark:text-gray-500">
                            Belum ada riwayat transaksi barang keluar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- 5. MODAL TAMBAH BARANG KELUAR --}}
{{-- Samakan dengan pengecekan tombol di atas -- hanya Manajer Gudang --}}
@if(strtolower(auth()->user()->role) === 'manajer gudang')
<div id="add-barang-keluar-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full bg-gray-900/40 backdrop-blur-sm flex items-center justify-center">
    <div class="rak-ticket relative w-full max-w-md max-h-full bg-white rounded-xl shadow-xl dark:bg-[#111826] p-6 border border-gray-100 dark:border-gray-700/60 mx-auto mt-10 overflow-hidden" style="border-left-color: rgba(244,63,94,0.5)">
        <span class="rak-tag absolute top-0 right-0 bg-rose-600 text-white text-[9px] font-semibold px-2.5 py-1 rounded-bl-lg">NEW-OUT</span>
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700/60 pb-4 mb-5">
            <h3 class="font-display text-base font-bold text-gray-900 dark:text-white flex items-center gap-2 tracking-tight">
                <span class="material-symbols-outlined text-rose-500">call_made</span> Form Pengajuan Barang Keluar
            </h3>
            <button type="button" data-modal-toggle="add-barang-keluar-modal" class="text-gray-400 hover:text-gray-500 flex items-center"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form action="{{ route('barang.keluar.store') }}" method="POST">
            @csrf
            <div class="space-y-4 text-left">
                <div>
                    <label for="product_id" class="rak-tag block text-[10px] font-bold text-gray-400 uppercase mb-1.5">Pilih Produk</label>
                    <select name="product_id" id="product_id" required class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-800 dark:text-white dark:border-gray-600 focus:ring-4 focus:ring-amber-400/20 focus:border-amber-400 py-2.5">
                        <option value="" disabled selected>-- Pilih Produk di Gudang --</option>
                        {{-- FIX: tampilkan stok AKTUAL ($prod->stock), bukan minimum_stock (ambang batas statis) --}}
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}">{{ $prod->name }} (Stok Saat Ini: {{ $prod->stock }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="quantity" class="rak-tag block text-[10px] font-bold text-gray-400 uppercase mb-1.5">Jumlah Keluar</label>
                    <input type="number" name="quantity" id="quantity" min="1" required placeholder="Contoh: 15" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-800 dark:text-white dark:border-gray-600 focus:ring-4 focus:ring-amber-400/20 focus:border-amber-400 py-2.5">
                </div>
                <div>
                    <label for="notes" class="rak-tag block text-[10px] font-bold text-gray-400 uppercase mb-1.5">Keterangan / Tujuan Distribusi</label>
                    <textarea name="notes" id="notes" rows="3" placeholder="Contoh: Kirim ke Cabang Mall North Plaza atau retur vendor..." class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-800 dark:text-white dark:border-gray-600 focus:ring-4 focus:ring-amber-400/20 focus:border-amber-400 py-2.5"></textarea>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700/60 flex justify-end space-x-2">
                <button type="button" data-modal-toggle="add-barang-keluar-modal" class="px-4 py-2.5 text-xs font-semibold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600">Batal</button>
                <button type="submit" class="px-4 py-2.5 text-xs font-semibold bg-gradient-to-br from-[#1E293B] to-[#101826] text-amber-400 rounded-xl shadow-md hover:shadow-lg transition-all duration-300">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- 6. SCRIPT LIVE PENCARIAN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('table-search');
        const tableBody = document.getElementById('transaction-table');

        if (searchInput && tableBody) {
            const rows = tableBody.getElementsByTagName('tr');

            searchInput.addEventListener('keyup', function (e) {
                const text = e.target.value.toLowerCase();

                for (let i = 0; i < rows.length; i++) {
                    if (rows[i].cells.length <= 1) continue;

                    const idTransaksi = rows[i].cells[1] ? rows[i].cells[1].textContent.toLowerCase() : '';
                    const namaProduk = rows[i].cells[2] ? rows[i].cells[2].textContent.toLowerCase() : '';
                    const sku = rows[i].cells[3] ? rows[i].cells[3].textContent.toLowerCase() : '';
                    const catatan = rows[i].cells[6] ? rows[i].cells[6].textContent.toLowerCase() : '';

                    if (idTransaksi.includes(text) || namaProduk.includes(text) || sku.includes(text) || catatan.includes(text)) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            });
        }
    });

    {{-- 🆕 KONFIRMASI SETUJUI/TOLAK: pakai SweetAlert2, disamakan persis dengan halaman Barang Masuk --}}
    function konfirmasiAksi(url, tipe) {
        let buttonColorClass = tipe === 'Setujui'
            ? 'bg-amber-500 hover:bg-amber-600 text-white'
            : 'bg-rose-500 hover:bg-rose-600 text-white';

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                popup: 'rounded-2xl shadow-xl',
                title: 'text-lg font-bold',
                confirmButton: `px-5 py-2 text-xs font-bold rounded-lg mr-3 transition-colors ${buttonColorClass}`,
                cancelButton: 'px-5 py-2 text-xs font-bold rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors',
                icon: tipe === 'Setujui' ? 'border-amber-500 text-amber-500' : 'border-rose-500 text-rose-500'
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: 'Konfirmasi',
            text: `Anda yakin ingin ${tipe} data ini?`,
            icon: tipe === 'Setujui' ? 'question' : 'error',
            width: '320px',
            showCancelButton: true,
            confirmButtonText: 'Ya, Lanjut',
            cancelButtonText: 'Batal',
            didOpen: (popup) => {
                if (tipe === 'Setujui') {
                    const icon = popup.querySelector('.swal2-question');
                    if (icon) icon.style.color = '#f59e0b';
                    if (icon) icon.style.borderColor = '#f59e0b';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.action = url; form.method = 'POST';
                form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
                document.body.appendChild(form); form.submit();
            }
        });
    }
</script>
@endsection