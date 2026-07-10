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

<div class="p-6 space-y-5 bg-gray-50/50 dark:bg-gray-950 min-h-screen id-to-print">

    {{-- 1. HEADER HALAMAN --}}
    <div class="rak-ticket p-6 bg-white dark:bg-[#111826] rounded-xl border border-gray-100 dark:border-gray-700/60 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4 print:border-none print:shadow-none print:p-0" style="border-left-color: rgba(245,166,35,0.5)">
        <div class="text-left">
            <h1 class="font-display text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2 tracking-tight print:text-black">
                <span class="material-symbols-outlined text-amber-500 dark:text-amber-400 text-2xl print:hidden">swap_horizontal_circle</span>
                Laporan Arus Log <span class="text-amber-500 dark:text-amber-400">Barang Masuk & Keluar</span>
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">Sistem rekam log mutasi, penyesuaian kuantitas stok, dan status validasi SOP gudang.</p>
        </div>
        <div class="flex flex-wrap gap-2 w-full lg:w-auto print:hidden">
            <button onclick="exportToExcel()" class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 text-white bg-teal-600 hover:bg-teal-700 font-semibold rounded-xl text-sm px-4 py-2.5 shadow-md transition-all duration-300 cursor-pointer">
                <span class="material-symbols-outlined text-sm">description</span>
                Export Excel
            </button>
            <button onclick="window.print()" class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 bg-gradient-to-br from-[#1E293B] to-[#101826] text-amber-400 font-semibold rounded-xl text-sm px-4 py-2.5 shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer">
                <span class="material-symbols-outlined text-sm">picture_as_pdf</span>
                Cetak PDF
            </button>
        </div>
    </div>

    {{-- 2. KARTU METRIK RINGKASAN --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 print:grid-cols-4 print:gap-2">
        <div class="rak-ticket p-5 bg-white dark:bg-[#111826] border border-gray-100 dark:border-gray-700/60 rounded-xl shadow-sm flex items-center justify-between print:border-gray-300 print:p-4 print:text-black print:bg-white" style="border-left-color: rgba(245,166,35,0.45)">
            <span class="rak-tag absolute top-0 right-0 bg-[#101826] text-amber-400 text-[9px] font-semibold px-2 py-1 rounded-bl-lg print:hidden">TRX-ALL</span>
            <div class="space-y-1">
                <p class="rak-tag text-[10px] font-semibold text-gray-400 uppercase print:text-gray-600">Total Transaksi Log</p>
                <p class="font-display text-2xl font-bold text-gray-900 dark:text-white tracking-tight print:text-black">{{ $transactions->count() }} <span class="text-xs font-normal text-gray-400 print:text-gray-600">Log</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#1E293B] to-[#101826] flex items-center justify-center text-amber-400 shadow-md print:hidden">
                <span class="material-symbols-outlined text-xl">receipt_long</span>
            </div>
        </div>
        <div class="rak-ticket p-5 bg-white dark:bg-[#111826] border border-gray-100 dark:border-gray-700/60 rounded-xl shadow-sm flex items-center justify-between print:border-gray-300 print:p-4 print:text-black print:bg-white" style="border-left-color: rgba(20,184,166,0.5)">
            <span class="rak-tag absolute top-0 right-0 bg-teal-600 text-white text-[9px] font-semibold px-2 py-1 rounded-bl-lg print:hidden">IN-SOP</span>
            <div class="space-y-1">
                <p class="rak-tag text-[10px] font-semibold text-gray-400 uppercase print:text-gray-600">Aktivitas Masuk (In)</p>
                <p class="font-display text-2xl font-bold text-teal-600 dark:text-teal-400 tracking-tight print:text-black">{{ $transactions->where('type', 'in')->count() }} <span class="text-xs font-normal text-gray-400 print:text-gray-600">SOP</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center text-white shadow-md print:hidden">
                <span class="material-symbols-outlined text-xl">login</span>
            </div>
        </div>
        <div class="rak-ticket p-5 bg-white dark:bg-[#111826] border border-gray-100 dark:border-gray-700/60 rounded-xl shadow-sm flex items-center justify-between print:border-gray-300 print:p-4 print:text-black print:bg-white" style="border-left-color: rgba(244,63,94,0.5)">
            <span class="rak-tag absolute top-0 right-0 bg-rose-600 text-white text-[9px] font-semibold px-2 py-1 rounded-bl-lg print:hidden">OUT-SOP</span>
            <div class="space-y-1">
                <p class="rak-tag text-[10px] font-semibold text-gray-400 uppercase print:text-gray-600">Aktivitas Keluar (Out)</p>
                <p class="font-display text-2xl font-bold text-rose-600 dark:text-rose-400 tracking-tight print:text-black">{{ $transactions->where('type', 'out')->count() }} <span class="text-xs font-normal text-gray-400 print:text-gray-600">SOP</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-rose-700 flex items-center justify-center text-white shadow-md print:hidden">
                <span class="material-symbols-outlined text-xl">logout</span>
            </div>
        </div>
        <div class="rak-ticket p-5 bg-white dark:bg-[#111826] border border-gray-100 dark:border-gray-700/60 rounded-xl shadow-sm flex items-center justify-between print:border-gray-300 print:p-4 print:text-black print:bg-white" style="border-left-color: rgba(245,166,35,0.45)">
            <span class="rak-tag absolute top-0 right-0 bg-amber-500 text-white text-[9px] font-semibold px-2 py-1 rounded-bl-lg print:hidden">PENDING</span>
            <div class="space-y-1">
                <p class="rak-tag text-[10px] font-semibold text-gray-400 uppercase print:text-gray-600">Belum Terverifikasi</p>
                <p class="font-display text-2xl font-bold text-amber-600 dark:text-amber-400 tracking-tight print:text-black">{{ $transactions->where('status', 'Pending')->count() }} <span class="text-xs font-normal text-gray-400 print:text-gray-600">Antrean</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/40 flex items-center justify-center text-amber-600 dark:text-amber-400 shadow-sm print:hidden">
                <span class="material-symbols-outlined text-xl">hourglass_empty</span>
            </div>
        </div>
    </div>

    {{-- 3. TABEL + TOOLBAR --}}
    <div class="rak-ticket bg-white dark:bg-[#111826] rounded-xl border border-gray-100 dark:border-gray-700/60 shadow-sm overflow-hidden print:border-none print:shadow-none" style="border-left-color: rgba(245,166,35,0.45)">
        <span class="rak-tag absolute top-0 right-0 bg-[#101826] text-amber-400 text-[9px] font-semibold px-2.5 py-1 rounded-bl-lg z-10 print:hidden">TRX-TBL</span>

        <div class="p-5 bg-gray-50/75 dark:bg-gray-800/60 border-b border-gray-100 dark:border-gray-700/60 flex flex-col lg:flex-row justify-between items-center gap-4 print:hidden">
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <div class="relative w-full sm:w-72">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 dark:text-gray-500">
                        <span class="material-symbols-outlined text-lg">search</span>
                    </span>
                    <input type="text" id="trxSearchInput" placeholder="Cari nama produk..." class="w-full pl-11 pr-4 py-2.5 text-xs font-medium rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-4 focus:ring-amber-400/20 focus:border-amber-400 transition-all shadow-2xs placeholder:text-gray-400">
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto overflow-x-auto">
                    <button onclick="filterType('ALL')" data-type="ALL" class="type-filter-btn px-4 py-2 rounded-xl bg-amber-500 text-white text-xs font-bold uppercase tracking-wider transition shadow-sm">Semua Arus</button>
                    <button onclick="filterType('MASUK')" data-type="MASUK" class="type-filter-btn px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 text-xs font-bold uppercase tracking-wider transition">Masuk</button>
                    <button onclick="filterType('KELUAR')" data-type="KELUAR" class="type-filter-btn px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 text-xs font-bold uppercase tracking-wider transition">Keluar</button>
                </div>
            </div>
            <div class="rak-tag text-xs text-gray-500 dark:text-gray-400 w-full lg:w-auto text-left lg:text-right font-semibold bg-white dark:bg-gray-900 px-3 py-1.5 rounded-xl border border-gray-100 dark:border-gray-800">
                MENAMPILKAN <span id="visibleTrxCount" class="font-bold text-amber-500 dark:text-amber-400">{{ $transactions->count() }}</span> REKAMAN
            </div>
        </div>

        <div class="overflow-x-auto">
            <table id="mainReportTable" class="min-w-full divide-y divide-gray-100 dark:divide-gray-700/60 text-left min-w-[1000px] print:min-w-full print:divide-gray-400">
                <thead class="rak-tag bg-gray-50/75 dark:bg-gray-800/60 text-gray-400 dark:text-gray-500 font-bold uppercase text-[10px] border-b border-gray-100 dark:border-gray-700/60 print:bg-gray-100 print:text-black print:border-gray-400">
                    <tr>
                        <th class="px-6 py-4 w-[20%] print:px-2 print:py-2">Waktu / Tanggal</th>
                        <th class="px-6 py-4 w-[35%] print:px-2 print:py-2">Identitas Komoditas</th>
                        <th class="px-6 py-4 text-center w-[15%] print:px-2 print:py-2">Tipe Arus</th>
                        <th class="px-6 py-4 text-right w-[15%] print:px-2 print:py-2">Volume Jumlah</th>
                        <th class="px-6 py-4 text-center w-[15%] print:px-2 print:py-2">Status Verifikasi</th>
                    </tr>
                </thead>
                <tbody id="trxTableBody" class="divide-y divide-gray-100 dark:divide-gray-700/60 bg-white dark:bg-[#111826] print:divide-gray-300 print:text-black">
                    @forelse($transactions as $trx)
                    @php
                        $rawType = $trx->type === 'in' ? 'MASUK' : 'KELUAR';
                    @endphp
                    <tr class="trx-row-item hover:bg-amber-50/40 dark:hover:bg-gray-700/20 transition-colors duration-150 group print:bg-white" data-type="{{ $rawType }}">
                        <td class="rak-tag px-6 py-5 whitespace-nowrap font-semibold text-gray-600 dark:text-gray-400 text-xs print:px-2 print:py-2 print:text-black">
                            {{ $trx->date }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap print:px-2 print:py-2">
                            <div class="flex flex-col gap-1">
                                <span class="font-display font-bold text-gray-900 dark:text-white text-sm group-hover:text-amber-500 transition-colors target-product-name print:text-black">{{ $trx->product->name ?? 'Produk Terhapus' }}</span>
                                <span class="rak-tag text-[10px] text-gray-400 dark:text-gray-500 print:text-gray-700">ID-TRX #{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-center print:px-2 print:py-2">
                            @if($trx->type === 'in')
                                <span class="rak-tag inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold uppercase rounded-lg bg-teal-50 text-teal-700 dark:bg-teal-950/30 dark:text-teal-400 border border-teal-100 dark:border-teal-900/50 print:bg-transparent print:text-black print:border-none">
                                    Masuk
                                </span>
                            @else
                                <span class="rak-tag inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold uppercase rounded-lg bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-400 border border-rose-100 dark:border-rose-900/50 print:bg-transparent print:text-black print:border-none">
                                    Keluar
                                </span>
                            @endif
                        </td>
                        <td class="font-display px-6 py-5 whitespace-nowrap text-right font-bold text-sm {{ $trx->type === 'in' ? 'text-teal-600 dark:text-teal-400' : 'text-rose-600 dark:text-rose-400' }} print:px-2 print:py-2 print:text-black">
                            {{ $trx->type === 'in' ? '+' : '-' }}{{ number_format($trx->quantity, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-center print:px-2 print:py-2">
                            <span class="rak-tag inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold uppercase rounded-full print:text-black print:bg-transparent print:border-none
                                {{ $trx->status === 'Diterima' || $trx->status === 'Dikeluarkan' ? 'bg-teal-50 text-teal-800 dark:bg-teal-950/40 dark:text-teal-300 border border-teal-100 dark:border-teal-900/30' : ($trx->status === 'Pending' ? 'bg-amber-50 text-amber-800 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-100 dark:border-amber-900/30 animate-pulse' : 'bg-rose-50 text-rose-800 dark:bg-rose-950/40 dark:text-rose-300 border border-rose-100 dark:border-rose-900/30') }}">
                                <span class="w-2 h-2 rounded-full print:hidden {{ $trx->status === 'Diterima' || $trx->status === 'Dikeluarkan' ? 'bg-teal-500' : ($trx->status === 'Pending' ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                                {{ $trx->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm font-medium text-gray-400 dark:text-gray-500 print:text-black">
                            Belum ada riwayat aktivitas mutasi barang masuk atau keluar.
                        </td>
                    </tr>
                    @endforelse

                    <tr id="trxNoResultRow" class="hidden">
                        <td colspan="5" class="px-6 py-12 text-center text-sm font-medium text-gray-400 dark:text-gray-500">
                            Data log mutasi barang tidak ditemukan.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- STYLE KHUSUS PRINT PDF AGAR RAPI --}}
<style>
    @media print {
        header, footer, nav, aside, .sidebar, .no-print { display: none !important; }
        body { background-color: #ffffff !important; color: #000000 !important; font-family: Georgia, serif; padding: 0; }
        .id-to-print { background: transparent !important; padding: 0 !important; }
        table { table-layout: fixed; width: 100% !important; border-collapse: collapse !important; }
        th, td { border: 1px solid #94a3b8 !important; padding: 6px !important; font-size: 11px !important; }
    }
</style>

<script>
    let activeTypeFilter = 'ALL';

    document.getElementById('trxSearchInput').addEventListener('input', function() {
        runLogFiltering();
    });

    function filterType(type) {
        activeTypeFilter = type;
        const activeStyles = {
            'ALL': 'bg-amber-500 text-white font-bold text-xs uppercase tracking-wider transition shadow-sm',
            'MASUK': 'bg-teal-600 text-white font-bold text-xs uppercase tracking-wider transition shadow-sm',
            'KELUAR': 'bg-rose-600 text-white font-bold text-xs uppercase tracking-wider transition shadow-sm'
        };
        const inactiveStyle = 'type-filter-btn px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 text-xs font-bold uppercase tracking-wider transition';

        const buttons = document.querySelectorAll('.type-filter-btn');
        buttons.forEach(btn => {
            const btnType = btn.getAttribute('data-type');
            if(btnType === type) {
                btn.className = `type-filter-btn px-4 py-2 rounded-xl ${activeStyles[type]}`;
            } else {
                btn.className = inactiveStyle;
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

    /* --- ENGINE EKSPOR EXCEL DIRECT JAVASCRIPT --- */
    function exportToExcel() {
        const table = document.getElementById("mainReportTable");
        let html = table.outerHTML;

        // Hapus elemen tersembunyi agar tidak merusak formatting Excel
        html = html.replace(/<tr id="trxNoResultRow" class="hidden">([\s\S]*?)<\/tr>/g, "");

        const blob = new Blob(['\ufeff' + html], {
            type: "application/vnd.ms-excel"
        });

        const url = URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "Laporan_Log_Mutasi_Barang_" + new Date().toISOString().slice(0,10) + ".xls";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
</script>
@endsection