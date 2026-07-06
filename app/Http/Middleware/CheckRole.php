<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 2. Cek apakah role user saat ini ada di dalam daftar yang diizinkan
        // (Kita pakai ucwords/huruf kapital di awal karena di database kamu tertulis "Manajer Gudang")
        if (in_array(auth()->user()->role, $roles)) {
            return $next($request);
        }

        // 3. Jika tidak sesuai, tolak akses
        abort(403, 'Kamu tidak memiliki hak akses untuk membuka halaman ini.');
    }
}