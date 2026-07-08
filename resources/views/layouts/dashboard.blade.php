<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="#">
    <meta name="author" content="#">
    <meta name="generator" content="Laravel">

    {{-- ✨ DINAMIS: Menampilkan Nama Aplikasi dari database di Title Bar Browser --}}
    <title>Dashboard - {{ \App\Models\Setting::where('key', 'app_name')->value('value') ?? 'Flowbite' }}</title>
    
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="canonical" href="{{ request()->fullUrl() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">

    @if(isset($page->params['robots']))
        <meta name="robots" content="{{ $page->params['robots'] }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Google Material Symbols Outlined --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />

    <style>
        .material-symbols-outlined {
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        :root {
            --primary: #3b82f6;
            --secondary: #10b981;
            --secondary-container: #d2f8e9;
            --on-secondary-container: #047857;
            --on-surface-variant: #4b5563;
            --surface-container-low: #f3f4f6;
            --outline: #9ca3af;
            --outline-variant: #e5e7eb;
            --error: #ef4444;
        }

        html.dark :root {
            --on-surface-variant: #d1d5db;
            --surface-container-low: #374151;
            --outline: #6b7280;
            --outline-variant: #374151;
            --secondary-container: #064e3b;
            --on-secondary-container: #34d399;
        }

        .bg-secondary-container { background-color: var(--secondary-container); }
        .text-on-secondary-container { color: var(--on-secondary-container); }
        .text-on-surface-variant { color: var(--on-surface-variant); }
        .hover\:bg-surface-container-low:hover { background-color: var(--surface-container-low); }
        .bg-surface-container-low { background-color: var(--surface-container-low); }
        .text-outline { color: var(--outline); }
        .border-outline-variant { border-color: var(--outline-variant); }
        .text-error { color: var(--error); }
        
        .text-label-md { font-size: 14px; }
        .text-label-sm { font-size: 12.5px; }
        .font-label-md { font-weight: 500; }
        .font-label-sm { font-weight: 400; }
    </style>

    {{-- ✨ DINAMIS: Favicon Ikon Tab Browser mengikuti logo yang diunggah --}}
    @php
        $favLogo = \App\Models\Setting::where('key', 'app_logo')->value('value');
    @endphp
    @if($favLogo)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favLogo) }}">
        <link rel="apple-touch-icon" href="{{ asset('storage/' . $favLogo) }}">
    @else
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="icon" type="image/png" href="/favicon.ico">
    @endif
    
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
@php
    $whiteBg = isset($params['white_bg']) && $params['white_bg'];
@endphp
<body class="{{ $whiteBg ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }}">
    <x-navbar-dashboard/>
    
    {{-- 💡 Jarak padding atas diperbaiki dari pt-16 menjadi pt-24 agar konten tidak mepet ke navbar --}}
    <div class="flex pt-24 overflow-hidden bg-gray-50 dark:bg-gray-900">
        <x-sidebar.admin-sidebar/>
        <div id="main-content" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900">
            <main>
                {{-- Bagian injector konten halaman --}}
                @yield('content')
            </main>
            <x-footer-dashboard/>
        </div>
    </div>
    
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.2/datepicker.min.js"></script>
</body>
</html>