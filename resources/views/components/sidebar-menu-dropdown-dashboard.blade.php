@props(['icon' => null, 'routeName' => null, 'title' => null])
<li>
    <button type="button"
        class="flex items-center justify-between w-[calc(100%-24px)] px-5 py-3.5 mx-3 rounded-xl transition-all duration-300 group {{ request()->routeIs($routeName) ? 'bg-gray-50 text-amber-600 font-bold dark:bg-gray-800/60 dark:text-amber-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}"
        aria-controls="{{ $routeName }}" data-collapse-toggle="{{ $routeName }}">
        
        <div class="flex items-center gap-4">
            @if($icon)
                <span class="material-symbols-outlined !text-2xl text-gray-400 group-hover:text-amber-500 dark:text-gray-500">{{ $icon }}</span>
            @else
                <span class="material-symbols-outlined !text-2xl text-gray-400 group-hover:text-amber-500 dark:text-gray-500">layers</span>
            @endif
            <span>{{ $title }}</span>
        </div>

        <span class="material-symbols-outlined !text-xl transition-transform duration-200 {{ request()->routeIs($routeName) ? 'rotate-180' : '' }}">keyboard_arrow_down</span>
    </button>
    
    <ul id="{{ $routeName }}" class="{{ request()->routeIs($routeName) ? '' : 'hidden' }} mt-1.5 space-y-1.5 pl-6 pr-3">
        {{ $slot }}
    </ul>
</li>