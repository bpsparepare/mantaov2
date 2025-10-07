@extends('layouts.app')

@section('body-class', 'page-form')

@push('styles')
<style>
    /* CSS Konsisten untuk semua halaman form */
    .main-container {
        display: flex;
        gap: 30px;
        max-width: 1600px;
        margin: auto;
        align-items: flex-start;
        padding-top: 110px;
        padding-bottom: 40px;
    }

    .form-container {
        flex: 0 0 450px;
        background-color: #ffffff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .preview-container {
        flex: 1 1 auto;
        top: 110px;
        background-color: #ffffff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        min-width: 0;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    thead th {
        position: sticky;
        top: 0;
        background-color: #e9ecef;
        z-index: 10;
        white-space: nowrap;
    }

    .form-header,
    .preview-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .preview-header-content {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
    }

    .reload-btn {
        font-size: 12px;
        padding: 5px 10px;
        background-color: #6c757d;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        border: none;
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        box-sizing: border-box;
    }

    .submit-btn {
        position: relative;
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 25px;
        background-color: #1a4f6d;
        color: white;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .submit-btn:hover {
        background-color: #0d2c3f;
        transform: translateY(-2px);
    }

    /* --- CSS BARU UNTUK EFEK LOADING TOMBOL --- */
    .submit-btn .btn-text {
        transition: opacity 0.2s ease-in-out;
    }

    .submit-btn .loader {
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-top-color: #fff;
        border-radius: 50%;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -10px;
        margin-left: -10px;
        display: none;
        animation: spin 1s linear infinite;
    }

    .submit-btn.loading .btn-text {
        opacity: 0;
    }

    .submit-btn.loading .loader {
        display: block;
    }

    .submit-btn.loading {
        cursor: wait;
        background-color: #0d2c3f;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* --- AKHIR CSS BARU --- */

    .info-box {
        margin-top: 40px;
        padding: 20px;
        background-color: #f8f9fa;
        border-left: 4px solid #1a4f6d;
        border-radius: 8px;
    }

    .notification {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        text-align: center;
    }

    .notification.success {
        background-color: #d4edda;
        color: #155724;
    }

    .notification.error {
        background-color: #f8d7da;
        color: #721c24;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 10px 12px;
        border: 1px solid #dee2e6;
        text-align: left;
        font-size: 14px;
    }

    thead th {
        font-weight: 600;
    }

    fieldset {
        border: 1px solid #e2e8f0;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    legend {
        font-weight: 600;
        font-size: 1.1em;
        padding: 0 10px;
        color: #1a4f6d;
    }

    @media (max-width: 992px) {
        .main-container {
            flex-direction: column;
            padding: 90px 20px 20px 20px;
            gap: 20px;
        }

        .form-container,
        .preview-container {
            flex: 1 1 100%;
            width: 100%;
            padding: 30px;
            position: static;
        }

        h1 {
            font-size: 24px;
        }
    }
</style>
@endpush

@section('content')
<div class="main-container">
    <div class="form-container">
        <div class="form-header">
            <h1>Formulir Bulanan</h1>
            <p>KPPN</p>
        </div>

        @if(session('success'))
        <div class="notification success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
        <div class="notification error" style="text-align: left;">
            <strong>Terjadi kesalahan:</strong>
            <ul style="margin-top: 10px; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('data.store') }}" method="POST" id="data-form">
            @csrf
            <input type="hidden" name="nama_instansi" value="KPPN">
            <div class="form-group">
                <label for="bulan">Pilih Bulan Pelaporan:</label>
                <select id="bulan" name="bulan" required>
                    <option value="" disabled selected>-- Pilih Bulan --</option>
                    <option value="Januari">Januari</option>
                    <option value="Februari">Februari</option>
                    <option value="Maret">Maret</option>
                    <option value="April">April</option>
                    <option value="Mei">Mei</option>
                    <option value="Juni">Juni</option>
                    <option value="Juli">Juli</option>
                    <option value="Agustus">Agustus</option>
                    <option value="September">September</option>
                    <option value="Oktober">Oktober</option>
                    <option value="November">November</option>
                    <option value="Desember">Desember</option>
                </select>
            </div>
            <div class="form-group">
                <label for="belanja_pegawai">Realisasi Belanja Pegawai (Rp):</label>
                <input type="number" step="any" id="belanja_pegawai" name="kppn_belanja_pegawai" required value="0">
            </div>
            <fieldset>
                <legend>Realisasi Belanja Modal</legend>
                <div class="form-group">
                    <label for="modal_kontraktual_fisik">Kontraktual Fisik (Rp):</label>
                    <input type="number" step="any" id="modal_kontraktual_fisik" name="kppn_modal_kontraktual_fisik" required value="0">
                </div>
                <div class="form-group">
                    <label for="modal_kontraktual_nonfisik">Kontraktual Non Fisik (Rp):</label>
                    <input type="number" step="any" id="modal_kontraktual_nonfisik" name="kppn_modal_kontraktual_nonfisik" required value="0">
                </div>
                <div class="form-group">
                    <label for="modal_nonkontraktual">Non Kontraktual (Rp):</label>
                    <input type="number" step="any" id="modal_nonkontraktual" name="kppn_modal_nonkontraktual" required value="0">
                </div>
            </fieldset>
            <button type="submit" class="submit-btn">
                <span class="btn-text">Kirim Data</span>
                <div class="loader"></div>
            </button>
        </form>

        <hr style="margin: 40px 0; border: 1px solid #e2e8f0;">

        <form action="{{ route('file.upload') }}" method="POST" enctype="multipart/form-data" id="file-upload-form">
            @csrf
            <input type="hidden" name="nama_instansi" value="KPPN">
            <fieldset>
                <legend>Upload File Rincian</legend>
                <div class="form-group">
                    <label for="rincian_file">File Rincian (PDF/Excel, max: 5MB)</label>
                    <input type="file" id="rincian_file" name="rincian_file" required>
                </div>
            </fieldset>
            <button type="submit" class="submit-btn">
                <span class="btn-text">Unggah File</span>
                <div class="loader"></div>
            </button>
        </form>
    </div>

    <div class="preview-container">
        <div class="preview-header">
            <div class="preview-header-content">
                <h1>Pratinjau Data {{ date('Y') }}</h1>
                <button type="button" class="reload-btn" id="reload-preview-btn">Muat Ulang</button>
            </div>
            <p>Data terkini dari Google Sheet.</p>
        </div>

        <div class="table-wrapper">
            <div id="preview-table-container" data-url="{{ route('preview.data', Str::slug('KPPN')) }}">
                @include('partials.preview-table-kppn', ['sheetData' => $sheetData, 'previewError' => $previewError])
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('reload-preview-btn').addEventListener('click', function() {
        /* ... */
    });

    // SCRIPT BARU UNTUK EFEK LOADING PADA KEDUA TOMBOL
    document.addEventListener('DOMContentLoaded', function() {
        const dataForm = document.getElementById('data-form');
        const fileUploadForm = document.getElementById('file-upload-form');

        if (dataForm) {
            dataForm.addEventListener('submit', function(e) {
                const submitBtn = dataForm.querySelector('.submit-btn');
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
            });
        }

        if (fileUploadForm) {
            fileUploadForm.addEventListener('submit', function(e) {
                const submitBtn = fileUploadForm.querySelector('.submit-btn');
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
            });
        }
    });
</script>
@endpush