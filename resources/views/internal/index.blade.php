@extends('layouts.app')

{{-- Menambahkan kelas body untuk header solid --}}
@section('body-class', 'page-form')

@push('styles')
<style>
    /* Latar belakang halaman yang konsisten */
    body {
        background-color: #f4f6f9;
    }

    .page-container {
        max-width: 1600px;
        margin: auto;
        padding: 110px 40px 40px 40px;
    }

    .page-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .page-header h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 36px;
        color: #0d2c3f;
        margin: 0;
    }

    .page-header p {
        font-size: 18px;
        color: #6c757d;
        margin-top: 10px;
    }

    /* --- CSS BARU UNTUK NOTIFIKASI --- */
    .alert {
        padding: 15px;
        margin-bottom: 30px;
        /* Jarak ke konten di bawahnya */
        border-radius: 10px;
        text-align: center;
        font-weight: 600;
        font-size: 1rem;
    }

    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
    }

    .alert-error {
        background-color: #fee2e2;
        color: #991b1b;
    }

    /* --- AKHIR CSS BARU --- */


    .internal-container {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .action-card {
        background-color: #ffffff;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .action-card-content h3 {
        font-family: 'Poppins', sans-serif;
        color: #1a4f6d;
        margin-top: 0;
        margin-bottom: 5px;
    }

    .action-card-content p {
        margin: 0;
        color: #6c757d;
    }

    .action-card .btn-create {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 25px;
        border: none;
        border-radius: 25px;
        background-color: #1a4f6d;
        color: white;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .action-card .btn-create:hover {
        background-color: #0d2c3f;
        transform: translateY(-2px);
    }

    .links-card {
        background-color: #ffffff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .links-card h2 {
        font-family: 'Poppins', sans-serif;
        text-align: center;
        margin-top: 0;
        margin-bottom: 30px;
        color: #1a4f6d;
    }

    .links-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }

    .link-item {
        background-color: #fff;
        border: 1px solid #eef2f7;
        padding: 20px;
        border-radius: 8px;
        text-decoration: none;
        color: #343a40;
        font-weight: 600;
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .link-item i {
        font-size: 24px;
        color: #1a4f6d;
    }

    .link-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(26, 79, 109, 0.1);
        border-color: #1a4f6d;
    }

    /* Aturan untuk Responsivitas */
    @media (max-width: 768px) {
        .page-container {
            padding: 90px 20px 20px 20px;
        }

        .action-card {
            flex-direction: column;
            text-align: center;
            gap: 20px;
            padding: 30px;
        }

        .links-card {
            padding: 30px;
        }

        .page-header h1 {
            font-size: 28px;
        }
    }
</style>
@endpush

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Halaman Internal</h1>
        <p>Manajemen akun instansi dan akses cepat ke folder data.</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="internal-container">
        <div class="action-card">
            <div class="action-card-content">
                <h3>Manajemen Akun</h3>
                <p>Buat akun baru untuk instansi yang terdaftar.</p>
            </div>
            <a href="{{ route('admin.user.create') }}" class="btn-create">
                <i class="fas fa-plus-circle"></i>
                <span>Buat Akun Baru</span>
            </a>
        </div>

        <div class="links-card">
            <h2>Folder Google Drive Instansi</h2>
            <div class="links-grid">
                @foreach($all_instansi as $instansi)
                <a href="{{ $instansi['link'] }}" target="_blank" class="link-item">
                    <i class="fas fa-folder"></i>
                    <span>{{ $instansi['nama'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection