@extends('layouts.app')

@section('title', 'Form RSUD Andi Makkasau')

@section('body-class', 'page-form')

@section('content')
<div class="main-container">
    <div class="form-container">
        <div class="form-header">
            <h1>Formulir Bulanan</h1>
            <p>RSUD Andi Makkasau</p>
        </div>

        @if(session('success'))
        <div class="notification success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
        <div class="notification error">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('data.store') }}" method="POST" id="data-form">
            @csrf
            <input type="hidden" name="nama_instansi" value="RSUD Andi Makkasau">
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
                <label for="rawat_inap">Jumlah Pasien Rawat Inap:</label>
                <input type="number" id="rawat_inap" name="rsud_rawat_inap" placeholder="Contoh: 1428" required>
            </div>

            <div class="form-group">
                <label for="rawat_jalan">Jumlah Pasien Rawat Jalan:</label>
                <input type="number" id="rawat_jalan" name="rsud_rawat_jalan" placeholder="Contoh: 4364" required>
            </div>

            <button type="submit" class="submit-btn">
                <span class="btn-text">Kirim Data</span>
                <div class="loader"></div>
            </button>
        </form>

        <div class="info-box">
            <h4>Petunjuk Pengisian</h4>
            <ul>
                <li>Masukkan jumlah total pasien rawat inap dan rawat jalan selama bulan pelaporan.</li>
                <li>Jika tidak ada data, harap isi dengan angka <strong>0</strong>.</li>
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
                @include('partials.preview-table-rsud', ['sheetData' => $sheetData, 'previewError' => $previewError])
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
            .then(response => {
                if (response.status === 401) {
                    return response.json().then(err => {
                        throw new Error(err.error || 'Sesi Anda telah berakhir. Silakan muat ulang halaman untuk login kembali.');
                    });
                }
                if (!response.ok) {
                    throw new Error('Gagal memuat data. Periksa koneksi jaringan.');
                }
                return response.text();
            })
            .then(html => {
                container.innerHTML = html;
            })
            .catch(error => {
                container.innerHTML = `<div class="notification error">${error.message}</div>`;
                console.error('Error fetching preview:', error);
            })
            .finally(() => {
                btn.textContent = 'Muat Ulang';
                btn.classList.remove('loading');
                btn.disabled = false;
            });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const dataForm = document.getElementById('data-form');
        if (dataForm) {
            dataForm.addEventListener('submit', function(e) {
                const submitBtn = dataForm.querySelector('.submit-btn');
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
            });
        }
    });
</script>
@endpush