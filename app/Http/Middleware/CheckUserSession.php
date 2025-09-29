<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckUserSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('user')) {
            // Jika tidak ada data 'user' di session, tendang ke halaman login
            return redirect('/login');
        }

        // Jika ada, lanjutkan ke halaman yang dituju (dashboard, form, dll.)
        return $next($request);
    }
}
