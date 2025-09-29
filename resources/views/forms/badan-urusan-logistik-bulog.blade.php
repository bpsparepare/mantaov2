@extends('layouts.app')

@section('title', 'Form Bulog')

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

    .table-wrapper {
        max-height: 600px;
        overflow: auto;
    }

    thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #e9ecef;
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
        transition: background-color 0.2s;
        border: none;
        cursor: pointer;
    }

    .reload-btn:hover {
        background-color: #5a6268;
    }

    .reload-btn.loading {
        background-color: #a0a6ac;
        cursor: wait;
    }

    h1 {
        font-size: 28px;
        margin-bottom: 10px;
    }

    p {
        font-size: 16px;
        color: #6c757d;
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
        background-color: #fff;
    }

    thead th {
        font-weight: 600;
    }

    tbody tr:nth-of-type(even) {
        background-color: #f8f9fa;
    }

    .info-box h4 {
        margin-top: 0;
        font-size: 18px;
        color: #343a40;
    }

    .info-box ul {
        padding-left: 20px;
        margin-bottom: 0;
        color: #6c757d;
        font-size: 14px;
    }

    .info-box li {
        margin-bottom: 10px;
    }

    /* --- CSS UNTUK RESPONSIVE --- */
    @media (max-width: 992px) {
        .main-container {
            flex-direction: column;
            /* Ubah layout menjadi vertikal (tumpuk ke bawah) */
            padding: 90px 20px 20px 20px;
            /* Sesuaikan padding untuk layar kecil */
            gap: 20px;
        }

        .form-container {
            flex: 1 1 100%;
            /* Buat form mengambil lebar penuh */
            width: 100%;
            padding: 30px;
            /* Kurangi padding di layar kecil */
        }

        .preview-container {
            position: static;
            /* Hapus efek "sticky" di layar kecil */
            width: 100%;
            padding: 30px;
            /* Kurangi padding di layar kecil */
        }

        h1 {
            font-size: 24px;
            /* Perkecil ukuran judul */
        }
    }
</style>
@endpush

@section('content')
<div class="main-container">
    <div class="form-container">
        <div class="form-header">
            <h1>Formulir Bulanan</h1>
            <p>Badan Urusan Logistik (BULOG)</p>
        </div>

        @if(session('success'))
        <div class="notification success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="notification error">{{ session('error') }}</div>
        @endif

        <form action="{{ route('data.store') }}" method="POST">
            @csrf
            <input type="hidden" name="nama_instansi" value="Badan Urusan Logistik (BULOG)">
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

            <div class="form-group"><label for="stok_beras_medium">Beras Medium (Kg):</label><input type="number" step="any" id="stok_beras_medium" name="bulog_stok_beras_medium" required></div>
            <div class="form-group"><label for="stok_beras_premium">Beras Premium (Kg):</label><input type="number" step="any" id="stok_beras_premium" name="bulog_stok_beras_premium" required></div>
            <div class="form-group"><label for="stok_gula_pasir">Gula Pasir (Kg):</label><input type="number" step="any" id="stok_gula_pasir" name="bulog_stok_gula_pasir" required></div>
            <div class="form-group"><label for="stok_minyak_goreng">Minyak Goreng (L):</label><input type="number" step="any" id="stok_minyak_goreng" name="bulog_stok_minyak_goreng" required></div>
            <div class="form-group"><label for="stok_daging_kerbau">Daging Kerbau (Kg):</label><input type="number" step="any" id="stok_daging_kerbau" name="bulog_stok_daging_kerbau" required></div>
            <div class="form-group"><label for="stok_tepung_terigu">Tepung Terigu (Kg):</label><input type="number" step="any" id="stok_tepung_terigu" name="bulog_stok_tepung_terigu" required></div>
            <div class="form-group"><label for="stok_jagung_pakan_ternak">Jagung pakan ternak (Kg):</label><input type="number" step="any" id="stok_jagung_pakan_ternak" name="bulog_stok_jagung_pakan_ternak" required></div>

            <button type="submit" class="submit-btn">Kirim Data</button>
        </form>

        <div class="info-box">
            <h4>Petunjuk Pengisian</h4>
            <ul>
                <li>Semua data stok diisi dalam satuan yang telah ditentukan (Kg atau Liter).</li>
                <li>Jika terdapat komoditas dengan stok kosong, harap isi dengan angka <strong>0</strong>.</li>
                <li>Jika mengalami kendala, silakan hubungi narahubung dari BPS Kota Parepare.</li>
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
            <div id="preview-table-container" data-url="{{ route('preview.data', $slugInstansi) }}">
                @include('partials.preview-table-bulog', ['sheetData' => $sheetData, 'previewError' => $previewError])
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
                console.error('Error fetching preview:', error);
            })
            .finally(() => {
                btn.textContent = 'Muat Ulang';
                btn.classList.remove('loading');
                btn.disabled = false;
            });
    });
</script>
@endpush