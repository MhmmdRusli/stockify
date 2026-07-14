<ul class="space-y-2 py-6 text-base font-medium">

    {{-- 📊 DASHBOARD --}}
    <li>
        <a href="/dashboard" class="flex items-center gap-4 px-5 py-3.5 mx-3 rounded-xl transition-all duration-300 {{ Request::is('dashboard*') || Request::is('/') ? 'bg-amber-50 text-gray-950 font-bold shadow-md border-l-4 border-amber-500 dark:bg-amber-950/20 dark:text-amber-300 dark:border-amber-500' : 'text-gray-600 hover:bg-gray-50/80 dark:text-gray-400 dark:hover:bg-gray-800/50 group' }}">
            <span class="material-symbols-outlined !text-2xl transition-colors {{ Request::is('dashboard*') || Request::is('/') ? 'text-amber-500' : 'text-gray-400 group-hover:text-amber-500 dark:text-gray-500' }}">grid_view</span>
            <span>Dashboard</span>
        </a>
    </li>

    {{-- 📦 DATA PRODUK --}}
    @if(Auth::check() && in_array(strtolower(Auth::user()->role), ['admin', 'manajer gudang', 'staff gudang']))
    <li>
        <a href="{{ route('products.index') }}" class="flex items-center gap-4 px-5 py-3.5 mx-3 rounded-xl transition-all duration-300 {{ Request::is('products*') ? 'bg-amber-50 text-gray-950 font-bold shadow-md border-l-4 border-amber-500 dark:bg-amber-950/20 dark:text-amber-300 dark:border-amber-500' : 'text-gray-600 hover:bg-gray-50/80 dark:text-gray-400 dark:hover:bg-gray-800/50 group' }}">
            <span class="material-symbols-outlined !text-2xl transition-colors {{ Request::is('products*') ? 'text-amber-500' : 'text-gray-400 group-hover:text-amber-500 dark:text-gray-500' }}">warehouse</span>
            <span>Data Produk</span>
        </a>
    </li>
    @endif

    {{-- 🏢 DATA SUPPLIER --}}
    @if(Auth::check() && in_array(strtolower(Auth::user()->role), ['admin', 'manajer gudang']))
    <li>
        <a href="{{ route('suppliers.index') }}" class="flex items-center gap-4 px-5 py-3.5 mx-3 rounded-xl transition-all duration-300 {{ Request::is('suppliers*') ? 'bg-amber-50 text-gray-950 font-bold shadow-md border-l-4 border-amber-500 dark:bg-amber-950/20 dark:text-amber-300 dark:border-amber-500' : 'text-gray-600 hover:bg-gray-50/80 dark:text-gray-400 dark:hover:bg-gray-800/50 group' }}">
            <span class="material-symbols-outlined !text-2xl transition-colors {{ Request::is('suppliers*') ? 'text-amber-500' : 'text-gray-400 group-hover:text-amber-500 dark:text-gray-500' }}">corporate_fare</span>
            <span>Data Supplier</span>
        </a>
    </li>
    @endif

    {{-- 🛠️ DATA KATEGORI & USER --}}
    @if(Auth::check() && strtolower(Auth::user()->role) === 'admin')
    <li>
        <a href="{{ route('categories.index') }}" class="flex items-center gap-4 px-5 py-3.5 mx-3 rounded-xl transition-all duration-300 {{ Request::is('categories*') ? 'bg-amber-50 text-gray-950 font-bold shadow-md border-l-4 border-amber-500 dark:bg-amber-950/20 dark:text-amber-300 dark:border-amber-500' : 'text-gray-600 hover:bg-gray-50/80 dark:text-gray-400 dark:hover:bg-gray-800/50 group' }}">
            <span class="material-symbols-outlined !text-2xl transition-colors {{ Request::is('categories*') ? 'text-amber-500' : 'text-gray-400 group-hover:text-amber-500 dark:text-gray-500' }}">category</span>
            <span>Data Kategori</span>
        </a>
    </li>
    <li>
        <a href="{{ route('users.index') }}" class="flex items-center gap-4 px-5 py-3.5 mx-3 rounded-xl transition-all duration-300 {{ Request::is('users*') ? 'bg-amber-50 text-gray-950 font-bold shadow-md border-l-4 border-amber-500 dark:bg-amber-950/20 dark:text-amber-300 dark:border-amber-500' : 'text-gray-600 hover:bg-gray-50/80 dark:text-gray-400 dark:hover:bg-gray-800/50 group' }}">
            <span class="material-symbols-outlined !text-2xl transition-colors {{ Request::is('users*') ? 'text-amber-500' : 'text-gray-400 group-hover:text-amber-500 dark:text-gray-500' }}">manage_accounts</span>
            <span>Manajemen User</span>
        </a>
    </li>
    @endif

    {{-- 📋 STOCK OPNAME --}}
    @if(Auth::check() && in_array(strtolower(Auth::user()->role), ['admin', 'manajer gudang']))
    <li>
        <a href="{{ route('opnames.index') }}" class="flex items-center gap-4 px-5 py-3.5 mx-3 rounded-xl transition-all duration-300 {{ Request::is('opnames*') ? 'bg-amber-50 text-gray-950 font-bold shadow-md border-l-4 border-amber-500 dark:bg-amber-950/20 dark:text-amber-300 dark:border-amber-500' : 'text-gray-600 hover:bg-gray-50/80 dark:text-gray-400 dark:hover:bg-gray-800/50 group' }}">
            <span class="material-symbols-outlined !text-2xl transition-colors {{ Request::is('opnames*') ? 'text-amber-500' : 'text-gray-400 group-hover:text-amber-500 dark:text-gray-500' }}">assignment</span>
            <span>Stock Opname</span>
        </a>
    </li>
    @endif

    {{-- 📥 DROPDOWN LOGISTIK --}}
    @if(Auth::check() && in_array(strtolower(Auth::user()->role), ['admin', 'manajer gudang', 'staff gudang']))
    <li x-data="{ open: {{ Request::is('barang-masuk*') || Request::is('barang-keluar*') ? 'true' : 'false' }} }">
        <button type="button" @click="open = !open" class="flex items-center justify-between w-[calc(100%-24px)] px-5 py-3.5 mx-3 rounded-xl transition-all duration-300 group {{ Request::is('barang-masuk*') || Request::is('barang-keluar*') ? 'bg-gray-50 text-amber-600 font-bold dark:bg-gray-800/60 dark:text-amber-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
            <div class="flex items-center gap-4">
                <span class="material-symbols-outlined !text-2xl text-gray-400 group-hover:text-amber-500 dark:text-gray-500">swap_horiz</span>
                <span>Barang Logistik</span>
            </div>
            <span class="material-symbols-outlined !text-xl transition-transform duration-200" :class="open ? 'rotate-180' : ''">keyboard_arrow_down</span>
        </button>
        <ul x-show="open" class="mt-1.5 space-y-1.5 pl-6 pr-3" x-cloak>
            <li>
                <a href="{{ route('barang.masuk.index') }}" class="flex items-center justify-between px-5 py-3 rounded-xl {{ Request::is('barang-masuk*') ? 'bg-amber-50 text-amber-950 font-bold shadow-sm border-l-2 border-amber-500 dark:bg-amber-950/20 dark:text-amber-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/40' }}">
                    <div class="flex items-center gap-3.5">
                        <span class="material-symbols-outlined !text-xl text-amber-500">input</span>
                        <span>Barang Masuk</span>
                    </div>

                    {{-- Titik Merah Persetujuan (Hanya muncul di Manager/Admin jika ada pengajuan) --}}
                    @if(Auth::check() && in_array(strtolower(Auth::user()->role), ['admin', 'manajer gudang']) && $pendingMasukCount > 0)
                        <span class="relative flex h-2.5 w-2.5 shrink-0">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                        </span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('barang.keluar.index') }}" class="flex items-center gap-3.5 px-5 py-3 rounded-xl {{ Request::is('barang-keluar*') ? 'bg-rose-50 text-rose-800 font-bold shadow-sm border-l-2 border-rose-500 dark:bg-rose-950/20 dark:text-rose-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/40' }}">
                    <span class="material-symbols-outlined !text-xl text-rose-500">output</span>
                    <span>Barang Keluar</span>
                </a>
            </li>
        </ul>
    </li>
    @endif

    {{-- 📊 MENU DROPDOWN LAPORAN --}}
    @if(Auth::check() && in_array(strtolower(Auth::user()->role), ['admin', 'manajer gudang']))
    <li x-data="{ open: {{ Request::is('report*') ? 'true' : 'false' }} }">
        <button type="button" @click="open = !open" class="flex items-center justify-between w-[calc(100%-24px)] px-5 py-3.5 mx-3 rounded-xl transition-all duration-300 group {{ Request::is('report*') ? 'bg-gray-50 text-amber-600 font-bold dark:bg-gray-800/60 dark:text-amber-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
            <div class="flex items-center gap-4">
                <span class="material-symbols-outlined !text-2xl text-gray-400 group-hover:text-amber-500 dark:text-gray-500">analytics</span>
                <span>Laporan Analisis</span>
            </div>
            <span class="material-symbols-outlined !text-xl transition-transform duration-200" :class="open ? 'rotate-180' : ''">keyboard_arrow_down</span>
        </button>
        <ul x-show="open" class="mt-1.5 space-y-1.5 pl-6 pr-3" x-cloak>
            <li>
                <a href="{{ route('report.stock') }}" class="flex items-center gap-3.5 px-5 py-3 rounded-xl {{ Request::is('report/stock') ? 'bg-amber-50 text-amber-800 font-bold shadow-sm border-l-2 border-amber-500 dark:bg-amber-950/20 dark:text-amber-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/40' }}">
                    <span class="material-symbols-outlined !text-xl text-amber-500">inventory</span>
                    <span>Laporan Stok Barang</span>
                </a>
            </li>
            <li>
                <a href="{{ route('report.transaction') }}" class="flex items-center gap-3.5 px-5 py-3 rounded-xl {{ Request::is('report/transactions') ? 'bg-amber-50 text-amber-800 font-bold shadow-sm border-l-2 border-amber-500 dark:bg-amber-950/20 dark:text-amber-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/40' }}">
                    <span class="material-symbols-outlined !text-xl text-amber-500">sync_alt</span>
                    <span>Mutasi Masuk & Keluar</span>
                </a>
            </li>
            @if(strtolower(Auth::user()->role) === 'admin')
            <li>
                <a href="{{ route('report.user_activity') }}" class="flex items-center justify-between px-5 py-3 rounded-xl {{ Request::is('report/users-activity') ? 'bg-amber-50 text-amber-800 font-bold shadow-sm border-l-2 border-amber-500 dark:bg-amber-950/20 dark:text-amber-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/40' }}">
                    <div class="flex items-center gap-3.5">
                        <span class="material-symbols-outlined !text-xl text-amber-500">person_search</span>
                        <span>Aktivitas Pengguna</span>
                    </div>
                    
                    {{-- Titik Merah Aktivitas Baru --}}
                    @if(isset($activities) && $activities->isNotEmpty())
                    <span class="relative flex h-2.5 w-2.5 shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                    </span>
                    @endif
                </a>
            </li>
            @endif
        </ul>
    </li>
    @endif

    {{-- ⚙️ MENU PENGATURAN --}}
    @if(Auth::check() && strtolower(Auth::user()->role) === 'admin')
    <li>
        <a href="{{ route('admin.settings') }}" class="flex items-center gap-4 px-5 py-3.5 mx-3 rounded-xl transition-all duration-300 {{ Request::is('admin/settings*') ? 'bg-amber-50 text-gray-950 font-bold shadow-md border-l-4 border-amber-500 dark:bg-amber-950/20 dark:text-amber-300 dark:border-amber-500' : 'text-gray-600 hover:bg-gray-50/80 dark:text-gray-400 dark:hover:bg-gray-800/50 group' }}">
            <span class="material-symbols-outlined !text-2xl transition-colors {{ Request::is('admin/settings*') ? 'text-amber-500' : 'text-gray-400 group-hover:text-amber-500 dark:text-gray-500' }}">settings</span>
            <span>Pengaturan</span>
        </a>
    </li>
    @endif

    {{-- 🚪 LOGOUT --}}
    <li class="pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-[calc(100%-24px)] flex items-center gap-4 px-5 py-3.5 mx-3 text-rose-600 font-bold hover:bg-rose-50 dark:hover:bg-rose-950/20 rounded-xl transition-all text-left">
                <span class="material-symbols-outlined !text-2xl text-rose-500">logout</span>
                <span>Keluar Aplikasi</span>
            </button>
        </form>
    </li>
</ul>