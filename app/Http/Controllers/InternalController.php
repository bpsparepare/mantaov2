<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InternalController extends Controller
{
    /**
     * Menampilkan halaman internal utama.
     */
    public function index()
    {
        $instansiList = config('pdrb.all_instansi', []);
        $gdriveLinks = config('pdrb.gdrive_links', []);

        $instansiData = [];
        foreach ($instansiList as $nama) {
            $instansiData[] = [
                'nama' => $nama,
                'link' => $gdriveLinks[$nama] ?? '#',
            ];
        }

        return view('internal.index', [
            'all_instansi' => $instansiData,
        ]);
    }

    /**
     * Memproses pembuatan akun baru dari halaman internal.
     */
    public function createUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'nama_instansi' => 'required|string|max:255',
        ]);

        try {
            DB::table('mantao_users')->insert([
                'nama_instansi' => $request->input('nama_instansi'),
                'email' => $request->input('email'),
                'password' => Hash::make('BpsMantao2025!'), // Password default
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return back()->with('error', 'Gagal membuat akun: Email tersebut sudah terdaftar.');
            }
            return back()->with('error', 'Gagal membuat akun: ' . $e->getMessage());
        }

        return back()->with('success', 'Akun untuk ' . $request->input('nama_instansi') . ' berhasil dibuat!');
    }
}
