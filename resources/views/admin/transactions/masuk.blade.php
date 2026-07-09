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
            @if(strtolower(auth()->user()->role) !== 'staff gudang')
                <button type="button" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 text-gray-600 bg-white hover:bg-gray-50 border border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 font-semibold rounded-xl text-sm px-4 py-2.5 shadow-xs transition-colors">
                    <span class="material-symbols-outlined text-sm">download</span>
                    Export Report
                </button>
            @endif

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
                            <div class="flex items-center justify-end gap-1.5">
                                <button type="button" data-modal-target="detail-masuk-modal-{{ $item->id }}" data-modal-toggle="detail-masuk-modal-{{ $item->id }}" class="p-2 text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100/80 rounded-xl transition-colors border border-blue-100 dark:bg-blue-950/20 dark:text-blue-400 dark:border-blue-900/50" title="Lihat Detail">
                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                </button>
        
                                @if($item->status === 'Pending')
                                    @if(strtolower(auth()->user()->role) === 'staff gudang')
                                        <button type="button" 
                                            onclick="konfirmasiAksi('{{ route('transactions.konfirmasi', $item->id) }}', 'Setujui')" 
                                            class="px-3 py-2 text-emerald-700 bg-emerald-50 hover:bg-emerald-100/80 font-semibold rounded-xl text-[11px] border border-emerald-100 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50 transition-colors">
                                            Setujui
                                        </button>
                                        <button type="button" 
                                            onclick="konfirmasiAksi('{{ route('transactions.tolak', $item->id) }}', 'Tolak')" 
                                            class="px-3 py-2 text-red-700 bg-red-50 hover:bg-red-100/80 font-semibold rounded-xl text-[11px] border border-red-100 dark:bg-red-950/20 dark:text-red-400 dark:border-red-900/50 transition-colors">
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
                    
                    {{-- Modal Detail --}}
                    <div id="detail-masuk-modal-{{ $item->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full bg-gray-900/40 backdrop-blur-xs flex items-center justify-center">
                        <div class="relative w-full max-w-lg max-h-full bg-white rounded-2xl shadow-xl dark:bg-gray-800 p-6 border border-gray-100 dark:border-gray-700 mx-auto mt-10">
                            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4 mb-5">
                                <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-600">receipt_long</span>
                                    Detail Transaksi <span class="text-blue-600 dark:text-blue-400 font-mono text-sm">#IN-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </h3>
                                <button type="button" data-modal-toggle="detail-masuk-modal-{{ $item->id }}" class="text-gray-400 hover:text-gray-500 flex items-center"><span class="material-symbols-outlined">close</span></button>
                            </div>
                            <div class="space-y-4 text-left">
                                <div class="grid grid-cols-2 gap-4">
                                    <div><p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Produk</p><p class="text-sm font-bold text-gray-900 dark:text-white">{{ $item->product->name ?? 'Produk Terhapus' }}</p></div>
                                    <div><p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">SKU</p><p class="text-sm font-mono text-gray-700 dark:text-gray-300">{{ $item->product->sku ?? '-' }}</p></div>
                                    <div><p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Waktu Masuk</p><p class="text-sm font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</p></div>
                                    <div><p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Jumlah</p><p class="text-sm font-bold text-emerald-600 dark:text-emerald-400">+{{ $item->quantity }} Pcs</p></div>
                                </div>
                                <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Keterangan / Asal Supplier</p>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 p-3 rounded-xl leading-relaxed whitespace-pre-line">{{ $item->notes ?? 'Tidak ada catatan' }}</p>
                                </div>
                            </div>
                            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                                <button type="button" data-modal-toggle="detail-masuk-modal-{{ $item->id }}" class="px-4 py-2.5 text-xs font-semibold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Tutup</button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-sm font-medium text-gray-400 dark:text-gray-500">Belum ada riwayat transaksi barang masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
@if(strtolower(auth()->user()->role) === 'manajer gudang')
<div id="add-barang-masuk-modal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full bg-gray-900/40 backdrop-blur-xs flex items-center justify-center">
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl dark:bg-gray-800 p-6 border border-gray-100 dark:border-gray-700">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Form Pasokan Barang Masuk</h3>
        <form action="{{ route('barang.masuk.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <select name="product_id" required class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:border-gray-600 py-2.5">
                    <option value="" disabled selected>-- Pilih Produk --</option>
                    {{-- FIX: tampilkan stok AKTUAL ($prod->stock), bukan minimum_stock (ambang batas statis) --}}
                    @foreach($products as $prod) <option value="{{ $prod->id }}">{{ $prod->name }} (Stok Saat Ini: {{ $prod->stock }})</option> @endforeach
                </select>
                <input type="number" name="quantity" required placeholder="Jumlah" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:border-gray-600 py-2.5">
                <textarea name="notes" rows="3" placeholder="Catatan" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:border-gray-600 py-2.5"></textarea>
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" data-modal-toggle="add-barang-masuk-modal" class="px-4 py-2 text-xs font-semibold text-gray-500 bg-white border border-gray-200 rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-amber-500 rounded-xl">Kirim</button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
function konfirmasiAksi(url, tipe) {
    // 1. Warna tombol
    let buttonColorClass = tipe === 'Setujui' 
        ? 'bg-amber-500 hover:bg-amber-600 text-white' 
        : 'bg-red-500 hover:bg-red-600 text-white';
        
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            popup: 'rounded-2xl shadow-xl',
            title: 'text-lg font-bold',
            confirmButton: `px-5 py-2 text-xs font-bold rounded-lg mr-3 transition-colors ${buttonColorClass}`,
            cancelButton: 'px-5 py-2 text-xs font-bold rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors',
            // Menambahkan style manual agar ikon kuning amber
            icon: tipe === 'Setujui' ? 'border-amber-500 text-amber-500' : 'border-red-500 text-red-500'
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
        // Tambahan: memaksa warna icon jika class di atas kurang dominan
        didOpen: (popup) => {
            if (tipe === 'Setujui') {
                const icon = popup.querySelector('.swal2-question');
                if (icon) icon.style.color = '#f59e0b'; // Warna amber-500
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