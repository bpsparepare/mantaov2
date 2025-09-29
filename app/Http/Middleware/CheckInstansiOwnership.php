<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session; // Tambahkan ini
use Illuminate\Support\Str; // Tambahkan ini

class CheckInstansiOwnership
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Session::get('user');

        // Admin selalu diizinkan mengakses form manapun
        if ($user && isset($user['role']) && $user['role'] === 'admin') {
            return $next($request);
        }

        // Ambil slug dari URL (contoh: 'dinas-lingkungan-hidup')
        $routeSlug = $request->route('slugInstansi');

        // Ambil nama instansi user yang login, lalu ubah menjadi slug
        $userInstansiName = $user['nama_instansi'] ?? '';
        $userSlug = Str::slug($userInstansiName);

        // Bandingkan slug dari URL dengan slug milik user
        if ($userSlug === $routeSlug) {
            // Jika cocok, izinkan akses
            return $next($request);
        }

        // Jika tidak cocok, tolak akses
        abort(403, 'ANDA TIDAK BERHAK MENGAKSES FORM INI.');
    }
}
