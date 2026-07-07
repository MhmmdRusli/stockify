<nav class="fixed z-50 w-full bg-white border-b border-gray-200 dark:bg-[#0C1220] dark:border-gray-800">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-start">
        <button id="toggleSidebarMobile" aria-expanded="true" aria-controls="sidebar" class="p-2 text-gray-600 rounded-lg cursor-pointer lg:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-800 focus:ring-2 focus:ring-amber-200 dark:focus:ring-gray-800 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white">
          <svg id="toggleSidebarMobileHamburger" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
          <svg id="toggleSidebarMobileClose" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>

        {{-- 💡 Ganti nama brand & logo sesuai identitas aplikasi kamu (mis. "Stockify") --}}
        <a href="{{ url('/') }}" class="flex items-center ml-2 md:mr-24">
          <span class="flex items-center justify-center w-8 h-8 mr-2.5 rounded-lg bg-gradient-to-br from-[#1E293B] to-[#0C1220] text-amber-400 shadow-sm">
            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
          </span>
          <span class="font-display self-center text-xl font-bold sm:text-2xl whitespace-nowrap text-gray-900 dark:text-white tracking-tight">Flowbite</span>
        </a>

        <form action="#" method="GET" class="hidden lg:block lg:pl-3.5">
          <label for="topbar-search" class="sr-only">Search</label>
          <div class="relative mt-1 lg:w-96">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
              <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
            </div>
            <input type="text" name="email" id="topbar-search" class="bg-gray-50 border border-gray-200 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 block w-full pl-10 p-2.5 dark:bg-[#111826] dark:border-gray-700 dark:placeholder-gray-500 dark:text-white transition-colors" placeholder="Cari produk, SKU, transaksi...">
          </div>
        </form>
      </div>

      <div class="flex items-center">
        {{-- 💡 Tombol GitHub Star bawaan template - hapus baris ini kalau tidak diperlukan untuk sistem internal --}}
        <div class="hidden mr-3 -mb-1 sm:block">
          <a class="github-button" href="https://github.com/themesberg/flowbite-admin-dashboard" data-color-scheme="no-preference: dark; light: light; dark: light;" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star themesberg/flowbite-admin-dashboard on GitHub">Star</a>
        </div>

        <!-- Search mobile -->
        <button id="toggleSidebarMobileSearch" type="button" class="p-2 text-gray-500 rounded-lg lg:hidden hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white">
          <span class="sr-only">Search</span>
          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
        </button>

        <!-- Notifications -->
        <button type="button" data-dropdown-toggle="notification-dropdown" class="relative p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800">
          <span class="sr-only">View notifications</span>
          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>
          <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-amber-500 rounded-full ring-2 ring-white dark:ring-[#0C1220]"></span>
        </button>

        <!-- Dropdown notifikasi -->
        <div class="z-50 hidden max-w-sm my-4 overflow-hidden text-base list-none bg-white divide-y divide-gray-100 rounded-xl shadow-lg border border-gray-100 dark:divide-gray-700 dark:bg-[#111826] dark:border-gray-700" id="notification-dropdown">
          <div class="rak-tag block px-4 py-2.5 text-[11px] font-semibold text-center text-amber-500 uppercase bg-gray-50 dark:bg-[#0C1220] tracking-wider">
            Notifikasi
          </div>
          <div>
            {{-- 💡 Ganti isi notifikasi ini dengan data transaksi/stok asli (mis. "Stok Produk X menipis") --}}
            <a href="#" class="flex px-4 py-3 border-b hover:bg-gray-50 dark:hover:bg-gray-800 dark:border-gray-700">
              <div class="flex-shrink-0">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-teal-50 text-teal-600 dark:bg-teal-900/30 dark:text-teal-400">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </span>
              </div>
              <div class="w-full pl-3">
                <div class="text-gray-600 font-normal text-sm mb-1 dark:text-gray-300">Barang masuk baru menunggu <span class="font-semibold text-gray-900 dark:text-white">konfirmasi</span></div>
                <div class="rak-tag text-[10px] font-medium text-amber-600 dark:text-amber-400">beberapa saat lalu</div>
              </div>
            </a>
            <a href="#" class="flex px-4 py-3 border-b hover:bg-gray-50 dark:hover:bg-gray-800 dark:border-gray-700">
              <div class="flex-shrink-0">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                </span>
              </div>
              <div class="w-full pl-3">
                <div class="text-gray-600 font-normal text-sm mb-1 dark:text-gray-300">Stok <span class="font-semibold text-gray-900 dark:text-white">3 produk</span> berada di bawah ambang minimum</div>
                <div class="rak-tag text-[10px] font-medium text-amber-600 dark:text-amber-400">10 menit lalu</div>
              </div>
            </a>
          </div>
          <a href="{{ route('report.transaction') }}" class="block py-2.5 rak-tag text-sm font-medium text-center text-gray-700 bg-gray-50 hover:bg-gray-100 dark:bg-[#0C1220] dark:text-gray-300 dark:hover:bg-gray-800">
            <div class="inline-flex items-center">
              Lihat semua
            </div>
          </a>
        </div>

        <button id="theme-toggle" data-tooltip-target="tooltip-toggle" type="button" class="text-gray-500 dark:text-amber-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
          <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
          <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
        </button>
        <div id="tooltip-toggle" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 rak-tag text-xs font-medium text-white transition-opacity duration-300 bg-[#111826] rounded-lg shadow-sm opacity-0 tooltip">
          Ganti tema gelap/terang
          <div class="tooltip-arrow" data-popper-arrow></div>
        </div>

        <!-- Profile -->
        <div class="flex items-center ml-3">
          <div>
            {{-- 💡 Ganti dengan avatar & nama user login: Auth::user()->name --}}
            <button type="button" class="flex text-sm rounded-full ring-2 ring-amber-400/60 hover:ring-amber-400 focus:ring-4 focus:ring-amber-200 dark:focus:ring-amber-900/40 transition-all" id="user-menu-button-2" aria-expanded="false" data-dropdown-toggle="dropdown-2">
              <span class="sr-only">Buka menu pengguna</span>
              <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="foto profil">
            </button>
          </div>
          <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-xl shadow-lg border border-gray-100 dark:bg-[#111826] dark:divide-gray-700 dark:border-gray-700 overflow-hidden" id="dropdown-2">
            <div class="px-4 py-3 bg-gray-50 dark:bg-[#0C1220]" role="none">
              <p class="font-display text-sm font-semibold text-gray-900 dark:text-white" role="none">
                {{ Auth::user()->name ?? 'Neil Sims' }}
              </p>
              <p class="rak-tag text-xs font-medium text-amber-500 truncate" role="none">
                {{ Auth::user()->role ?? 'Admin' }}
              </p>
            </div>
            <ul class="py-1" role="none">
              <li>
                <a href="{{ url('/dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white" role="menuitem">Dashboard</a>
              </li>
              <li>
                <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-gray-800" role="menuitem">Keluar / Logout</button>
                </form>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</nav>