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
                <span class="material-symbols-outlined text-amber-500 dark:text-amber-400 text-2xl print:hidden">history_edu</span>
                Log Aktivitas <span class="text-amber-500 dark:text-amber-400">Transaksi Pengguna</span>
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">Daftar rekam jejak aksi mutasi barang masuk dan keluar yang dilakukan oleh staf gudang secara real-time.</p>
        </div>
        <div class="shrink-0 flex gap-2 print:hidden">
            <button onclick="exportAuditToExcel()" class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-xl shadow-md transition-all duration-300">
                <span class="material-symbols-outlined text-sm">download_for_offline</span> UNDUH EXCEL
            </button>
            <button onclick="window.print()" class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-br from-[#1E293B] to-[#101826] hover:shadow-lg text-amber-400 font-bold text-xs rounded-xl shadow-md transition-all duration-300">
                <span class="material-symbols-outlined text-sm">print</span> CETAK PDF
            </button>
        </div>
    </div>

    {{-- 2. KARTU METRIK RINGKASAN --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 print:grid-cols-4 print:gap-2">
        <div class="rak-ticket p-5 bg-white dark:bg-[#111826] border border-gray-100 dark:border-gray-700/60 rounded-xl shadow-sm flex items-center justify-between print:border-gray-300 print:p-4 print:text-black print:bg-white" style="border-left-color: rgba(245,166,35,0.45)">
            <span class="rak-tag absolute top-0 right-0 bg-[#101826] text-amber-400 text-[9px] font-semibold px-2 py-1 rounded-bl-lg print:hidden">LOG-ALL</span>
            <div class="space-y-1">
                <p class="rak-tag text-[10px] font-semibold text-gray-400 uppercase print:text-gray-600">Total Jejak Aksi</p>
                <p class="font-display text-2xl font-bold text-gray-900 dark:text-white tracking-tight print:text-black">{{ $activities->count() }} <span class="text-xs font-normal text-gray-400 print:text-gray-600">Log</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#1E293B] to-[#101826] flex items-center justify-center text-amber-400 shadow-md print:hidden">
                <span class="material-symbols-outlined text-xl">analytics</span>
            </div>
        </div>
        <div class="rak-ticket p-5 bg-white dark:bg-[#111826] border border-gray-100 dark:border-gray-700/60 rounded-xl shadow-sm flex items-center justify-between print:border-gray-300 print:p-4 print:text-black print:bg-white" style="border-left-color: rgba(20,184,166,0.5)">
            <span class="rak-tag absolute top-0 right-0 bg-teal-600 text-white text-[9px] font-semibold px-2 py-1 rounded-bl-lg print:hidden">IN-LOG</span>
            <div class="space-y-1">
                <p class="rak-tag text-[10px] font-semibold text-gray-400 uppercase print:text-gray-600">Mutasi Masuk</p>
                <p class="font-display text-2xl font-bold text-teal-600 dark:text-teal-400 tracking-tight print:text-black">{{ $activities->where('type', 'in')->count() }} <span class="text-xs font-normal text-gray-400 print:text-gray-600">Aksi</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center text-white shadow-md print:hidden">
                <span class="material-symbols-outlined text-xl">input</span>
            </div>
        </div>
        <div class="rak-ticket p-5 bg-white dark:bg-[#111826] border border-gray-100 dark:border-gray-700/60 rounded-xl shadow-sm flex items-center justify-between print:border-gray-300 print:p-4 print:text-black print:bg-white" style="border-left-color: rgba(244,63,94,0.5)">
            <span class="rak-tag absolute top-0 right-0 bg-rose-600 text-white text-[9px] font-semibold px-2 py-1 rounded-bl-lg print:hidden">OUT-LOG</span>
            <div class="space-y-1">
                <p class="rak-tag text-[10px] font-semibold text-gray-400 uppercase print:text-gray-600">Mutasi Keluar</p>
                <p class="font-display text-2xl font-bold text-rose-600 dark:text-rose-400 tracking-tight print:text-black">{{ $activities->where('type', 'out')->count() }} <span class="text-xs font-normal text-gray-400 print:text-gray-600">Aksi</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-rose-700 flex items-center justify-center text-white shadow-md print:hidden">
                <span class="material-symbols-outlined text-xl">output</span>
            </div>
        </div>
        <div class="rak-ticket p-5 bg-white dark:bg-[#111826] border border-gray-100 dark:border-gray-700/60 rounded-xl shadow-sm flex items-center justify-between print:border-gray-300 print:p-4 print:text-black print:bg-white" style="border-left-color: rgba(245,166,35,0.45)">
            <span class="rak-tag absolute top-0 right-0 bg-[#101826] text-amber-400 text-[9px] font-semibold px-2 py-1 rounded-bl-lg print:hidden">USR-LOG</span>
            <div class="space-y-1">
                <p class="rak-tag text-[10px] font-semibold text-gray-400 uppercase print:text-gray-600">Total Kontributor</p>
                <p class="font-display text-2xl font-bold text-gray-900 dark:text-white tracking-tight print:text-black">{{ $activities->pluck('user_id')->unique()->count() }} <span class="text-xs font-normal text-gray-400 print:text-gray-600">User</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#1E293B] to-[#101826] flex items-center justify-center text-amber-400 shadow-md print:hidden">
                <span class="material-symbols-outlined text-xl">supervised_user_circle</span>
            </div>
        </div>
    </div>

    {{-- TOOLBAR: SEARCH (pindah dari header, dilebarkan) --}}
    <div class="print:hidden">
        <div class="relative w-full">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 dark:text-gray-500">
                <span class="material-symbols-outlined text-lg">search</span>
            </span>
            <input type="text" id="robustSearchInput" placeholder="Cari staf, role, atau nama produk..." class="w-full pl-11 pr-4 py-2.5 text-xs font-medium rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-4 focus:ring-amber-400/20 focus:border-amber-400 transition-all shadow-sm placeholder:text-gray-400">
        </div>
    </div>

    {{-- 3. TABEL AUDIT --}}
    <div class="rak-ticket bg-white dark:bg-[#111826] rounded-xl border border-gray-100 dark:border-gray-700/60 shadow-sm overflow-hidden print:border-none print:shadow-none" style="border-left-color: rgba(245,166,35,0.45)">
        <span class="rak-tag absolute top-0 right-0 bg-[#101826] text-amber-400 text-[9px] font-semibold px-2.5 py-1 rounded-bl-lg z-10 print:hidden">AUDIT-TBL</span>

        <div class="p-5 bg-gray-50/75 dark:bg-gray-800/60 border-b border-gray-100 dark:border-gray-700/60 flex justify-between items-center print:hidden">
            <span class="font-display font-bold text-gray-700 dark:text-gray-300 tracking-wide text-sm">TABEL AUDIT AKTIVITAS</span>
            <span class="rak-tag text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-xl border border-gray-100 dark:border-gray-700/60 font-semibold">
                TERFILTER: <span id="robustCount" class="font-bold text-amber-500 dark:text-amber-400">{{ $activities->count() }}</span> DATA
            </span>
        </div>

        <div class="overflow-x-auto">
            <table id="auditActivityTable" class="min-w-full divide-y divide-gray-100 dark:divide-gray-700/60 text-left min-w-[1100px] print:min-w-full print:divide-gray-400">
                <thead class="rak-tag bg-gray-50/75 dark:bg-gray-800/60 text-gray-400 dark:text-gray-500 font-bold uppercase text-[10px] border-b border-gray-100 dark:border-gray-700/60 print:bg-gray-100 print:text-black print:border-gray-400">
                    <tr>
                        <th class="px-6 py-4 w-[18%] print:px-2 print:py-2">Waktu Eksekusi</th>
                        <th class="px-6 py-4 w-[22%] print:px-2 print:py-2">Staf Eksekutor</th>
                        <th class="px-6 py-4 w-[12%] print:px-2 print:py-2">Otoritas Role</th>
                        <th class="px-6 py-4 w-[33%] print:px-2 print:py-2">Aktivitas & Target Produk</th>
                        <th class="px-6 py-4 w-[15%] text-right print:px-2 print:py-2">Volume Perubahan</th>
                    </tr>
                </thead>
                <tbody id="robustTableBody" class="divide-y divide-gray-100 dark:divide-gray-700/60 bg-white dark:bg-[#111826] print:divide-gray-300 print:text-black">
                    @forelse($activities as $act)
                    <tr class="robust-row hover:bg-amber-50/40 dark:hover:bg-gray-700/20 transition-colors duration-150 print:bg-white">
                        <td class="rak-tag px-6 py-5 whitespace-nowrap text-gray-500 dark:text-gray-400 font-semibold text-xs print:px-2 print:py-2 print:text-black">
                            {{ $act->created_at->format('d M Y — H:i') }} WIB
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap print:px-2 print:py-2">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[#1E293B] to-[#101826] text-amber-400 font-bold text-xs flex items-center justify-center uppercase shadow-sm shrink-0 print:hidden">
                                    {{ substr($act->user->name ?? 'U', 0, 2) }}
                                </div>
                                <div class="space-y-0.5">
                                    <span class="block font-display font-bold text-gray-900 dark:text-white text-sm target-user-name print:text-black">{{ $act->user->name ?? 'Sistem Otomatis' }}</span>
                                    <span class="rak-tag block text-[10px] text-gray-400 dark:text-gray-500 print:text-gray-700">UID-{{ $act->user_id ?? '00' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap print:px-2 print:py-2">
                            <span class="rak-tag px-2.5 py-1 text-[10px] font-semibold rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300 border border-amber-100 dark:border-amber-800 uppercase target-user-role print:p-0 print:border-none print:bg-transparent print:text-black">
                                {{ $act->user->role ?? 'System' }}
                            </span>
                        </td>
                        <td class="px-6 py-5 print:px-2 print:py-2">
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center gap-2 flex-wrap">
                                    @if($act->type === 'in')
                                        <span class="rak-tag text-[10px] font-bold text-teal-700 dark:text-teal-400 bg-teal-50 dark:bg-teal-950/40 px-2 py-1 rounded-md border border-teal-100 dark:border-teal-900/50 print:bg-transparent print:text-black print:p-0 print:border-none print:text-xs">[MASUK]</span>
                                    @else
                                        <span class="rak-tag text-[10px] font-bold text-rose-700 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 px-2 py-1 rounded-md border border-rose-100 dark:border-rose-900/50 print:bg-transparent print:text-black print:p-0 print:border-none print:text-xs">[KELUAR]</span>
                                    @endif
                                    <span class="font-display font-bold text-gray-900 dark:text-white text-sm target-product-name print:text-black">{{ $act->product->name ?? 'Produk Terhapus' }}</span>
                                </div>
                                <span class="text-xs font-normal text-gray-400 dark:text-gray-500 print:text-gray-700">Mengajukan mutasi pencatatan fisik komoditas gudang.</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-right print:px-2 print:py-2">
                            <div class="flex flex-col items-end gap-2">
                                <span class="font-display font-bold text-sm {{ $act->type === 'in' ? 'text-teal-600 dark:text-teal-400' : 'text-rose-600 dark:text-rose-400' }} print:text-black">
                                    {{ $act->type === 'in' ? '+' : '-' }}{{ number_format($act->quantity, 0, ',', '.') }} Pcs
                                </span>
                                <div class="w-24 bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden print:hidden">
                                    <div class="h-full {{ $act->type === 'in' ? 'bg-teal-500' : 'bg-rose-500' }}" style="width: {{ min(($act->quantity / 100) * 100, 100) }}%"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm font-medium text-gray-400 dark:text-gray-500 print:text-black">
                            Belum ada riwayat aktivitas log dari pengguna yang terdata.
                        </td>
                    </tr>
                    @endforelse

                    <tr id="robustNoResult" class="hidden">
                        <td colspan="5" class="px-6 py-12 text-center text-sm font-medium text-gray-400 dark:text-gray-500">
                            Data log aktivitas yang dicari tidak ditemukan.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MEDIA PRINT RULES --}}
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
    document.getElementById('robustSearchInput').addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.robust-row');
        let totalVisible = 0;

        rows.forEach(row => {
            const userName = row.querySelector('.target-user-name').innerText.toLowerCase();
            const userRole = row.querySelector('.target-user-role').innerText.toLowerCase();
            const prodName = row.querySelector('.target-product-name').innerText.toLowerCase();

            const isMatch = userName.includes(query) || userRole.includes(query) || prodName.includes(query);

            if (isMatch) {
                row.classList.remove('hidden');
                totalVisible++;
            } else {
                row.classList.add('hidden');
            }
        });

        document.getElementById('robustCount').innerText = totalVisible;

        const fallbackRow = document.getElementById('robustNoResult');
        if (fallbackRow) {
            if (totalVisible === 0 && rows.length > 0) {
                fallbackRow.classList.remove('hidden');
            } else {
                fallbackRow.classList.add('hidden');
            }
        }
    });

    /* --- DIRECT EXCEL EXPORTER ENGINE --- */
    function exportAuditToExcel() {
        const table = document.getElementById("auditActivityTable");
        let html = table.outerHTML;

        // Buang row tidak berwujud agar tidak merusak formatting file spreadsheet
        html = html.replace(/<tr id="robustNoResult" class="hidden">([\s\S]*?)<\/tr>/g, "");

        const blob = new Blob(['\ufeff' + html], {
            type: "application/vnd.ms-excel"
        });

        const url = URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "Audit_Log_Aktivitas_User_" + new Date().toISOString().slice(0,10) + ".xls";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
</script>
@endsection