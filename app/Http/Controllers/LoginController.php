<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function handleLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            $user = DB::table('mantao_users')->where('email', $request->input('email'))->first();

            if ($user && Hash::check($request->input('password'), $user->password)) {
                Session::put('user', (array)$user); // Simpan sebagai array
                return redirect()->intended('/dashboard');
            }

            return back()->withErrors(['email' => 'Email atau password yang Anda masukkan salah.']);
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal terhubung ke database. Periksa koneksi dan file .env Anda.']);
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }
}
