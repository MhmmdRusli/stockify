@props(['icon' => null, 'routeName' => null, 'title' => null])

<ul class="space-y-1 py-4">

    {{-- 📊 DASHBOARD (Semua Bisa Lihat) --}}
    <li>
        <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-lg transition-all duration-300 {{ Request::is('dashboard*') || Request::is('/') ? 'bg-amber-50 text-gray-900 font-semibold shadow-sm border-l-4 border-amber-400 dark:bg-amber-900/15 dark:text-amber-300 dark:border-amber-500' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 group' }}">
            <span class="material-symbols-outlined transition-colors {{ Request::is('dashboard*') || Request::is('/') ? 'text-amber-500' : 'text-gray-400 group-hover:text-amber-500 dark:text-gray-500' }}">grid_view</span>
            <span class="text-sm">Dashboard</span>
        </a>
    </li>

    {{-- 📦 DATA PRODUK (Admin, Manajer, & Staff Bisa Lihat) --}}
    @if(Auth::check() && in_array(Auth::user()->role, ['Admin', 'Manajer Gudang', 'Staff Gudang']))
    <li>
        <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-lg transition-all duration-300 {{ Request::is('products*') ? 'bg-amber-50 text-gray-900 font-semibold shadow-sm border-l-4 border-amber-400 dark:bg-amber-900/15 dark:text-amber-300 dark:border-amber-500' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 group' }}">
            <span class="material-symbols-outlined transition-colors {{ Request::is('products*') ? 'text-amber-500' : 'text-gray-400 group-hover:text-amber-500 dark:text-gray-500' }}">warehouse</span>
            <span class="text-sm">Data Produk</span>
        </a>
    </li>
    @endif

    {{-- 🏢 DATA SUPPLIER (Admin & Manajer Gudang Bisa Melihat) --}}
    @if(Auth::check() && in_array(Auth::user()->role, ['Admin', 'Manajer Gudang']))
    <li>
        <a href="{{ route('suppliers.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-lg transition-all duration-300 {{ Request::is('suppliers*') ? 'bg-amber-50 text-gray-900 font-semibold shadow-sm border-l-4 border-amber-400 dark:bg-amber-900/15 dark:text-amber-300 dark:border-amber-500' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 group' }}">
            <span class="material-symbols-outlined transition-colors {{ Request::is('suppliers*') ? 'text-amber-500' : 'text-gray-400 group-hover:text-amber-500 dark:text-gray-500' }}">corporate_fare</span>
            <span class="text-sm">Data Supplier</span>
        </a>
    </li>
    @endif

    {{-- 🛠️ DATA KATEGORI (Khusus Admin Master) --}}
    @if(Auth::check() && Auth::user()->role === 'Admin')
    <li>
        <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-lg transition-all duration-300 {{ Request::is('categories*') ? 'bg-amber-50 text-gray-900 font-semibold shadow-sm border-l-4 border-amber-400 dark:bg-amber-900/15 dark:text-amber-300 dark:border-amber-500' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 group' }}">
            <span class="material-symbols-outlined transition-colors {{ Request::is('categories*') ? 'text-amber-500' : 'text-gray-400 group-hover:text-amber-500 dark:text-gray-500' }}">category</span>
            <span class="text-sm">Data Kategori</span>
        </a>
    </li>
    {{-- 👥 MANAJEMEN USER (Khusus Admin Master) --}}
    <li>
        <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-lg transition-all duration-300 {{ Request::is('users*') ? 'bg-amber-50 text-gray-900 font-semibold shadow-sm border-l-4 border-amber-400 dark:bg-amber-900/15 dark:text-amber-300 dark:border-amber-500' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 group' }}">
            <span class="material-symbols-outlined transition-colors {{ Request::is('users*') ? 'text-amber-500' : 'text-gray-400 group-hover:text-amber-500 dark:text-gray-500' }}">manage_accounts</span>
            <span class="text-sm">Manajemen User</span>
        </a>
    </li>
    @endif

    {{-- 📋 STOCK OPNAME (Hanya dipegang oleh Manajer Gudang) --}}
    @if(Auth::check() && Auth::user()->role === 'Manajer Gudang')
    <li>
        <a href="{{ route('opnames.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-lg transition-all duration-300 {{ Request::is('opnames*') ? 'bg-amber-50 text-gray-900 font-semibold shadow-sm border-l-4 border-amber-400 dark:bg-amber-900/15 dark:text-amber-300 dark:border-amber-500' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 group' }}">
            <span class="material-symbols-outlined transition-colors {{ Request::is('opnames*') ? 'text-amber-500' : 'text-gray-400 group-hover:text-amber-500 dark:text-gray-500' }}">assignment</span>
            <span class="text-sm">Stock Opname</span>
        </a>
    </li>
    @endif

    {{-- 📥 DROPDOWN BARANG MASUK & KELUAR (Admin, Manajer, & Staff) --}}
    @if(Auth::check() && in_array(Auth::user()->role, ['Admin', 'Manajer Gudang', 'Staff Gudang']))
    <li>
        <button type="button" class="flex items-center justify-between w-full px-4 py-3 mx-2 w-[calc(100%-16px)] rounded-lg transition-all duration-300 group {{ Request::is('barang-masuk*') || Request::is('barang-keluar*') ? 'bg-gray-50 text-amber-600 font-semibold dark:bg-gray-800 dark:text-amber-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}" data-collapse-toggle="dropdown-barang">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-gray-400 group-hover:text-amber-500 dark:text-gray-500">swap_horiz</span>
                <span class="text-sm">Barang Masuk & Keluar</span>
            </div>
            <span class="material-symbols-outlined text-sm transition-transform duration-200 {{ Request::is('barang-masuk*') || Request::is('barang-keluar*') ? 'rotate-180' : '' }}">keyboard_arrow_down</span>
        </button>
        <ul id="dropdown-barang" class="{{ Request::is('barang-masuk*') || Request::is('barang-keluar*') ? '' : 'hidden' }} mt-1 space-y-1 pl-4 pr-2">
            <li>
                <a href="{{ route('barang.masuk.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-all duration-200 {{ Request::is('barang-masuk*') ? 'bg-teal-50 text-teal-700 font-semibold shadow-sm dark:bg-teal-900/20 dark:text-teal-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                    <span class="material-symbols-outlined text-sm {{ Request::is('barang-masuk*') ? 'text-teal-600 dark:text-teal-400' : 'text-teal-500' }}">input</span>
                    <span class="text-sm">Barang Masuk</span>
                </a>
            </li>
            <li>
                <a href="{{ route('barang.keluar.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-all duration-200 {{ Request::is('barang-keluar*') ? 'bg-rose-50 text-rose-700 font-semibold shadow-sm dark:bg-rose-900/20 dark:text-rose-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                    <span class="material-symbols-outlined text-sm {{ Request::is('barang-keluar*') ? 'text-rose-600 dark:text-rose-400' : 'text-rose-500' }}">output</span>
                    <span class="text-sm">Barang Keluar</span>
                </a>
            </li>
        </ul>
    </li>
    @endif

    {{-- 📊 MENU DROPDOWN LAPORAN (Admin & Manajer Gudang) --}}
    @if(Auth::check() && in_array(Auth::user()->role, ['Admin', 'Manajer Gudang']))
    <li>
        <button type="button" class="flex items-center justify-between w-full px-4 py-3 mx-2 w-[calc(100%-16px)] rounded-lg transition-all duration-300 group {{ Request::is('report*') ? 'bg-gray-50 text-amber-600 font-semibold dark:bg-gray-800 dark:text-amber-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}" data-collapse-toggle="dropdown-laporan">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-gray-400 group-hover:text-amber-500 dark:text-gray-500">analytics</span>
                <span class="text-sm">Laporan</span>
            </div>
            <span class="material-symbols-outlined text-sm transition-transform duration-200 {{ Request::is('report*') ? 'rotate-180' : '' }}">keyboard_arrow_down</span>
        </button>
        <ul id="dropdown-laporan" class="{{ Request::is('report*') ? '' : 'hidden' }} mt-1 space-y-1 pl-4 pr-2">
            <li>
                <a href="{{ route('report.stock') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-all duration-200 {{ Request::is('report/stock') ? 'bg-amber-50 text-amber-700 font-semibold shadow-sm dark:bg-amber-900/20 dark:text-amber-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                    <span class="material-symbols-outlined text-sm {{ Request::is('report/stock') ? 'text-amber-600 dark:text-amber-400' : 'text-gray-400' }}">inventory</span>
                    <span class="text-sm">Laporan Stok Barang</span>
                </a>
            </li>
            <li>
                <a href="{{ route('report.transaction') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-all duration-200 {{ Request::is('report/transactions') ? 'bg-amber-50 text-amber-700 font-semibold shadow-sm dark:bg-amber-900/20 dark:text-amber-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                    <span class="material-symbols-outlined text-sm {{ Request::is('report/transactions') ? 'text-amber-600 dark:text-amber-400' : 'text-gray-400' }}">sync_alt</span>
                    <span class="text-sm">Barang Masuk & Keluar</span>
                </a>
            </li>
            @if(Auth::user()->role === 'Admin')
            <li>
                <a href="{{ route('report.user_activity') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-all duration-200 {{ Request::is('report/users-activity') ? 'bg-amber-50 text-amber-700 font-semibold shadow-sm dark:bg-amber-900/20 dark:text-amber-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                    <span class="material-symbols-outlined text-sm {{ Request::is('report/users-activity') ? 'text-amber-600 dark:text-amber-400' : 'text-gray-400' }}">person_search</span>
                    <span class="text-sm">Aktivitas Pengguna</span>
                </a>
            </li>
            @endif
        </ul>
    </li>
    @endif

    {{-- 🚪 LOGOUT SEKSI --}}
    <li class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-[calc(100%-16px)] flex items-center gap-3 px-4 py-3 mx-2 text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-900/15 rounded-lg transition-all duration-300 text-sm text-left">
                <span class="material-symbols-outlined text-rose-500">logout</span>
                <span>Keluar / Logout</span>
            </button>
        </form>
    </li>

</ul>