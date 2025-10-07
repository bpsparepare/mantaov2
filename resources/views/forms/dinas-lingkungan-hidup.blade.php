@extends('layouts.app')

@section('title', 'Form Dinas Lingkungan Hidup')

@section('body-class', 'page-form')

@section('content')
<div class="main-container">
    <div class="form-container">
        <div class="form-header">
            <h1>Formulir Bulanan</h1>
            <p>Dinas Lingkungan Hidup</p>
        </div>

        @if(session('success'))
        <div class="notification success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="notification error">{{ session('error') }}</div>
        @endif

        <form action="{{ route('data.store') }}" method="POST" id="data-form">
            @csrf
            <input type="hidden" name="nama_instansi" value="Dinas Lingkungan Hidup">
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
                <label for="volume_timbulan">Volume timbulan sampah (ton):</label>
                <input type="number" step="any" id="volume_timbulan" name="dlh_volume_timbulan" required placeholder="Contoh: 2558.69">
            </div>
            <div class="form-group">
                <label for="volume_ditangani">Volume sampah yang ditangani (ton):</label>
                <input type="number" step="any" id="volume_ditangani" name="dlh_volume_ditangani" required placeholder="Contoh: 1883.27">
            </div>
            <div class="form-group">
                <label for="volume_dikelola">Volume sampah yang dapat dikelola (ton):</label>
                <input type="number" step="any" id="volume_dikelola" name="dlh_volume_dikelola" required placeholder="Contoh: 2269.03">
            </div>

            <button type="submit" class="submit-btn">
                <span class="btn-text">Kirim Data</span>
                <div class="loader"></div>
            </button>
        </form>

        <div class="info-box">
            <h4>Petunjuk Pengisian</h4>
            <ul>
                <li>Semua data volume sampah diisi dalam satuan <strong>ton</strong>.</li>
                <li>Pastikan data yang diinput sesuai dengan data bulanan yang sebenarnya.</li>
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
                @include('partials.preview-table-dlh', ['sheetData' => $sheetData, 'previewError' => $previewError])
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