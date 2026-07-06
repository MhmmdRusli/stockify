@props(['icon' => null, 'routeName' => null, 'title' => null])

<ul class="pb-2 space-y-2">
    
    <li>
        <a href="/dashboard" class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700 {{ Request::is('dashboard*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
            <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
            </svg>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>

    @if(Auth::check() && (Auth::user()->role === 'Admin' || Auth::user()->role === 'Manajer Gudang'))
    <li>
        <a href="{{ route('products.index') }}" class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700 {{ Request::is('*products*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
            <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>
            </svg>
            <span class="ml-3">Data Produk</span>
        </a>
    </li>
    @endif

    @if(Auth::check() && Auth::user()->role === 'Admin')
    <li>
        <a href="{{ route('suppliers.index') }}" class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700 {{ Request::is('*suppliers*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
            <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
            </svg>
            <span class="ml-3">Data Supplier</span>
        </a>
    </li>
    @endif

    @if(Auth::check() && Auth::user()->role === 'Admin')
    <li>
        <a href="{{ route('categories.index') }}" class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700 {{ Request::is('*categories*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
            <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
            </svg>
            <span class="ml-3">Data Kategori</span>
        </a>
    </li>
    @endif

    @if(Auth::check() && Auth::user()->role === 'Manajer Gudang')
    <li>
        <a href="{{ route('opnames.index') }}" class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700 {{ Request::is('*opnames*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
            <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9 4a1 1 0 10-2 0v2H9a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V9z" clip-rule="evenodd"></path>
            </svg>
            <span class="ml-3">Stock Opname</span>
        </a>
    </li>
    @endif

    @if(Auth::check() && (Auth::user()->role === 'Manajer Gudang' || Auth::user()->role === 'Staff Gudang'))
    <li>
        <a href="{{ route('barang.masuk.index') }}" class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700 {{ Request::is('*barang-masuk*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
            <svg class="w-6 h-6 text-green-500 transition duration-75 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            <span class="ml-3">Barang Masuk</span>
        </a>
    </li>
    @endif

    @if(Auth::check() && (Auth::user()->role === 'Manajer Gudang' || Auth::user()->role === 'Staff Gudang'))
    <li>
        <a href="{{ route('barang.keluar.index') }}" class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700 {{ Request::is('*barang-keluar*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
            <svg class="w-6 h-6 text-red-500 transition duration-75 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 8l4 4m0 0l-4 4m4-4H3m5 4v1a3 3 0 003 3h4a3 3 0 003-3V7a3 3 0 00-3-3h-4a3 3 0 00-3 3v1"></path>
            </svg>
            <span class="ml-3">Barang Keluar</span>
        </a>
    </li>
    @endif

    <li class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center p-2 text-base text-red-600 rounded-lg hover:bg-red-50 group dark:text-red-400 dark:hover:bg-gray-700">
                <svg class="w-6 h-6 text-red-500 transition duration-75 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span class="ml-3">Keluar / Logout</span>
            </button>
        </form>
    </li>

</ul>