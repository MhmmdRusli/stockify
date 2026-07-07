@extends('layouts.dashboard')

@section('content')
<div class="p-4 md:p-6 max-w-[1600px] mx-auto w-full space-y-6 text-sm font-sans antialiased text-gray-700 dark:text-gray-300">

    {{-- HEADER AREA: Besar & Tegas --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-normal tracking-tight text-gray-900 dark:text-white flex items-center gap-3">
                <span class="material-symbols-outlined text-blue-600 text-3xl font-normal">history_edu</span> 
                Log Aktivitas Transaksi Pengguna
            </h1>
            <p class="text-sm font-light text-gray-500 dark:text-gray-400 mt-1">Daftar rekam jejak aksi mutasi barang masuk dan keluar yang dilakukan oleh staf gudang secara real-time.</p>
        </div>
        
        {{-- Search Input --}}
        <div class="relative w-full md:w-80">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg">search</span>
            <input type="text" id="robustSearchInput" placeholder="Cari staf, role, atau nama produk..." class="w-full pl-10 pr-4 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-gray-900 dark:text-white">
        </div>
    </div>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs uppercase font-bold text-gray-400 tracking-wider">Total Jejak Aksi</span>
                <h3 class="text-3xl font-black text-gray-900 dark:text-white">{{ $activities->count() }} <span class="text-xs font-normal text-gray-400">Log</span></h3>
            </div>
            <div class="p-3.5 bg-blue-50 dark:bg-gray-700 text-blue-600 rounded-xl">
                <span class="material-symbols-outlined text-2xl">analytics</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs uppercase font-bold text-emerald-500 tracking-wider">Mutasi Masuk</span>
                <h3 class="text-3xl font-black text-emerald-600 dark:text-emerald-400">{{ $activities->where('type', 'in')->count() }} <span class="text-xs font-normal text-gray-400">Aksi</span></h3>
            </div>
            <div class="p-3.5 bg-emerald-50 dark:bg-gray-700 text-emerald-600 rounded-xl">
                <span class="material-symbols-outlined text-2xl">input</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs uppercase font-bold text-rose-500 tracking-wider">Mutasi Keluar</span>
                <h3 class="text-3xl font-black text-rose-600 dark:text-rose-400">{{ $activities->where('type', 'out')->count() }} <span class="text-xs font-normal text-gray-400">Aksi</span></h3>
            </div>
            <div class="p-3.5 bg-rose-50 dark:bg-gray-700 text-rose-600 rounded-xl">
                <span class="material-symbols-outlined text-2xl">output</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs uppercase font-bold text-amber-500 tracking-wider">Total Kontributor</span>
                <h3 class="text-3xl font-black text-amber-600 dark:text-amber-400">{{ $activities->pluck('user_id')->unique()->count() }} <span class="text-xs font-normal text-gray-400">User</span></h3>
            </div>
            <div class="p-3.5 bg-amber-50 dark:bg-gray-700 text-amber-600 rounded-xl">
                <span class="material-symbols-outlined text-2xl">supervised_user_circle</span>
            </div>
        </div>
    </div>

    {{-- MAIN TABLE ROW: Lebih Longgar & Lega --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        
        <div class="p-5 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <span class="font-bold text-gray-800 dark:text-gray-200 tracking-wide">TABEL AUDIT AKTIVITAS</span>
            <span class="text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 font-semibold">
                Terfilter: <span id="robustCount" class="font-black text-blue-600 dark:text-blue-400">{{ $activities->count() }}</span> data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1100px]">
                <thead class="bg-gray-100 dark:bg-gray-700 text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-5 w-[18%]">Waktu Eksekusi</th>
                        <th class="px-6 py-5 w-[22%]">Staf Eksekuotor</th>
                        <th class="px-6 py-5 w-[12%]">Otoritas Role</th>
                        <th class="px-6 py-5 w-[33%]">Aktivitas & Target Produk</th>
                        <th class="px-6 py-5 w-[15%] text-right">Volume Perubahan</th>
                    </tr>
                </thead>
                <tbody id="robustTableBody" class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    @forelse($activities as $act)
                    <tr class="robust-row hover:bg-gray-50/80 dark:hover:bg-gray-700/30 transition-colors">
                        
                        <td class="px-6 py-6 whitespace-nowrap text-gray-500 dark:text-gray-400 font-semibold">
                            {{ $act->created_at->format('d M Y — H:i') }} WIB
                        </td>

                        <td class="px-6 py-6 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                                    {{ substr($act->user->name ?? 'U', 0, 2) }}
                                </div>
                                <div class="flex flex-col gap-0.5">
                                    <span class="font-bold text-gray-900 dark:text-white text-base target-user-name">{{ $act->user->name ?? 'Sistem Otomatis' }}</span>
                                    <span class="text-xs text-gray-400 dark:text-gray-500 font-mono">UID-{{ $act->user_id ?? '00' }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-6 whitespace-nowrap">
                            <span class="px-3 py-1.5 text-xs font-bold rounded-lg bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-600 uppercase tracking-wide target-user-role">
                                {{ $act->user->role ?? 'System' }}
                            </span>
                        </td>

                        <td class="px-6 py-6">
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    @if($act->type === 'in')
                                        <span class="text-[10px] font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2.5 py-1 rounded border border-emerald-200 dark:border-emerald-900 tracking-wide">MASUK</span>
                                    @else
                                        <span class="text-[10px] font-bold text-rose-700 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 px-2.5 py-1 rounded border border-rose-200 dark:border-rose-900 tracking-wide">KELUAR</span>
                                    @endif
                                    <span class="font-bold text-gray-900 dark:text-white text-base target-product-name">{{ $act->product->name ?? 'Produk Terhapus' }}</span>
                                </div>
                                <span class="text-xs font-normal text-gray-400 dark:text-gray-500">Mengajukan mutasi pencatatan fisik komoditas gudang.</span>
                            </div>
                        </td>

                        <td class="px-6 py-6 whitespace-nowrap text-right">
                            <div class="flex flex-col items-end gap-2">
                                <span class="font-black text-base {{ $act->type === 'in' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $act->type === 'in' ? '+' : '-' }}{{ number_format($act->quantity, 0, ',', '.') }} Pcs
                                </span>
                                {{-- Bar Indikator Volume --}}
                                <div class="w-24 bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden">
                                    <div class="h-full {{ $act->type === 'in' ? 'bg-emerald-500' : 'bg-rose-500' }}" style="width: {{ min(($act->quantity / 100) * 100, 100) }}%"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-gray-400 dark:text-gray-500 font-medium">
                            <span class="material-symbols-outlined text-5xl block mb-2 opacity-30">history_toggle_off</span>
                            Belum ada riwayat aktivitas log dari pengguna yang terdata.
                        </td>
                    </tr>
                    @endforelse

                    {{-- Search Fallback Row --}}
                    <tr id="robustNoResult" class="hidden">
                        <td colspan="5" class="px-6 py-16 text-center text-gray-400 dark:text-gray-500 font-medium">
                            <span class="material-symbols-outlined text-4xl block mb-2 opacity-30">search_off</span>
                            Data log aktivitas yang dicari tidak ditemukan.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- JAVASCRIPT: LIVE ENGINE SEARCH FILTER --}}
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
</script>
@endsection