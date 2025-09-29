<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session; // Jangan lupa tambahkan ini

class CheckAdminRole
{
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil data user dari sesi
        $user = Session::get('user');

        // Periksa apakah user ada dan rolenya adalah 'admin'
        if ($user && isset($user['role']) && $user['role'] === 'admin') {
            // Jika ya, izinkan akses ke halaman berikutnya
            return $next($request);
        }

        // Jika tidak, tolak akses dan tampilkan halaman 403 (Forbidden)
        abort(403, 'AKSES DITOLAK. ANDA BUKAN ADMIN.');
    }
}
