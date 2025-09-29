@extends('layouts.app')

@section('body-class', 'page-dashboard')

@push('styles')
<style>
    main {
        padding: 0 !important;
    }

    /* Hero Section Styles */
    .hero-section {
        height: 100vh;
        background-image: linear-gradient(rgba(13, 44, 63, 0.7), rgba(13, 44, 63, 0.7)), url('/images/7372_header_1740324574_cro.jpg');
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: white;
        padding: 0 20px;
    }

    .hero-section .badge {
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .hero-section h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 48px;
        font-weight: 700;
        margin: 0;
        max-width: 800px;
    }

    .hero-section p {
        font-size: 18px;
        max-width: 600px;
        margin: 20px 0 40px 0;
        color: #e0e0e0;
    }

    .hero-actions {
        display: flex;
        gap: 20px;
    }

    .btn {
        display: inline-block;
        padding: 12px 30px;
        font-weight: 700;
        font-size: 16px;
        text-decoration: none;
        border-radius: 25px;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .btn-primary {
        background-color: #fff;
        color: #0d2c3f;
    }

    .btn-primary:hover {
        background-color: transparent;
        border-color: #fff;
        color: #fff;
        transform: translateY(-3px);
    }

    .btn-secondary {
        background-color: transparent;
        border: 2px solid #fff;
        color: #fff;
    }

    .btn-secondary:hover {
        background-color: #fff;
        color: #0d2c3f;
        transform: translateY(-3px);
    }

    /* Content Section Styles */
    .content-section {
        padding: 80px 40px;
        background-color: #f4f6f9;
    }

    .section-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .section-header h2 {
        font-family: 'Poppins', sans-serif;
        font-size: 32px;
        font-weight: 700;
        color: #0d2c3f;
    }

    .section-header p {
        color: #6c757d;
        font-size: 18px;
    }

    .instansi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 30px;
        max-width: 1600px;
        margin: 0 auto;
    }

    .instansi-card {
        background-color: white;
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        text-decoration: none;
        color: #343a40;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s, box-shadow 0.3s;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        /* DIUBAH: Konten rata atas */
    }

    .instansi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    }

    .instansi-card.active {
        border: 2px solid #1a4f6d;
    }

    .instansi-card.active:hover {
        box-shadow: 0 10px 20px rgba(26, 79, 109, 0.15);
    }

    .instansi-card.inactive {
        filter: grayscale(100%);
        opacity: 0.6;
        cursor: not-allowed;
        background-color: #f8f9fa;
    }

    .instansi-card img {
        height: 80px;
        width: 100%;
        max-width: 160px;
        object-fit: contain;
        margin-bottom: 15px;
    }

    .instansi-card h3 {
        font-family: 'Poppins', sans-serif;
        font-size: 18px;
        font-weight: 600;
        margin: 0;
        margin-top: 0;
        /* DIUBAH: Hapus margin-top auto agar tidak terdorong ke bawah */
    }
</style>
@endpush

@section('content')
<div class="hero-section">
    <div class="badge">Selamat Datang, {{ $logged_in_instansi ?? 'Admin' }}</div>
    <h1>Portal Pengumpulan Data Pendukung PDRB</h1>
    <p>Platform terpusat untuk pengumpulan dan manajemen data dari Organisasi Perangkat Daerah (OPD) dan instansi terkait di Kota Parepare.</p>
    <div class="hero-actions">
        <a href="#instansi-list" class="btn btn-primary">Mulai Mengisi Data</a>
        <a href="https://drive.google.com/file/d/1-aFXsdvdvxhW8fB6NOdNFlfxpIwt2YQo/view" class="btn btn-secondary" target="_blank">Baca Panduan</a>
    </div>
</div>

<div class="content-section" id="instansi-list">
    <div class="section-header">
        <h2>Pilih Instansi Anda</h2>
        <p>Silakan pilih instansi Anda untuk memulai proses pengisian data.</p>
    </div>

    <div class="instansi-grid">
        @foreach($all_instansi as $instansi)

        {{-- Logika BARU: Aktif jika role adalah admin ATAU jika instansi cocok --}}
        @if((isset($user_role) && $user_role === 'admin') || $instansi['nama'] === $logged_in_instansi)

        {{-- Kartu Aktif yang Bisa Diklik --}}
        <a href="{{ route('form.show', $instansi['slug']) }}" class="instansi-card active">
            <img src="{{ $instansi['logo'] }}" alt="Logo {{ $instansi['nama'] }}" onerror="this.src='https://placehold.co/150x150/0d2c3f/FFFFFF?text=Logo';">
            <h3>{{ $instansi['nama'] }}</h3>
        </a>

        @else

        {{-- Kartu Nonaktif --}}
        <div class="instansi-card inactive">
            <img src="{{ $instansi['logo'] }}" alt="Logo {{ $instansi['nama'] }}" onerror="this.src='https://placehold.co/150x150/0d2c3f/FFFFFF?text=Logo';">
            <h3>{{ $instansi['nama'] }}</h3>
        </div>

        @endif
        @endforeach
    </div>
</div>
@endsection