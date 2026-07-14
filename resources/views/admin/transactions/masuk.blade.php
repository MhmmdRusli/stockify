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
                <button type="button" onclick="exportBarangMasukToExcel()" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 text-gray-600 bg-white hover:bg-gray-50 border border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 font-semibold rounded-xl text-sm px-4 py-2.5 shadow-xs transition-colors">
                    <span class="material-symbols-outlined text-sm">download</span>
                    Export Report
                </button>
            @endif

            {{-- BUTTON KHUSUS STAFF GUDANG --}}
            @if(strtolower(auth()->user()->role) === 'staff gudang')
                <button type="button" onclick="openModalForStaff()" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 text-white bg-amber-500 hover:bg-amber-600 font-semibold rounded-xl text-sm px-4 py-2.5 shadow-xs transition-colors">
                    <span class="material-symbols-outlined text-sm font-bold">assignment_add</span>
                    Ajukan Draft Barang Masuk
                </button>
            @endif

            {{-- BUTTON KHUSUS MANAJER GUDANG --}}
            @if(strtolower(auth()->user()->role) === 'manajer gudang')
                <button type="button" onclick="openModalForManager()" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 text-white bg-amber-500 hover:bg-amber-600 font-semibold rounded-xl text-sm px-4 py-2.5 shadow-xs transition-colors">
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
    {{-- 🆕 FIX: overflow-x-auto dihapus dari wrapper, tabel dipaksa table-fixed w-full agar tidak perlu scroll horizontal --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs overflow-hidden">
        <div class="w-full">
            <table id="barangMasukTable" class="w-full table-fixed divide-y divide-gray-100 dark:divide-gray-700 text-left">
                <thead class="bg-gray-50/75 dark:bg-gray-700/50 text-gray-400 dark:text-gray-400 font-bold uppercase text-[10px] tracking-wider border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-3 py-4 w-[11%]">Waktu</th>
                        <th class="px-3 py-4 w-[10%]">ID Transaksi</th>
                        <th class="px-3 py-4 w-[16%]">Nama Produk</th>
                        <th class="px-3 py-4 w-[9%]">SKU</th>
                        <th class="px-2 py-4 w-[8%] text-center">Tipe</th>
                        <th class="px-2 py-4 w-[7%] text-right">Jumlah</th>
                        <th class="px-3 py-4 w-[15%]">Keterangan</th>
                        <th class="px-2 py-4 w-[9%] text-center">Status</th>
                        <th class="px-2 py-4 w-[15%] text-center">Aksi SOP</th>
                    </tr>
                </thead>
                <tbody id="transaction-table" class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($transactions as $item)
                    <tr class="hover:bg-gray-50/40 dark:hover:bg-gray-700/20 transition-colors">
                        <td class="px-3 py-4">
                            <p class="text-xs font-bold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->date)->format('M d, Y') }}</p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">{{ $item->created_at->format('H:i A') }}</p>
                        </td>
                        <td class="px-3 py-4 text-[11px] font-bold text-blue-600 dark:text-blue-400 font-mono truncate">
                            #IN-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-3 py-4 text-xs font-bold text-gray-900 dark:text-white truncate" title="{{ $item->product->name ?? 'Produk Terhapus' }}">
                            {{ $item->product->name ?? 'Produk Terhapus' }}
                        </td>
                        <td class="px-3 py-4 text-[11px] font-mono text-gray-400 dark:text-gray-500 truncate">
                            {{ $item->product->sku ?? '-' }}
                        </td>
                        <td class="px-2 py-4 text-center">
                            <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100 text-[9px] font-bold uppercase dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50">
                                <span class="material-symbols-outlined text-[10px]">arrow_downward</span> Masuk
                            </span>
                        </td>
                        <td class="px-2 py-4 text-right font-bold text-emerald-600 dark:text-emerald-400 text-xs">
                            +{{ number_format($item->quantity, 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-4 text-xs text-gray-500 dark:text-gray-400 truncate" title="{{ $item->notes ?? 'Tidak ada catatan' }}">
                            {{ $item->notes ?? 'Tidak ada catatan' }}
                        </td>
                        <td class="px-2 py-4 text-center">
                            @if($item->status === 'Pending')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 dark:bg-amber-950/30 dark:text-amber-400">
                                    <span class="w-1 h-1 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                </span>
                            @elseif($item->status === 'Diterima' || $item->status === 'Dikeluarkan')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-400">
                                    <span class="w-1 h-1 rounded-full bg-emerald-500"></span> Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-800 dark:bg-red-950/30 dark:text-red-400">
                                    <span class="w-1 h-1 rounded-full bg-red-500"></span> Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-2 py-4 text-center">
                            <div class="inline-flex items-center justify-center gap-1 flex-wrap">
                                <button type="button" data-modal-target="detail-masuk-modal-{{ $item->id }}" data-modal-toggle="detail-masuk-modal-{{ $item->id }}" class="p-1.5 text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100/80 rounded-lg transition-colors border border-blue-100 dark:bg-blue-950/20 dark:text-blue-400 dark:border-blue-900/50" title="Lihat Detail">
                                    <span class="material-symbols-outlined text-base">visibility</span>
                                </button>

                                @if($item->status === 'Pending')
                                    @if(strtolower(auth()->user()->role) === 'manajer gudang')
                                        <button type="button"
                                            onclick="konfirmasiAksi('{{ route('transactions.konfirmasi', $item->id) }}', 'Setujui')"
                                            class="px-2 py-1.5 text-emerald-700 bg-emerald-50 hover:bg-emerald-100/80 font-bold rounded-lg text-[10px] border border-emerald-100 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50 transition-colors">
                                            Acc
                                        </button>
                                        <button type="button"
                                            onclick="konfirmasiAksi('{{ route('transactions.tolak', $item->id) }}', 'Tolak')"
                                            class="px-2 py-1.5 text-red-700 bg-red-50 hover:bg-red-100/80 font-bold rounded-lg text-[10px] border border-red-100 dark:bg-red-950/20 dark:text-red-400 dark:border-red-900/50 transition-colors">
                                            Tolak
                                        </button>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500 italic text-[9px] leading-tight">Wait Mgr</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 text-[9px]">Done</span>
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

{{-- MODAL INTERAKTIF UTAMA --}}
@if(in_array(strtolower(auth()->user()->role), ['manajer gudang', 'staff gudang']))
<div id="add-barang-masuk-modal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full bg-gray-900/40 backdrop-blur-xs flex items-center justify-center">
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl dark:bg-gray-800 p-6 border border-gray-100 dark:border-gray-700">
        
        <div class="flex justify-between items-center mb-4">
            <h3 id="modalFormTitle" class="text-base font-bold text-gray-900 dark:text-white">Form Pasokan Barang Masuk</h3>
            <button type="button" id="btnToggleQuickProduct" onclick="toggleQuickProductForm()" class="hidden text-[11px] font-bold text-amber-600 hover:underline flex items-center gap-0.5 cursor-pointer">
                <span class="material-symbols-outlined text-xs">add_box</span> + Produk Baru
            </button>
        </div>

        <form action="{{ route('barang.masuk.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                
                {{-- BLOCK A: INPUT DROPDOWN PRODUK EXISTING --}}
                <div id="wrapperProductSelect">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Pilih Produk</label>
                    <select name="product_id" id="mainProductSelect" required class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:border-gray-600 py-2.5">
                        <option value="" disabled selected>-- Pilih Produk --</option>
                        @foreach($products as $prod) 
                            <option value="{{ $prod->id }}">{{ $prod->name }} (Stok Saat Ini: {{ $prod->stock }})</option> 
                        @endforeach
                    </select>
                </div>

                {{-- BLOCK B: FORM KILAT DAFTAR PRODUK BARU --}}
                <div id="wrapperQuickProductForm" class="hidden p-3.5 bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50 rounded-xl space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-amber-800 dark:text-amber-400">Registrasi Produk Kilat (Draft)</span>
                        <button type="button" id="btnCancelQuickProduct" onclick="toggleQuickProductForm()" class="text-[10px] font-semibold text-red-600 hover:underline">Batal</button>
                    </div>
                    <div>
                        <input type="text" name="new_product_name" id="modalProdName" placeholder="Nama Produk Baru" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:border-gray-600 py-2">
                    </div>
                    
                    {{-- DROPDOWN KATEGORI + OPSI KATEGORI BARU --}}
                    <div>
                        <select name="category_id" id="modalProdCategory" onchange="checkNewCategoryOption(this)" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:border-gray-600 py-2">
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                            <option value="NEW_CATEGORY" class="text-indigo-600 font-bold">+ Buat Kategori Baru...</option>
                        </select>
                    </div>

                    {{-- BLOCK SUB-INPUT NAMA KATEGORI BARU (HIDDEN DEFAULT) --}}
                    <div id="wrapperNewCategoryInput" class="hidden mt-1 p-2 bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/50 rounded-lg">
                        <input type="text" name="new_category_name" id="modalNewCategoryName" placeholder="Ketik Nama Kategori Baru" class="w-full rounded-lg border-gray-200 text-xs dark:bg-gray-700 dark:border-gray-600 py-1.5">
                    </div>

                    <div>
                        <input type="text" name="sku" placeholder="Kode SKU (Kosongkan jika auto-generate)" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:border-gray-600 py-2">
                    </div>
                </div>

                {{-- INPUT QUANTITY & NOTES --}}
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Jumlah Item</label>
                    <input type="number" name="quantity" required placeholder="Jumlah" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:border-gray-600 py-2.5">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Catatan</label>
                    <textarea name="notes" rows="3" placeholder="Catatan transaksi..." class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:border-gray-600 py-2.5"></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" onclick="closeMainFormModal()" class="px-4 py-2 text-xs font-semibold text-gray-500 bg-white border border-gray-200 rounded-xl cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-amber-500 rounded-xl cursor-pointer">Kirim</button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
let isQuickProductActive = false;

document.getElementById('table-search')?.addEventListener('input', function() {
    const query = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('#transaction-table tr');

    rows.forEach(row => {
        if(row.cells.length > 1) {
            const textContent = row.textContent.toLowerCase();
            if(textContent.includes(query)) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        }
    });
});

function checkNewCategoryOption(selectElement) {
    const wrapperNewCat = document.getElementById('wrapperNewCategoryInput');
    const inputNewCatName = document.getElementById('modalNewCategoryName');
    
    if (selectElement.value === 'NEW_CATEGORY') {
        wrapperNewCat.classList.remove('hidden');
        inputNewCatName.setAttribute('required', 'required');
    } else {
        wrapperNewCat.classList.add('hidden');
        inputNewCatName.removeAttribute('required');
        inputNewCatName.value = "";
    }
}

function openModalForStaff() {
    document.getElementById('modalFormTitle').innerText = "Form Pengajuan Draft Produk";
    
    document.getElementById('wrapperProductSelect').classList.add('hidden');
    document.getElementById('mainProductSelect').removeAttribute('required');
    document.getElementById('mainProductSelect').value = "";
    
    document.getElementById('wrapperQuickProductForm').classList.remove('hidden');
    document.getElementById('modalProdName').setAttribute('required', 'required');
    document.getElementById('modalProdCategory').setAttribute('required', 'required');

    document.getElementById('btnToggleQuickProduct').classList.add('hidden');
    document.getElementById('btnCancelQuickProduct').classList.add('hidden');

    document.getElementById('add-barang-masuk-modal').classList.remove('hidden');
}

function openModalForManager() {
    document.getElementById('modalFormTitle').innerText = "Form Pasokan Barang Masuk";
    
    document.getElementById('wrapperProductSelect').classList.remove('hidden');
    document.getElementById('mainProductSelect').setAttribute('required', 'required');
    
    document.getElementById('wrapperQuickProductForm').classList.add('hidden');
    document.getElementById('modalProdName').removeAttribute('required');
    document.getElementById('modalProdCategory').removeAttribute('required');
    
    document.getElementById('wrapperNewCategoryInput').classList.add('hidden');
    document.getElementById('modalNewCategoryName').removeAttribute('required');
    document.getElementById('modalNewCategoryName').value = "";
    document.getElementById('modalProdCategory').value = "";
    
    document.getElementById('btnToggleQuickProduct').classList.remove('hidden');
    document.getElementById('btnCancelQuickProduct').classList.remove('hidden');

    document.getElementById('add-barang-masuk-modal').classList.remove('hidden');
}

function closeMainFormModal() {
    document.getElementById('add-barang-masuk-modal').classList.add('hidden');
}

function toggleQuickProductForm() {
    const wrapperSelect = document.getElementById('wrapperProductSelect');
    const wrapperForm = document.getElementById('wrapperQuickProductForm');
    const selectEl = document.getElementById('mainProductSelect');
    const inputNameEl = document.getElementById('modalProdName');
    const selectCatEl = document.getElementById('modalProdCategory');
    const btnToggle = document.getElementById('btnToggleQuickProduct');

    isQuickProductActive = !isQuickProductActive;

    if (isQuickProductActive) {
        wrapperSelect.classList.add('hidden');
        wrapperForm.classList.remove('hidden');
        selectEl.removeAttribute('required');
        selectEl.value = "";
        
        inputNameEl.setAttribute('required', 'required');
        selectCatEl.setAttribute('required', 'required');
        btnToggle.classList.add('hidden');
    } else {
        wrapperSelect.classList.remove('hidden');
        wrapperForm.classList.add('hidden');
        selectEl.setAttribute('required', 'required');
        
        inputNameEl.removeAttribute('required');
        selectCatEl.removeAttribute('required');
        inputNameEl.value = "";
        selectCatEl.value = "";
        
        document.getElementById('wrapperNewCategoryInput').classList.add('hidden');
        document.getElementById('modalNewCategoryName').removeAttribute('required');
        document.getElementById('modalNewCategoryName').value = "";
        
        btnToggle.classList.remove('hidden');
    }
}

function exportBarangMasukToExcel() {
    const table = document.getElementById("barangMasukTable");
    const blob = new Blob(['\ufeff' + table.outerHTML], { type: "application/vnd.ms-excel" });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = "Laporan_Barang_Masuk_" + new Date().toISOString().slice(0,10) + ".xls";
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

function konfirmasiAksi(url, tipe) {
    let buttonColorClass = tipe === 'Setujui' ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white';
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            popup: 'rounded-2xl shadow-xl',
            title: 'text-lg font-bold',
            confirmButton: `px-5 py-2 text-xs font-bold rounded-lg mr-3 ${buttonColorClass}`,
            cancelButton: 'px-5 py-2 text-xs font-bold rounded-lg bg-gray-200 text-gray-700'
        },
        buttonsStyling: false 
    });

    swalWithBootstrapButtons.fire({
        title: 'Konfirmasi',
        text: `Anda yakin ingin ${tipe} data ini?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Lanjut',
        cancelButtonText: 'Batal'
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