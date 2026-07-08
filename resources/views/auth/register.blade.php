<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin - RusellStockify</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-950">
    <section class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="w-full max-w-4xl bg-white dark:bg-gray-800 rounded-2xl shadow-lg shadow-gray-200/60 dark:shadow-none border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="flex flex-col md:flex-row">

                {{-- PANEL KIRI: BRANDING & SYSTEM INFO --}}
                <div class="w-full md:w-2/5 bg-gradient-to-br from-[#1E293B] to-[#0C1220] p-8 md:p-10 flex flex-col justify-between text-white min-h-[220px] md:min-h-[620px] relative overflow-hidden">
                    
                    {{-- Efek Dekoratif --}}
                    <div class="absolute -top-12 -right-12 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
                    <div class="absolute bottom-10 -left-10 w-32 h-32 bg-amber-500/10 rounded-full blur-xl"></div>

                    {{-- Header --}}
                    <div class="relative z-10">
                        <h2 class="text-3xl font-black tracking-tight leading-tight">Rusell<span class="text-amber-500">Stockify</span></h2>
                        <p class="text-sm font-medium text-gray-400 mt-2 border-l-2 border-amber-500 pl-3">Registrasi Admin Baru</p>
                    </div>

                    {{-- Konten Tengah: Fitur --}}
                    <div class="relative z-10 space-y-6">
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="p-2 bg-white/10 rounded-lg"><span class="material-symbols-outlined text-amber-400">group_add</span></div>
                                <div>
                                    <p class="text-sm font-bold">Akses Admin Terverifikasi</p>
                                    <p class="text-[11px] text-gray-400">Dapatkan akses penuh ke dasbor manajemen gudang.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="p-2 bg-white/10 rounded-lg"><span class="material-symbols-outlined text-amber-400">lock_person</span></div>
                                <div>
                                    <p class="text-sm font-bold">Keamanan Data Terenkripsi</p>
                                    <p class="text-[11px] text-gray-400">Informasi akun Anda dilindungi dengan standar enkripsi.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Stats --}}
                    <div class="relative z-10 pt-8 border-t border-white/10">
                        <div class="text-center">
                            <p class="text-[10px] uppercase tracking-widest text-gray-500">Bergabunglah dengan ekosistem</p>
                            <p class="text-sm font-bold mt-1 text-amber-500">RusellStockify Professional</p>
                        </div>
                    </div>
                </div>

                {{-- PANEL KANAN: FORM REGISTER --}}
                <div class="w-full md:w-3/5 p-6 sm:p-10 flex flex-col justify-center">
                    <div class="mb-6">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Buat Akun Baru</h1>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">Lengkapi data di bawah untuk mendaftar sebagai admin.</p>
                    </div>

                    <form class="space-y-4" action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label for="name" class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                        <span class="material-symbols-outlined text-lg">person</span>
                                    </span>
                                    <input type="text" name="name" id="name" class="w-full rounded-xl border-gray-200 text-sm pl-10 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" placeholder="Nama Anda" required>
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="email" class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Alamat Email</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                        <span class="material-symbols-outlined text-lg">mail</span>
                                    </span>
                                    <input type="email" name="email" id="email" class="w-full rounded-xl border-gray-200 text-sm pl-10 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" placeholder="nama@email.com" required>
                                </div>
                                @error('email')
                                    <p class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="password" class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Password</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                        <span class="material-symbols-outlined text-lg">lock</span>
                                    </span>
                                    <input type="password" name="password" id="password" placeholder="••••••••" class="w-full rounded-xl border-gray-200 text-sm pl-10 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" required>
                                </div>
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Konfirmasi</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                        <span class="material-symbols-outlined text-lg">verified_user</span>
                                    </span>
                                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" class="w-full rounded-xl border-gray-200 text-sm pl-10 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2.5" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 text-white bg-amber-500 hover:bg-amber-600 font-semibold rounded-xl text-sm px-5 py-2.5 shadow-xs transition-colors mt-2">
                            Daftar Sekarang
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </button>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 text-center pt-2">
                            Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:underline dark:text-blue-400">Login di sini</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>