<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama setelah user login.
     */
    public function showDashboard()
    {
        $user = Session::get('user');

        if (!$user) {
            return redirect('/login')->withErrors(['email' => 'Sesi Anda telah berakhir, silakan login kembali.']);
        }

        $loggedInInstansiNama = $user['nama_instansi'] ?? null;
        $userRole = $user['role'] ?? 'user';

        return view('dashboard', [
            'all_instansi' => $this->getAllInstansiData(),
            'logged_in_instansi' => $loggedInInstansiNama,
            'user_role' => $userRole
        ]);
    }

    /**
     * Mengembalikan array lengkap berisi data semua instansi.
     */
    private function getAllInstansiData()
    {
        $instansiList = [
            'Badan Urusan Logistik (BULOG)',
            'Dinas Lingkungan Hidup',
            'Dinas Perhubungan',
            'Dinas Pertanian, Kelautan dan Perikanan',
            'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPMPTSP)',
            'DPRD Kota Parepare',
            'Perusahaan Es Balok',
            'Institut Ilmu Sosial dan Bisnis Andi Sapada',
            'Institut Teknologi BJ Habibie',
            'J&T Express',
            // 'LRA',
            'PAM Tirta Karajae',
            'PLN',
            'Pos Indonesia',
            'RS Fatima',
            'RSUD Andi Makkasau',
            'SAMSAT',
            'Perusahaan TV Kabel',
            'BPJS Ketenagakerjaan',
            'Dinas Perdagangan',
            'Perusahaan Es Kristal',
            'Hadji Kalla Toyota',
            'Mandiri Taspen',
            'La Tunrung Money Changer',
            'Pegadaian',
            'RS Khadijah',
            'Universitas Muhammadiyah Parepare',
            'Universitas Negeri Makassar - Parepare',
            'Perumahan',
            'Swadharma Sarana Informatika'
        ];

        $logos = [
            'Badan Urusan Logistik (BULOG)' => '/images/Logo-bulog.png',
            'Dinas Lingkungan Hidup' => '/images/Logo-dlh.png',
            'Dinas Perhubungan' => '/images/Logo-dishub.png',
            'Dinas Pertanian, Kelautan dan Perikanan' => '/images/Logo-dinas-pkp.png',
            'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPMPTSP)' => '/images/Logo-dpmptsp.png',
            'DPRD Kota Parepare' => '/images/Logo-dprd.png',
            'Perusahaan Es Balok' => '/images/Logo-es-balok.png',
            'Institut Ilmu Sosial dan Bisnis Andi Sapada' => '/images/Logo-institut-ilmu-sosal-dan-bisnis-andi-sapada.png',
            'Institut Teknologi BJ Habibie' => '/images/Logo-ith-pare.png',
            'J&T Express' => '/images/Logo-jnt.png',
            'PAM Tirta Karajae' => '/images/Logo-pam-tirta-karajae.png',
            'PLN' => '/images/Logo-PLN.png',
            'Pos Indonesia' => '/images/Logo-pos-indonesia.png',
            'RS Fatima' => '/images/Logo-RS-Fatima.png',
            'RSUD Andi Makkasau' => '/images/Logo-RSUD.png',
            'SAMSAT' => '/images/Logo-samsat.png',
            // 'LRA' => 'https://placehold.co/150x150/FFFFFF/333333?text=LRA',
            'Perusahaan TV Kabel' => 'https://placehold.co/150x150/FFFFFF/333333?text=TV',
            'BPJS Ketenagakerjaan' => '/images/Logo-bpjs-ketenagakerjaan.png',
            'Dinas Perdagangan' => '/images/Logo-dinas-perdagangan.png',
            'Perusahaan Es Kristal' => '/images/Logo-es-kristal.png',
            'Hadji Kalla Toyota' => '/images/Logo-kalla-toyota.png',
            'Mandiri Taspen' => '/images/Logo-mandiri-taspen.png',
            'La Tunrung Money Changer' => '/images/Logo-la-tunrung.png',
            'Pegadaian' => '/images/Logo-pegadaian.png',
            'RS Khadijah' => '/images/Logo-RS-Khadijah.png',
            'Universitas Muhammadiyah Parepare' => '/images/Logo-umpar.png',
            'Universitas Negeri Makassar - Parepare' => '/images/Logo-UNM.png',
            'Perumahan' => '/images/Logo-perumahan.png',
            'Swadharma Sarana Informatika' => '/images/Logo-SSI.png'
        ];

        $data = [];
        foreach ($instansiList as $nama) {
            $data[] = [
                'nama' => $nama,
                'slug' => Str::slug($nama),
                'logo' => $logos[$nama] ?? 'https://placehold.co/150x150/FFFFFF/333333?text=Logo'
            ];
        }
        return $data;
    }
}
