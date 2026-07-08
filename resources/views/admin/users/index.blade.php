@extends('layouts.dashboard')

@section('content')
<div class="p-6 space-y-6 bg-gray-50/50 dark:bg-gray-950 min-h-screen">

    {{-- 1. HEADER HALAMAN --}}
    <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="text-left">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Manajemen User</h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola dan pantau seluruh kredensial serta hak akses akun di dalam sistem gudang.</p>
        </div>
        <div class="shrink-0">
            <button type="button" data-modal-target="addUserModal" data-modal-toggle="addUserModal" class="inline-flex items-center gap-2 text-white bg-amber-500 hover:bg-amber-600 font-semibold rounded-xl text-sm px-4 py-2.5 shadow-xs transition-colors">
                <span class="material-symbols-outlined text-sm font-bold">add</span>
                Tambah User Baru
            </button>
        </div>
    </div>

    {{-- Notifikasi Sukses / Gagal --}}
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
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Pengguna</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $users->total() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <span class="material-symbols-outlined text-xl">group</span>
            </div>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Administrator</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $users->where('role', 'Admin')->count() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/40 flex items-center justify-center text-purple-600 dark:text-purple-400">
                <span class="material-symbols-outlined text-xl">shield_person</span>
            </div>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Manajer Gudang</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $users->where('role', 'Manajer Gudang')->count() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <span class="material-symbols-outlined text-xl">badge</span>
            </div>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Staff Gudang</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $users->where('role', 'Staff Gudang')->count() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/40 flex items-center justify-center text-amber-600 dark:text-amber-400">
                <span class="material-symbols-outlined text-xl">engineering</span>
            </div>
        </div>
    </div>

    {{-- 3. INPUT CARI DATA --}}
    <div class="relative w-full">
        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 dark:text-gray-500">
            <span class="material-symbols-outlined text-lg">search</span>
        </span>
        <input type="text" id="searchInput" placeholder="Cari data pengguna sistem..." class="w-full pl-11 pr-4 py-3 text-xs font-medium rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-2xs placeholder:text-gray-400">
    </div>

    {{-- 4. KONTEN TABEL DATA USER --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700 text-left">
                <thead class="bg-gray-50/75 dark:bg-gray-700/50 text-gray-400 dark:text-gray-400 font-bold uppercase text-[11px] tracking-wider border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4">Nama User</th>
                        <th class="px-6 py-4">Email Utama</th>
                        <th class="px-6 py-4">Hak Akses / Role</th>
                        <th class="px-6 py-4 text-right pr-10">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/40 dark:hover:bg-gray-700/20 transition-colors">
                        {{-- Kolom Nama --}}
                        <td class="px-6 py-5 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 dark:bg-blue-950/30 dark:text-blue-400 font-bold text-xs flex items-center justify-center uppercase shadow-xs shrink-0 border border-blue-100 dark:border-blue-900/50">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <div class="space-y-0.5">
                                    <span class="block text-sm font-bold text-gray-900 dark:text-white">{{ $user->name }}</span>
                                    <span class="block text-[10px] font-mono text-gray-400 dark:text-gray-500">UID-{{ $user->id }}</span>
                                </div>
                            </div>
                        </td>
                        {{-- Kolom Email --}}
                        <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $user->email }}
                        </td>
                        {{-- Kolom Role Badge --}}
                        <td class="px-6 py-5 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 text-[11px] font-bold rounded-lg uppercase tracking-wide border
                                {{ $user->role === 'Admin' ? 'bg-purple-50 text-purple-700 border-purple-100 dark:bg-purple-950/20 dark:text-purple-400 dark:border-purple-900' : '' }}
                                {{ $user->role === 'Manajer Gudang' ? 'bg-blue-50 text-blue-700 border-blue-100 dark:bg-blue-950/20 dark:text-blue-400 dark:border-blue-900' : '' }}
                                {{ $user->role === 'Staff Gudang' ? 'bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900' : '' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        {{-- Kolom Tombol Aksi --}}
                        <td class="px-6 py-5 whitespace-nowrap text-right pr-8 space-x-1">
                            <button type="button" data-modal-target="editUserModal-{{ $user->id }}" data-modal-toggle="editUserModal-{{ $user->id }}" class="p-2 text-amber-600 hover:text-amber-700 bg-amber-50 hover:bg-amber-100/80 rounded-xl transition-colors inline-flex items-center justify-center border border-amber-100 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/50">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </button>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengguna ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100/80 rounded-xl transition-colors inline-flex items-center justify-center border border-red-100 dark:bg-red-950/20 dark:text-red-400 dark:border-red-900/50">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>

                    {{-- 5. MODAL EDIT USER --}}
                    <div id="editUserModal-{{ $user->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full bg-gray-900/40 backdrop-blur-xs flex items-center justify-center">
                        <div class="relative w-full max-w-xl max-h-full bg-white rounded-2xl shadow-xl dark:bg-gray-800 p-6 border border-gray-100 dark:border-gray-700 mx-auto mt-10">
                            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4 mb-5">
                                <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    <span class="material-symbols-outlined text-amber-500">edit_square</span> Ubah Data Pengguna
                                </h3>
                                <button type="button" data-modal-toggle="editUserModal-{{ $user->id }}" class="text-gray-400 hover:text-gray-500 flex items-center"><span class="material-symbols-outlined">close</span></button>
                            </div>
                            <form action="{{ route('users.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-left">
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                                        <input type="text" name="name" value="{{ $user->name }}" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" required>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Email Sistem</label>
                                        <input type="email" name="email" value="{{ $user->email }}" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" required>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Password (Isi jika ingin diubah)</label>
                                        <input type="password" name="password" placeholder="••••••••" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Hak Akses / Peran</label>
                                        <select name="role" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" required>
                                            <option value="Admin" {{ $user->role === 'Admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="Manajer Gudang" {{ $user->role === 'Manajer Gudang' ? 'selected' : '' }}>Manajer Gudang</option>
                                            <option value="Staff Gudang" {{ $user->role === 'Staff Gudang' ? 'selected' : '' }}>Staff Gudang</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end space-x-2">
                                    <button type="button" data-modal-toggle="editUserModal-{{ $user->id }}" class="px-4 py-2.5 text-xs font-semibold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Batal</button>
                                    <button type="submit" class="px-4 py-2.5 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-sm font-medium text-gray-400 dark:text-gray-500">
                            Belum ada data pengguna sistem.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

{{-- 6. MODAL TAMBAH USER BARU --}}
<div id="addUserModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full bg-gray-900/40 backdrop-blur-xs flex items-center justify-center">
    <div class="relative w-full max-w-xl max-h-full bg-white rounded-2xl shadow-xl dark:bg-gray-800 p-6 border border-gray-100 dark:border-gray-700 mx-auto mt-10">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4 mb-5">
            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-500">person_add</span> Daftarkan Pengguna Baru
            </h3>
            <button type="button" data-modal-toggle="addUserModal" class="text-gray-400 hover:text-gray-500 flex items-center"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-left">
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" required placeholder="Contoh: Budi Santoso">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Email Utama</label>
                    <input type="email" name="email" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" required placeholder="budi@gudang.com">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Kata Sandi Akun</label>
                    <input type="password" name="password" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" required placeholder="Minimal 6 karakter">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Hak Akses / Peran</label>
                    <select name="role" class="w-full rounded-xl border-gray-200 text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" required>
                        <option value="Admin">Admin</option>
                        <option value="Manajer Gudang">Manajer Gudang</option>
                        <option value="Staff Gudang" selected>Staff Gudang</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end space-x-2">
                <button type="button" data-modal-toggle="addUserModal" class="px-4 py-2.5 text-xs font-semibold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Batal</button>
                <button type="submit" class="px-4 py-2.5 text-xs font-semibold text-white bg-amber-500 hover:bg-amber-600 rounded-xl shadow-xs">Daftarkan Akun</button>
            </div>
        </form>
    </div>
</div>

{{-- 7. JAVASCRIPT REALTIME PENCARIAN --}}
<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            let nameText = row.cells[0] ? row.cells[0].innerText.toLowerCase() : '';
            let emailText = row.cells[1] ? row.cells[1].innerText.toLowerCase() : '';

            if (nameText.includes(filter) || emailText.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

@endsection