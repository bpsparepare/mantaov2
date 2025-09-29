@extends('layouts.app')

@section('title', 'Form DPRD Kota Parepare')

@section('body-class', 'page-form')

@push('styles')
<style>
    /* --- PERUBAHAN UTAMA DIMULAI DI SINI --- */
    .main-container {
        display: flex;
        gap: 30px;
        max-width: 1600px;
        margin: auto;
        align-items: flex-start;
        /* SOLUSI: Memberi ruang untuk header */
        padding-top: 80px;
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
        position: sticky;
        /* Disesuaikan agar posisi sticky benar setelah ada header */
        top: 80px;
        background-color: #ffffff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        min-width: 0;
    }

    /* --- AKHIR PERUBAHAN UTAMA --- */


    .table-wrapper {
        max-height: 600px;
        overflow: auto;
    }

    thead th {
        position: sticky;
        top: 0;
        background-color: #e9ecef;
        z-index: 10;
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

    /* --- PENYESUAIAN TEMA AGAR KONSISTEN --- */
    .submit-btn {
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 25px;
        background-color: #1a4f6d;
        /* Warna tema MANTAO */
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
        /* Warna tema MANTAO */
        border-radius: 8px;
    }

    /* --- AKHIR PENYESUAIAN TEMA --- */


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

    .new-year-input {
        display: none;
        margin-top: 15px;
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

    /* --- CSS UNTUK RESPONSIVE --- */
    @media (max-width: 992px) {
        .main-container {
            flex-direction: column;
            padding: 90px 20px 20px 20px;
            gap: 20px;
        }

        .form-container {
            flex: 1 1 100%;
            width: 100%;
            padding: 30px;
        }

        .preview-container {
            position: static;
            width: 100%;
            padding: 30px;
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
            <h1>Formulir Tahunan</h1>
            <p>DPRD Kota Parepare</p>
        </div>

        @if(session('success'))
        <div class="notification success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="notification error">{{ session('error') }}</div>
        @endif

        <form action="{{ route('data.store') }}" method="POST">
            @csrf
            <input type="hidden" name="nama_instansi" value="DPRD Kota Parepare">
            <div class="form-group">
                <label for="tahun">Pilih Tahun Pelaporan:</label>
                <select id="tahun" name="tahun" required>
                    <option value="">-- Pilih Tahun --</option>
                    @if(isset($sheetData['years']))
                    @foreach($sheetData['years'] as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                    @endif
                    <option value="new_year" style="font-weight: bold;">+ Tambah Tahun Baru</option>
                </select>

                <div class="new-year-input" id="new-year-container">
                    <label for="tahun_baru">Masukkan Tahun Baru:</label>
                    <input type="number" name="tahun_baru" id="tahun_baru" placeholder="Contoh: {{ date('Y') + 1 }}">
                </div>
            </div>

            <div class="form-group">
                <label>Jumlah Anggota Laki-laki:</label>
                <input type="number" name="dprd_jumlah_laki" required value="0" min="0">
            </div>
            <div class="form-group">
                <label>Jumlah Anggota Perempuan:</label>
                <input type="number" name="dprd_jumlah_perempuan" required value="0" min="0">
            </div>

            <button type="submit" class="submit-btn">Kirim Data</button>
        </form>

        <div class="info-box">
            <h4>Petunjuk Pengisian</h4>
            <ul>
                <li>Pilih tahun yang sudah ada untuk memperbarui data, atau pilih "+ Tambah Tahun Baru" untuk menambahkan data tahun berikutnya.</li>
                <li>Masukkan jumlah total anggota DPRD berdasarkan jenis kelamin.</li>
            </ul>
        </div>
    </div>

    <div class="preview-container">
        <div class="preview-header">
            <div class="preview-header-content">
                <h1>Pratinjau Data</h1>
                <button type="button" class="reload-btn" id="reload-preview-btn">Muat Ulang</button>
            </div>
            <p>Data terkini dari Google Sheet.</p>
        </div>
        <div class="table-wrapper">
            @include('partials.preview-table-dprd', ['sheetData' => $sheetData, 'previewError' => $previewError])
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('tahun').addEventListener('change', function() {
        const newYearContainer = document.getElementById('new-year-container');
        if (this.value === 'new_year') {
            newYearContainer.style.display = 'block';
            document.getElementById('tahun_baru').required = true;
        } else {
            newYearContainer.style.display = 'none';
            document.getElementById('tahun_baru').required = false;
        }
    });

    // Skrip AJAX untuk Muat Ulang Pratinjau
    document.getElementById('reload-preview-btn').addEventListener('click', function() {
        const btn = this;
        const container = document.querySelector('.preview-container .table-wrapper');
        const url = "{{ route('preview.data', $slugInstansi) }}";
        btn.textContent = 'Memuat...';
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
                btn.disabled = false;
            });
    });
</script>
@endpush