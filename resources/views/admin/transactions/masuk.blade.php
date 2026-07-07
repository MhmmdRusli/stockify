@extends('layouts.dashboard')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>

<div class="container mx-auto px-4 py-6 space-y-6" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    
    {{-- NOTIFIKASI SUKSES / ERROR --}}
    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 dark:bg-gray-800 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-950 flex items-center gap-2" role="alert">
            <span class="material-icons-outlined text-[18px]">check_circle</span>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-rose-800 rounded-2xl bg-rose-50 dark:bg-gray-800 dark:text-rose-400 border border-rose-100 dark:border-rose-950 flex items-center gap-2" role="alert">
            <span class="material-icons-outlined text-[18px]">error</span>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-gray-900 p-6 border border-gray-100 dark:border-gray-800 rounded-2xl shadow-sm">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-gray-950 dark:text-white">
                Barang Masuk
            </h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Kelola dan pantau seluruh pasokan serta produk yang masuk ke gudang.
            </p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <button class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-200 font-semibold text-xs transition-colors shadow-sm">
                <span class="material-icons-outlined text-[18px]">download</span>
                Export Report
            </button>
            
            {{-- TOMBOL AKTIF MEMBUKA MODAL BARANG MASUK --}}
            <button type="button" data-modal-target="add-barang-masuk-modal" data-modal-toggle="add-barang-masuk-modal" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-xs shadow-md shadow-emerald-100 dark:shadow-none transition-all active:scale-95">
                <span class="material-icons-outlined text-[18px]">add</span>
                Tambah Barang Masuk
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-5 rounded-2xl shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2.5 bg-emerald-50 dark:bg-emerald-950/40 rounded-xl text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <span class="material-icons-outlined text-[22px]">call_received</span>
                </div>
                <span class="inline-flex items-center text-xs font-bold px-2.5 py-0.5 bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 rounded-full border border-emerald-100/40 dark:border-emerald-900/30">+8%</span>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Barang Masuk</p>
                <h3 class="text-2xl font-extrabold text-gray-950 dark:text-white mt-1">{{ $transactions->sum('quantity') }} <span class="text-xs font-normal text-gray-400">Item</span></h3>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-5 rounded-2xl shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2.5 bg-blue-50 dark:bg-blue-950/40 rounded-xl text-blue-600 dark:text-blue-400 flex items-center justify-center">
                    <span class="material-icons-outlined text-[22px]">inventory_2</span>
                </div>
                <span class="inline-flex items-center text-xs font-bold px-2.5 py-0.5 bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400 rounded-full border border-blue-100/40 dark:border-blue-900/30">Aktif</span>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Variasi Produk Masuk</p>
                <h3 class="text-2xl font-extrabold text-gray-950 dark:text-white mt-1">{{ $transactions->unique('product_id')->count() }} <span class="text-xs font-normal text-gray-400">Produk</span></h3>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-5 rounded-2xl shadow-sm flex flex-col justify-between sm:col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2.5 bg-amber-50 dark:bg-amber-950/40 rounded-xl text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <span class="material-icons-outlined text-[22px]">pending_actions</span>
                </div>
                <span class="inline-flex items-center text-xs font-bold px-2.5 py-0.5 bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 rounded-full border border-amber-100/40 dark:border-amber-900/30">Pending</span>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Menunggu Verifikasi</p>
                <h3 class="text-2xl font-extrabold text-gray-950 dark:text-white mt-1">{{ $transactions->where('status', 'Pending')->count() }} <span class="text-xs font-normal text-gray-400">Transaksi</span></h3>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-4 border border-gray-100 dark:border-gray-800 rounded-2xl shadow-sm">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative flex items-center">
                <span class="material-icons-outlined absolute left-3 text-gray-400 text-[20px] pointer-events-none">search</span>
                {{-- DITAMBAHKAN ID "table-search" --}}
                <input id="table-search" class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-gray-800 text-sm border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none text-gray-900 dark:text-white placeholder-gray-400" placeholder="Cari data barang masuk..." type="text"/>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 dark:bg-gray-800/60 border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Waktu Masuk</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">ID Transaksi</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Nama Produk</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">SKU</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center text-gray-500 dark:text-gray-400">Tipe</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-right text-gray-500 dark:text-gray-400">Jumlah</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Keterangan / Supplier</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Aksi SOP</th>
                    </tr>
                </thead>
                {{-- DITAMBAHKAN ID "transaction-table" --}}
                <tbody id="transaction-table" class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-gray-900">
                    @forelse($transactions as $item)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-xs font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->date)->format('M d, Y') }}</p>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">{{ $item->created_at->format('H:i A') }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-emerald-600 dark:text-emerald-400 font-mono">
                            #IN-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $item->product->name ?? 'Produk Terhapus' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs font-mono text-gray-500 dark:text-gray-400">
                            {{ $item->product->sku ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-100/50 text-[10px] font-bold uppercase tracking-wider dark:bg-emerald-950/30 dark:text-emerald-400 dark:border-emerald-900/30">
                                <span class="material-icons-outlined text-[12px]">arrow_downward</span> Masuk
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-emerald-600 dark:text-emerald-400 text-sm">
                            +{{ $item->quantity }}
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-gray-600 dark:text-gray-400 truncate max-w-[180px]">
                            {{ $item->notes ?? 'Tidak ada catatan' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($item->status === 'Pending')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-800 dark:bg-amber-950/30 dark:text-amber-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    Pending
                                </span>
                            @elseif($item->status === 'Diterima' || $item->status === 'Dikeluarkan')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-800 dark:bg-rose-950/30 dark:text-rose-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs">
                            @if($item->status === 'Pending')
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('transactions.konfirmasi', $item->id) }}" method="POST" onsubmit="return confirm('Konfirmasi penerimaan barang masuk ini ke dalam gudang?')">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium text-[11px] transition-colors">Setujui</button>
                                    </form>
                                    <form action="{{ route('transactions.tolak', $item->id) }}" method="POST" onsubmit="return confirm('Tolak pengajuan barang masuk ini?')">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 rounded-lg font-medium text-[11px] transition-colors">Tolak</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-gray-400 dark:text-gray-600 italic text-[11px]">Sudah diproses</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                            Belum ada riwayat transaksi barang masuk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL POP-UP CONTAINER (FORM TAMBAH BARANG MASUK) --}}
<div id="add-barang-masuk-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow border border-gray-100 dark:border-gray-800">
            <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-100 dark:border-gray-800 rounded-t">
                <h3 class="text-base font-bold text-gray-950 dark:text-white">
                    Form Pasokan Barang Masuk
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-950 dark:hover:bg-gray-800 dark:hover:text-white rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-toggle="add-barang-masuk-modal">
                    <span class="material-icons-outlined text-[20px]">close</span>
                </button>
            </div>
            <form action="{{ route('barang.masuk.store') }}" method="POST" class="p-4 md:p-5 space-y-4">
                @csrf
                <div>
                    <label for="product_id" class="block mb-2 text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">Pilih Produk</label>
                    <select name="product_id" id="product_id" required class="bg-gray-50 border border-gray-200 dark:border-gray-700 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5 dark:bg-gray-800 dark:text-white">
                        <option value="" disabled selected>-- Pilih Produk --</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}">
                                {{ $prod->name }} (Stok Saat Ini: {{ $prod->minimum_stock }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="quantity" class="block mb-2 text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">Jumlah Masuk</label>
                    <input type="number" name="quantity" id="quantity" min="1" required placeholder="Contoh: 50" class="bg-gray-50 border border-gray-200 dark:border-gray-700 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5 dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label for="notes" class="block mb-2 text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">Catatan Tambahan / Asal Supplier</label>
                    <textarea name="notes" id="notes" rows="3" placeholder="Contoh: Pengiriman dari PT. LogiTech Hub Utama..." class="bg-gray-50 border border-gray-200 dark:border-gray-700 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5 dark:bg-gray-800 dark:text-white"></textarea>
                </div>
                <button type="submit" class="w-full text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:outline-none focus:ring-emerald-300 font-semibold rounded-xl text-sm px-5 py-2.5 text-center transition-all">
                    Kirim Pengajuan
                </button>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT JAVASCRIPT UNTUK FITUR LIVE SEARCH --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('table-search');
        const tableBody = document.getElementById('transaction-table');
        
        if (searchInput && tableBody) {
            const rows = tableBody.getElementsByTagName('tr');
            
            searchInput.addEventListener('keyup', function (e) {
                const text = e.target.value.toLowerCase();
                
                for (let i = 0; i < rows.length; i++) {
                    // Jika baris berisi text "Belum ada riwayat", lewati pencarian
                    if (rows[i].cells.length <= 1) continue; 
                    
                    // Mendapatkan string teks dari tiap kolom target
                    const idTransaksi = rows[i].cells[1] ? rows[i].cells[1].textContent.toLowerCase() : '';
                    const namaProduk = rows[i].cells[2] ? rows[i].cells[2].textContent.toLowerCase() : '';
                    const sku = rows[i].cells[3] ? rows[i].cells[3].textContent.toLowerCase() : '';
                    const catatan = rows[i].cells[6] ? rows[i].cells[6].textContent.toLowerCase() : '';

                    // Jika keyword ada di salah satu kolom tersebut, tampilkan barisnya
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