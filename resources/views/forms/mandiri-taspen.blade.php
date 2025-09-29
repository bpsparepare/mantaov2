@extends('layouts.app')

@section('title', 'Form Mandiri Taspen')

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
    }

    .submit-btn:hover {
        background-color: #0d2c3f;
        transform: translateY(-2px);
    }

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
            <p>Mandiri Taspen</p>
        </div>

        @if(session('success'))
        <div class="notification success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="notification error">{{ session('error') }}</div>
        @endif

        <form action="{{ route('data.store') }}" method="POST">
            @csrf
            <input type="hidden" name="nama_instansi" value="Mandiri Taspen">
            <div class="form-group">
                <label for="bulan">Pilih Bulan Pelaporan:</label>
                <select id="bulan" name="bulan" required>
                    <option value="">-- Pilih Bulan --</option>
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
                <label for="jumlah_klaim">Jumlah Klaim Pensiun (Rp):</label>
                <input type="number" step="any" id="jumlah_klaim" name="taspen_jumlah_klaim" required placeholder="Contoh: 14761572400">
            </div>

            <button type="submit" class="submit-btn">Kirim Data</button>
        </form>

        <div class="info-box">
            <h4>Petunjuk Pengisian</h4>
            <ul>
                <li>Masukkan jumlah total klaim dalam Rupiah, tanpa titik, koma, atau simbol "Rp".</li>
                <li>Isi dengan angka <strong>0</strong> jika tidak ada klaim pada bulan tersebut.</li>
            </ul>
        </div>
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
            <div id="preview-table-container" data-url="{{ route('preview.data', Str::slug('Mandiri Taspen')) }}">
                @include('partials.preview-table-mandiri-taspen', ['sheetData' => $sheetData, 'previewError' => $previewError])
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('reload-preview-btn').addEventListener('click', function() {
        const btn = this;
        const container = document.getElementById('preview-table-container');
        const url = container.getAttribute('data-url');
        btn.textContent = 'Memuat...';
        btn.classList.add('loading');
        btn.disabled = true;
        fetch(url)
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
            })
            .catch(error => {
                container.innerHTML = '<div class="notification error">Gagal memuat data pratinjau.</div>';
            })
            .finally(() => {
                btn.textContent = 'Muat Ulang';
                btn.classList.remove('loading');
                btn.disabled = false;
            });
    });
</script>
@endpush