@extends('layouts.dashboard')

@section('content')
<div class="p-6 space-y-6 bg-gray-50/50 dark:bg-gray-950 min-h-screen">

    {{-- 1. HEADER HALAMAN --}}
    <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="text-left">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-2xl">history_edu</span>
                Log Aktivitas Transaksi Pengguna
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">Daftar rekam jejak aksi mutasi barang masuk dan keluar yang dilakukan oleh staf gudang secara real-time.</p>
        </div>
        
        {{-- DI ATAS: Tombol Cetak & Excel --}}
        <div class="flex items-center gap-3 w-full md:w-auto justify-end">
            <a href="{{ route('report.user_activity.print') }}" target="_blank"
               class="px-4 py-2.5 bg-slate-700 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs flex items-center gap-2 cursor-pointer transition">
                <span class="material-symbols-outlined text-sm">print</span> CETAK PDF
            </a>
            <button onclick="exportAuditToExcel()" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs flex items-center gap-2 cursor-pointer transition">
                <span class="material-symbols-outlined text-sm">download_for_offline</span> UNDUH EXCEL
            </button>
        </div>
    </div>

    {{-- 2. KARTU METRIK RINGKASAN --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Jejak Aksi</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activities->count() }} <span class="text-xs font-normal text-gray-400">Log</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <span class="material-symbols-outlined text-xl">analytics</span>
            </div>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Mutasi Masuk</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activities->where('type', 'in')->count() }} <span class="text-xs font-normal text-gray-400">Aksi</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <span class="material-symbols-outlined text-xl">input</span>
            </div>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Mutasi Keluar</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activities->where('type', 'out')->count() }} <span class="text-xs font-normal text-gray-400">Aksi</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-950/40 flex items-center justify-center text-red-600 dark:text-red-400">
                <span class="material-symbols-outlined text-xl">output</span>
            </div>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Kontributor</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activities->pluck('user_id')->unique()->count() }} <span class="text-xs font-normal text-gray-400">User</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/40 flex items-center justify-center text-amber-600 dark:text-amber-400">
                <span class="material-symbols-outlined text-xl">supervised_user_circle</span>
            </div>
        </div>
    </div>

    {{-- DI BAWAH: Search Input (Posisi Kiri & Ukuran Standar md:w-80) --}}
    <div class="flex justify-start">
        <div class="relative w-full md:w-80 shrink-0">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 dark:text-gray-500">
                <span class="material-symbols-outlined text-lg">search</span>
            </span>
            <input type="text" id="robustSearchInput" placeholder="Cari staf, role, atau nama produk..." class="w-full pl-11 pr-4 py-2.5 text-xs font-medium rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-gray-400 shadow-xs">
        </div>
    </div>

    {{-- 3. TABEL AUDIT --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs overflow-hidden">

        <div class="p-5 bg-gray-50/75 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <span class="font-bold text-gray-700 dark:text-gray-300 tracking-wide text-sm">TABEL AUDIT AKTIVITAS</span>
            <span class="text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-xl border border-gray-100 dark:border-gray-700 font-semibold">
                Terfilter: <span id="robustCount" class="font-bold text-blue-600 dark:text-blue-400">{{ $activities->count() }}</span> data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table id="auditActivityTable" class="min-w-full divide-y divide-gray-100 dark:divide-gray-700 text-left min-w-[1100px]">
                <thead class="bg-gray-50/75 dark:bg-gray-700/50 text-gray-400 dark:text-gray-400 font-bold uppercase text-[11px] tracking-wider border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4 w-[18%]">Waktu Eksekusi</th>
                        <th class="px-6 py-4 w-[22%]">Staf Eksekutor</th>
                        <th class="px-6 py-4 w-[12%]">Otoritas Role</th>
                        <th class="px-6 py-4 w-[33%]">Aktivitas & Target Produk</th>
                        <th class="px-6 py-4 w-[15%] text-right">Volume Perubahan</th>
                    </tr>
                </thead>
                <tbody id="robustTableBody" class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($activities as $act)
                    <tr class="robust-row hover:bg-gray-50/40 dark:hover:bg-gray-700/20 transition-colors">
                        <td class="px-6 py-5 whitespace-nowrap text-gray-500 dark:text-gray-400 font-semibold text-sm">
                            {{ $act->created_at->format('d M Y — H:i') }} WIB
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 dark:bg-blue-950/30 dark:text-blue-400 font-bold text-xs flex items-center justify-center uppercase shadow-xs shrink-0 border border-blue-100 dark:border-blue-900/50">
                                    {{ substr($act->user->name ?? 'U', 0, 2) }}
                                </div>
                                <div class="space-y-0.5">
                                    <span class="block font-bold text-gray-900 dark:text-white text-sm target-user-name">{{ $act->user->name ?? 'Sistem Otomatis' }}</span>
                                    <span class="block text-[10px] text-gray-400 dark:text-gray-500 font-mono">UID-{{ $act->user->id ?? '00' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600 uppercase tracking-wide target-user-role">
                                {{ $act->user->role ?? 'System' }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center gap-2 flex-wrap">
                                    @if($act->type === 'in')
                                        <span class="text-[10px] font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-1 rounded-md border border-emerald-100 dark:border-emerald-900/50">[MASUK]</span>
                                    @else
                                        <span class="text-[10px] font-bold text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-950/40 px-2 py-1 rounded-md border border-red-100 dark:border-red-900/50">[KELUAR]</span>
                                    @endif

                                    @if($act->action === 'pengajuan')
                                        <span class="text-[10px] font-bold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/40 px-2 py-1 rounded-md border border-blue-100 dark:border-blue-900/50">PENGAJUAN</span>
                                    @elseif($act->action === 'konfirmasi')
                                        <span class="text-[10px] font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-1 rounded-md border border-emerald-100 dark:border-emerald-900/50">KONFIRMASI</span>
                                    @else
                                        <span class="text-[10px] font-bold text-gray-700 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded-md border border-gray-200 dark:border-gray-700">DITOLAK</span>
                                    @endif

                                    <span class="font-bold text-gray-900 dark:text-white text-sm target-product-name">{{ $act->product->name ?? 'Produk Terhapus' }}</span>
                                </div>
                                <span class="text-xs font-normal text-gray-400 dark:text-gray-500">
                                    @if($act->action === 'pengajuan')
                                        Mengajukan transaksi mutasi {{ $act->type === 'in' ? 'barang masuk' : 'barang keluar' }}.
                                    @elseif($act->action === 'konfirmasi')
                                        Mengonfirmasi dan memvalidasi mutasi fisik komoditas gudang.
                                    @else
                                        Menolak pengajuan transaksi mutasi komoditas gudang.
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-right">
                            <div class="flex flex-col items-end gap-2">
                                <span class="font-bold text-sm {{ $act->type === 'in' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $act->type === 'in' ? '+' : '-' }}{{ number_format($act->quantity, 0, ',', '.') }} Pcs
                                </span>
                                <div class="w-24 bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden">
                                    <div class="h-full {{ $act->type === 'in' ? 'bg-emerald-500' : 'bg-red-500' }}" style="width: {{ min(($act->quantity / 100) * 100, 100) }}%"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm font-medium text-gray-400 dark:text-gray-500">
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

    function exportAuditToExcel() {
        const table = document.getElementById("auditActivityTable");
        let html = table.outerHTML;
        html = html.replace(/<tr id="robustNoResult" class="hidden">([\s\S]*?)<\/tr>/g, "");

        const blob = new Blob(['\ufeff' + html], { type: "application/vnd.ms-excel" });
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