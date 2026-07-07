@extends('layouts.dashboard')

@section('content')
<div class="p-6 space-y-6 bg-gray-50/50 dark:bg-gray-950 min-h-screen">

    {{-- 1. HEADER HALAMAN --}}
    <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="text-left">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Manajemen Data Supplier</h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola mitra pemasok, performa distribusi, dan informasi kontak resmi.</p>
        </div>
        <div class="shrink-0">
            @if(Auth::check() && Auth::user()->role === 'Admin')
            <button type="button" data-modal-target="add-supplier-modal" data-modal-toggle="add-supplier-modal" class="inline-flex items-center gap-2 text-white bg-emerald-600 hover:bg-emerald-700 font-semibold rounded-xl text-sm px-4 py-2.5 shadow-xs transition-colors">
                <span class="material-symbols-outlined text-sm font-bold">add</span>
                Tambah Supplier
            </button>
            @endif
        </div>
    </div>

    {{-- Notifikasi System --}}
    @if(session('success'))
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

    {{-- 2. KARTU METRIK RINGKASAN (Agar Halaman Tidak Terlihat Kosong) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Supplier Mitra</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $suppliers->count() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <span class="material-symbols-outlined text-xl">local_shipping</span>
            </div>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Kontak Terverifikasi</p>
                <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                    {{ $suppliers->whereNotNull('phone')->count() }} <span class="text-xs font-normal text-gray-400">Aktif</span>
                </p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <span class="material-symbols-outlined text-xl">contact_phone</span>
            </div>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Status Integrasi</p>
                <p class="text-sm font-semibold text-purple-600 dark:text-purple-400 flex items-center gap-1 mt-1">
                    <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span> Terhubung Sistem
                </p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/40 flex items-center justify-center text-purple-600 dark:text-purple-400">
                <span class="material-symbols-outlined text-xl">hub</span>
            </div>
        </div>
    </div>

    {{-- 3. KONTEN TABEL DATA SUPPLIER --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700 text-left table-fixed">
                <thead class="bg-gray-50/75 dark:bg-gray-700/50 text-gray-400 dark:text-gray-400 font-bold uppercase text-[11px] tracking-wider border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-4 w-[30%]">Nama Perusahaan / Supplier</th>
                        <th scope="col" class="px-6 py-4 w-[20%]">Email Resmi</th>
                        <th scope="col" class="px-6 py-4 w-[20%]">No. Telepon</th>
                        <th scope="col" class="px-6 py-4 w-[30%]">Alamat Operasional</th>
                        @if(Auth::check() && Auth::user()->role === 'Admin')
                        <th scope="col" class="px-6 py-4 text-right pr-10 w-44">Aksi Kelola</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($suppliers as $supplier)
                    <tr class="hover:bg-gray-50/40 dark:hover:bg-gray-700/20 transition-colors">
                        {{-- Kolom Nama Supplier dengan Bulatan Biru Premium --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 dark:bg-blue-950/30 dark:text-blue-400 font-bold text-xs flex items-center justify-center uppercase shadow-xs shrink-0 border border-blue-100 dark:border-blue-900/50">
                                    {{ strtoupper(substr($supplier->name, 0, 2)) }}
                                </div>
                                <div class="space-y-0.5 truncate">
                                    <span class="block text-sm font-bold text-gray-900 dark:text-white truncate">{{ $supplier->name }}</span>
                                    <span class="block text-[10px] font-mono text-gray-400 dark:text-gray-500">SPL-ID-{{ $supplier->id }}</span>
                                </div>
                            </div>
                        </td>
                        {{-- Kolom Email --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400 truncate">
                            {{ $supplier->email ?? '-' }}
                        </td>
                        {{-- Kolom Telepon --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $supplier->phone ?? '-' }}
                        </td>
                        {{-- Kolom Alamat --}}
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 truncate">
                            {{ $supplier->address ?? '-' }}
                        </td>
                        {{-- Kolom Aksi Soft Background Khusus Admin --}}
                        @if(Auth::check() && Auth::user()->role === 'Admin')
                        <td class="px-6 py-4 whitespace-nowrap text-right pr-8 space-x-1">
                            <button type="button" data-modal-target="edit-supplier-modal-{{ $supplier->id }}" data-modal-toggle="edit-supplier-modal-{{ $supplier->id }}" class="p-2 text-amber-600 hover:text-amber-700 bg-amber-50 hover:bg-amber-100/80 rounded-xl transition-colors inline-flex items-center justify-center border border-amber-100 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/50">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </button>
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus supplier ini? Semua produk yang terhubung mungkin akan terpengaruh.')" class="p-2 text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100/80 rounded-xl transition-colors inline-flex items-center justify-center border border-red-100 dark:bg-red-950/20 dark:text-red-400 dark:border-red-900/50">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>

                    {{-- 4. MODAL EDIT SUPPLIER --}}
                    @if(Auth::check() && Auth::user()->role === 'Admin')
                    <div id="edit-supplier-modal-{{ $supplier->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full bg-gray-900/40 backdrop-blur-xs flex items-center justify-center">
                        <div class="relative w-full max-w-md max-h-full bg-white rounded-2xl shadow-xl dark:bg-gray-800 p-6 border border-gray-100 dark:border-gray-700 mx-auto mt-10">
                            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4 mb-5">
                                <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    <span class="material-symbols-outlined text-amber-500">edit_note</span> Modifikasi Data Supplier
                                </h3>
                                <button type="button" data-modal-toggle="edit-supplier-modal-{{ $supplier->id }}" class="text-gray-400 hover:text-gray-500 flex items-center"><span class="material-symbols-outlined">close</span></button>
                            </div>

                            <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="space-y-4 text-left">
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nama Supplier</label>
                                        <input type="text" name="name" value="{{ $supplier->name }}" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" required>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Email Resmi</label>
                                        <input type="email" name="email" value="{{ $supplier->email }}" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">No. Telepon</label>
                                        <input type="text" name="phone" value="{{ $supplier->phone }}" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Alamat Lengkap</label>
                                        <textarea name="address" rows="3" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 py-2.5" required>{{ $supplier->address }}</textarea>
                                    </div>
                                </div>
                                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end space-x-2">
                                    <button type="button" data-modal-toggle="edit-supplier-modal-{{ $supplier->id }}" class="px-4 py-2.5 text-xs font-semibold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Batal</button>
                                    <button type="submit" class="px-4 py-2.5 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    @empty
                    <tr>
                        <td colspan="{{ Auth::check() && Auth::user()->role === 'Admin' ? 5 : 4 }}" class="px-6 py-12 text-center text-sm font-medium text-gray-400 dark:text-gray-500">Belum ada mitra supplier terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- 5. MODAL TAMBAH SUPPLIER BARU --}}
@if(Auth::check() && Auth::user()->role === 'Admin')
<div id="add-supplier-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full bg-gray-900/40 backdrop-blur-xs flex items-center justify-center">
    <div class="relative w-full max-w-md max-h-full bg-white rounded-2xl shadow-xl dark:bg-gray-800 p-6 border border-gray-100 dark:border-gray-700 mx-auto mt-10">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4 mb-5">
            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">playlist_add</span> Tambah Supplier Baru
            </h3>
            <button type="button" data-modal-toggle="add-supplier-modal" class="text-gray-400 hover:text-gray-500 flex items-center"><span class="material-symbols-outlined">close</span></button>
        </div>

        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf
            <div class="space-y-4 text-left">
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nama Perusahaan / Supplier</label>
                    <input type="text" name="name" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" placeholder="Contoh: PT. Sinar Abadi" required>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Email Resmi</label>
                    <input type="email" name="email" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" placeholder="admin@sinarabadi.com">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">No. Telepon</label>
                    <input type="text" name="phone" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" placeholder="021-xxxxxxx atau 081xxxx">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Alamat Gudang / Operasional</label>
                    <textarea name="address" rows="3" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" placeholder="Masukkan alamat lengkap operasional..." required></textarea>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end space-x-2">
                <button type="button" data-modal-toggle="add-supplier-modal" class="px-4 py-2.5 text-xs font-semibold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Batal</button>
                <button type="submit" class="px-4 py-2.5 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs">Simpan Supplier</button>
            </div>
        </form>
    </div>
</div>
@endif

@endsection