<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password; // Tambahkan ini

class AdminController extends Controller
{
    public function showCreateUserForm()
    {
        // Ambil daftar semua nama instansi dari config atau helper
        $instansiFromConfig = config('pdrb.all_instansi', []);

        // Tambahkan opsi 'Admin' secara manual ke daftar
        $all_instansi = array_merge(['BPS Kota Parepare (Admin)'], $instansiFromConfig);
        sort($all_instansi);

        return view('admin.create-user', ['all_instansi' => $all_instansi]);
    }

    public function createUser(Request $request)
    {
        // --- VALIDASI DIPERBARUI ---
        $request->validate([
            'email' => 'required|email|unique:mantao_users,email',
            'nama_instansi' => 'required|string|max:255',
            'role' => 'required|in:admin,user',
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal harus 8 karakter.',
        ]);

        try {
            // --- LOGIKA PENYIMPANAN DIPERBARUI ---
            DB::table('mantao_users')->insert([
                'nama_instansi' => $request->input('nama_instansi'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')), // Ambil password dari form
                'role' => $request->input('role'), // Simpan role dari form
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat akun: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('internal.index')->with('success', 'Akun untuk ' . $request->input('nama_instansi') . ' berhasil dibuat!');
    }
}
