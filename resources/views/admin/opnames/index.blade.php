@extends('layouts.dashboard')

@section('content')
<div class="p-6 space-y-6 bg-gray-50/50 dark:bg-gray-950 min-h-screen">

    {{-- 1. HEADER HALAMAN --}}
    <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="text-left">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Riwayat Stock Opname</h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola dan pantau hasil pencocokan stok fisik gudang dengan catatan sistem.</p>
        </div>
        <div class="shrink-0">
            <button type="button" data-modal-target="add-opname-modal" data-modal-toggle="add-opname-modal" class="inline-flex items-center gap-2 text-white bg-amber-500 hover:bg-amber-600 font-semibold rounded-xl text-sm px-4 py-2.5 shadow-xs transition-colors">
                <span class="material-symbols-outlined text-sm font-bold">add</span>
                Catat Opname Baru
            </button>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="p-4 text-xs font-semibold text-green-700 bg-green-50 rounded-xl border border-green-100 dark:bg-gray-800 dark:text-green-400 dark:border-gray-700 shadow-2xs">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="p-4 text-xs font-semibold text-red-700 bg-red-50 rounded-xl border border-red-100 dark:bg-gray-800 dark:text-red-400 dark:border-gray-700 shadow-2xs">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 2. KARTU METRIK RINGKASAN --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Selisih Stok Masuk</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $opnames->where('physical_stock', '>', 'system_stock')->count() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <span class="material-symbols-outlined text-xl">trending_up</span>
            </div>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Selisih Stok Keluar</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $opnames->where('physical_stock', '<', 'system_stock')->count() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-950/40 flex items-center justify-center text-red-600 dark:text-red-400">
                <span class="material-symbols-outlined text-xl">trending_down</span>
            </div>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Log Opname</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $opnames->count() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <span class="material-symbols-outlined text-xl">fact_check</span>
            </div>
        </div>
    </div>

    {{-- 3. INPUT CARI DATA --}}
    <div class="relative w-full">
        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 dark:text-gray-500">
            <span class="material-symbols-outlined text-lg">search</span>
        </span>
        <input type="text" id="opnameSearchInput" placeholder="Cari produk, SKU, atau petugas..." class="w-full pl-11 pr-4 py-3 text-xs font-medium rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-2xs placeholder:text-gray-400">
    </div>

    {{-- 4. KONTEN TABEL RIWAYAT OPNAME --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700 text-left">
                <thead class="bg-gray-50/75 dark:bg-gray-700/50 text-gray-400 dark:text-gray-400 font-bold uppercase text-[11px] tracking-wider border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Produk</th>
                        <th class="px-6 py-4 text-center">Jenis</th>
                        <th class="px-6 py-4 text-center">Rincian Stok</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4">Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($opnames as $opname)
                    <tr class="hover:bg-gray-50/40 dark:hover:bg-gray-700/20 transition-colors">
                        <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $opname->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 dark:bg-blue-950/30 dark:text-blue-400 font-bold text-xs flex items-center justify-center uppercase shadow-xs shrink-0 border border-blue-100 dark:border-blue-900/50">
                                    {{ substr($opname->product->name, 0, 2) }}
                                </div>
                                <div class="space-y-0.5">
                                    <span class="block text-sm font-bold text-gray-900 dark:text-white">{{ $opname->product->name }}</span>
                                    <span class="block text-[10px] font-mono text-gray-400 dark:text-gray-500">{{ $opname->product->sku }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-center">
                            @if ($opname->physical_stock > $opname->system_stock)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 dark:text-emerald-300 rounded-full border border-emerald-100 dark:border-emerald-900/30">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Stok Masuk
                                </span>
                            @elseif($opname->physical_stock < $opname->system_stock)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-red-800 bg-red-50 dark:bg-red-950/40 dark:text-red-300 rounded-full border border-red-100 dark:border-red-900/30">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Stok Keluar
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-gray-600 bg-gray-100 dark:bg-gray-700 dark:text-gray-300 rounded-full border border-gray-200 dark:border-gray-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Sesuai
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-center">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Fisik: {{ $opname->physical_stock }}</p>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">Sistem: {{ $opname->system_stock }}</p>
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-500 dark:text-gray-400 truncate max-w-[180px]">
                            {{ $opname->notes ?? '-' }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-sm font-semibold text-gray-700 dark:text-gray-300">
                            {{ $opname->user->name ?? 'System' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sm font-medium text-gray-400 dark:text-gray-500">
                            Belum ada riwayat stock opname.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- 5. MODAL CATAT OPNAME BARU --}}
<div id="add-opname-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full bg-gray-900/40 backdrop-blur-xs flex items-center justify-center">
    <div class="relative w-full max-w-md max-h-full bg-white rounded-2xl shadow-xl dark:bg-gray-800 p-6 border border-gray-100 dark:border-gray-700 mx-auto mt-10">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4 mb-5">
            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-500">fact_check</span> Catat Opname Stok
            </h3>
            <button type="button" data-modal-toggle="add-opname-modal" class="text-gray-400 hover:text-gray-500 flex items-center"><span class="material-symbols-outlined">close</span></button>
        </div>

        <form action="{{ route('opnames.store') }}" method="POST">
            @csrf
            <div class="grid gap-4 mb-5 grid-cols-2 text-left">
                <div class="col-span-2">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Pilih Produk</label>
                    <select name="product_id" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" required>
                        <option value="" disabled selected>-- Pilih Produk yang di-Opname --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->sku }} - {{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Stok Sistem</label>
                    <input type="number" name="system_stock" min="0" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" placeholder="Stok di komputer" required>
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Stok Fisik</label>
                    <input type="number" name="physical_stock" min="0" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" placeholder="Stok nyata di gudang" required>
                </div>

                <div class="col-span-2">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Keterangan / Alasan</label>
                    <textarea name="notes" rows="3" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" placeholder="Contoh: Selisih hitung, barang rusak, dll..."></textarea>
                </div>
            </div>
            <div class="mt-2 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end space-x-2">
                <button type="button" data-modal-toggle="add-opname-modal" class="px-4 py-2.5 text-xs font-semibold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Batal</button>
                <button type="submit" class="px-4 py-2.5 text-xs font-semibold text-white bg-amber-500 hover:bg-amber-600 rounded-xl shadow-xs">Simpan Log Opname</button>
            </div>
        </form>
    </div>
</div>

{{-- 6. SCRIPT LIVE PENCARIAN --}}
<script>
    document.getElementById('opnameSearchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>
@endsection