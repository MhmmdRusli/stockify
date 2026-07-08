@extends('layouts.dashboard')

@section('content')
<div class="p-6 space-y-6 bg-gray-50/50 dark:bg-gray-950 min-h-screen">

    {{-- 1. HEADER HALAMAN --}}
    <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="text-left">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Barang Masuk</h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola dan pantau seluruh pasokan serta produk yang masuk ke gudang.</p>
        </div>
        <div class="shrink-0 flex items-center gap-2 w-full sm:w-auto">
            {{-- FIX: Export Report disembunyikan dari Staff Gudang. Staff hanya bertugas
                 menerima & memeriksa barang masuk (Setujui/Tolak), bukan melihat laporan. --}}
            @if(strtolower(auth()->user()->role) !== 'staff gudang')
                <button type="button" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 text-gray-600 bg-white hover:bg-gray-50 border border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 font-semibold rounded-xl text-sm px-4 py-2.5 shadow-xs transition-colors">
                    <span class="material-symbols-outlined text-sm">download</span>
                    Export Report
                </button>
            @endif

            {{-- Hanya Manajer Gudang yang boleh menambahkan Barang Masuk baru.
                 Admin hanya berhak melihat riwayat/laporan (read-only) sesuai spesifikasi role.
                 Staff Gudang hanya menerima & memeriksa (tombol Setujui/Tolak), bukan menambah. --}}
            @if(strtolower(auth()->user()->role) === 'manajer gudang')
                <button type="button" data-modal-target="add-barang-masuk-modal" data-modal-toggle="add-barang-masuk-modal" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 text-white bg-amber-500 hover:bg-amber-600 font-semibold rounded-xl text-sm px-4 py-2.5 shadow-xs transition-colors">
                    <span class="material-symbols-outlined text-sm font-bold">add</span>
                    Tambah Barang Masuk
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
    @if(session('error'))
        <div class="p-4 text-xs font-semibold text-red-700 bg-red-50 rounded-xl border border-red-100 dark:bg-gray-800 dark:text-red-400 dark:border-gray-700 shadow-2xs">
            {{ session('error') }}
        </div>
    @endif

    {{-- 2. KARTU METRIK RINGKASAN --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Barang Masuk</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $transactions->sum('quantity') }} <span class="text-xs font-normal text-gray-400">Item</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <span class="material-symbols-outlined text-xl">call_received</span>
            </div>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Variasi Produk Masuk</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $transactions->unique('product_id')->count() }} <span class="text-xs font-normal text-gray-400">Produk</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <span class="material-symbols-outlined text-xl">inventory_2</span>
            </div>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Menunggu Verifikasi</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $transactions->where('status', 'Pending')->count() }} <span class="text-xs font-normal text-gray-400">Transaksi</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/40 flex items-center justify-center text-amber-600 dark:text-amber-400">
                <span class="material-symbols-outlined text-xl">pending_actions</span>
            </div>
        </div>
    </div>

    {{-- 3. INPUT CARI DATA --}}
    <div class="relative w-full">
        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 dark:text-gray-500">
            <span class="material-symbols-outlined text-lg">search</span>
        </span>
        <input id="table-search" type="text" placeholder="Cari data barang masuk..." class="w-full pl-11 pr-4 py-3 text-xs font-medium rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-2xs placeholder:text-gray-400">
    </div>

    {{-- 4. KONTEN TABEL DATA TRANSAKSI --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700 text-left">
                <thead class="bg-gray-50/75 dark:bg-gray-700/50 text-gray-400 dark:text-gray-400 font-bold uppercase text-[11px] tracking-wider border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4">Waktu Masuk</th>
                        <th class="px-6 py-4">ID Transaksi</th>
                        <th class="px-6 py-4">Nama Produk</th>
                        <th class="px-6 py-4">SKU</th>
                        <th class="px-6 py-4 text-center">Tipe</th>
                        <th class="px-6 py-4 text-right">Jumlah</th>
                        <th class="px-6 py-4">Keterangan / Supplier</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right pr-10">Aksi SOP</th>
                    </tr>
                </thead>
                <tbody id="transaction-table" class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($transactions as $item)
                    <tr class="hover:bg-gray-50/40 dark:hover:bg-gray-700/20 transition-colors">
                        <td class="px-6 py-5 whitespace-nowrap">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->date)->format('M d, Y') }}</p>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">{{ $item->created_at->format('H:i A') }}</p>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-xs font-bold text-blue-600 dark:text-blue-400 font-mono">
                            #IN-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-5 text-sm font-bold text-gray-900 dark:text-white">
                            {{ $item->product->name ?? 'Produk Terhapus' }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-xs font-mono text-gray-400 dark:text-gray-500">
                            {{ $item->product->sku ?? '-' }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100 text-[10px] font-bold uppercase tracking-wider dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50">
                                <span class="material-symbols-outlined text-xs">arrow_downward</span> Masuk
                            </span>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-right font-bold text-emerald-600 dark:text-emerald-400 text-sm">
                            +{{ $item->quantity }}
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-500 dark:text-gray-400 truncate max-w-[180px]">
                            {{ $item->notes ?? 'Tidak ada catatan' }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-center">
                            @if($item->status === 'Pending')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 dark:bg-amber-950/30 dark:text-amber-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                </span>
                            @elseif($item->status === 'Diterima' || $item->status === 'Dikeluarkan')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-800 dark:bg-red-950/30 dark:text-red-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-right pr-8">
                            @if($item->status === 'Pending')
                                {{-- Setujui/Tolak: khusus Staff Gudang, sesuai spek "menerima dan memeriksa barang masuk" --}}
                                @if(strtolower(auth()->user()->role) === 'staff gudang')
                                    <div class="flex items-center justify-end gap-1.5">
                                        <form action="{{ route('transactions.konfirmasi', $item->id) }}" method="POST" onsubmit="return confirm('Konfirmasi penerimaan barang masuk ini ke dalam gudang?')">
                                            @csrf
                                            <button type="submit" class="px-3 py-2 text-emerald-700 bg-emerald-50 hover:bg-emerald-100/80 font-semibold rounded-xl text-[11px] border border-emerald-100 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50 transition-colors">Setujui</button>
                                        </form>
                                        <form action="{{ route('transactions.tolak', $item->id) }}" method="POST" onsubmit="return confirm('Tolak pengajuan barang masuk ini?')">
                                            @csrf
                                            <button type="submit" class="px-3 py-2 text-red-700 bg-red-50 hover:bg-red-100/80 font-semibold rounded-xl text-[11px] border border-red-100 dark:bg-red-950/20 dark:text-red-400 dark:border-red-900/50 transition-colors">Tolak</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 italic text-[11px]">Menunggu verifikasi Staff</span>
                                @endif
                            @else
                                <span class="text-gray-400 dark:text-gray-500 italic text-[11px]">Sudah diproses</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-sm font-medium text-gray-400 dark:text-gray-500">
                            Belum ada riwayat transaksi barang masuk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- 5. MODAL TAMBAH BARANG MASUK --}}
{{-- Samakan dengan pengecekan tombol di atas -- hanya Manajer Gudang --}}
@if(strtolower(auth()->user()->role) === 'manajer gudang')
<div id="add-barang-masuk-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full bg-gray-900/40 backdrop-blur-xs flex items-center justify-center">
    <div class="relative w-full max-w-md max-h-full bg-white rounded-2xl shadow-xl dark:bg-gray-800 p-6 border border-gray-100 dark:border-gray-700 mx-auto mt-10">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4 mb-5">
            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">call_received</span> Form Pasokan Barang Masuk
            </h3>
            <button type="button" data-modal-toggle="add-barang-masuk-modal" class="text-gray-400 hover:text-gray-500 flex items-center"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form action="{{ route('barang.masuk.store') }}" method="POST">
            @csrf
            <div class="space-y-4 text-left">
                <div>
                    <label for="product_id" class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Pilih Produk</label>
                    <select name="product_id" id="product_id" required class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5">
                        <option value="" disabled selected>-- Pilih Produk --</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}">{{ $prod->name }} (Stok Saat Ini: {{ $prod->minimum_stock }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="quantity" class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Jumlah Masuk</label>
                    <input type="number" name="quantity" id="quantity" min="1" required placeholder="Contoh: 50" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5">
                </div>
                <div>
                    <label for="notes" class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Catatan Tambahan / Asal Supplier</label>
                    <textarea name="notes" id="notes" rows="3" placeholder="Contoh: Pengiriman dari PT. LogiTech Hub Utama..." class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5"></textarea>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end space-x-2">
                <button type="button" data-modal-toggle="add-barang-masuk-modal" class="px-4 py-2.5 text-xs font-semibold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Batal</button>
                <button type="submit" class="px-4 py-2.5 text-xs font-semibold text-white bg-amber-500 hover:bg-amber-600 rounded-xl shadow-xs">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- 6. SCRIPT LIVE PENCARIAN --}}
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
</script>
@endsection