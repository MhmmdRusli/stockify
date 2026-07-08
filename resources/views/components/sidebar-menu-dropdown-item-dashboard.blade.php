@props(['icon' => 'link', 'routeName' => null, 'title' => null])
<li>
    <a href="{{ Route::has($routeName) ? route($routeName) : '#' }}"
        class="flex items-center gap-3.5 px-5 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs($routeName) ? 'bg-amber-50 text-amber-950 font-bold shadow-sm border-l-2 border-amber-500 dark:bg-amber-950/20 dark:text-amber-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
        <span class="material-symbols-outlined !text-xl {{ request()->routeIs($routeName) ? 'text-amber-500' : 'text-gray-400' }}">{{ $icon }}</span>
        <span>{{ $title }}</span>
    </a>
</li>