@extends('layouts.app')

@section('title', 'Form Diskominfo')

@section('body-class', 'page-form')

@section('content')
<div class="main-container">
    <div class="form-container">
        <div class="form-header">
            <h1>Formulir Bulanan</h1>
            <p>Dinas Komunikasi dan Informatika</p>
        </div>

        @if(session('success'))
        <div class="notification success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="notification error">{{ session('error') }}</div>
        @endif

        <form action="{{ route('data.store') }}" method="POST" id="data-form">
            @csrf
            <input type="hidden" name="nama_instansi" value="Dinas Komunikasi dan Informatika">
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

            <fieldset>
                <legend>Kantor Berita</legend>
                <div class="form-group">
                    <label for="jumlah_kantor_berita">Jumlah Kantor Berita (Unit):</label>
                    <input type="number" step="1" id="jumlah_kantor_berita" name="diskominfo_jumlah_kantor_berita" required placeholder="Jumlah unit" value="0">
                </div>
                <div class="form-group">
                    <label for="pendapatan_kantor_berita">Pendapatan Kantor Berita (Rp):</label>
                    <input type="number" step="any" id="pendapatan_kantor_berita" name="diskominfo_pendapatan_kantor_berita" required placeholder="Total pendapatan" value="0">
                </div>
            </fieldset>

            <fieldset>
                <legend>Radio Swasta</legend>
                <div class="form-group">
                    <label for="jumlah_radio_swasta">Jumlah Radio Swasta (Unit):</label>
                    <input type="number" step="1" id="jumlah_radio_swasta" name="diskominfo_jumlah_radio_swasta" required placeholder="Jumlah unit" value="0">
                </div>
                <div class="form-group">
                    <label for="pendapatan_radio_swasta">Pendapatan Radio Swasta (Rp):</label>
                    <input type="number" step="any" id="pendapatan_radio_swasta" name="diskominfo_pendapatan_radio_swasta" required placeholder="Total pendapatan" value="0">
                </div>
            </fieldset>

            <button type="submit" class="submit-btn">
                <span class="btn-text">Kirim Data</span>
                <div class="loader"></div>
            </button>
        </form>

        <div class="info-box">
            <h4>Petunjuk Pengisian</h4>
            <ul>
                <li>Isi jumlah unit dan total pendapatan untuk masing-masing kategori.</li>
                <li>Isi dengan angka <strong>0</strong> jika tidak ada data.</li>
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
            <div id="preview-table-container" data-url="{{ route('preview.data', Str::slug('Dinas Komunikasi dan Informatika')) }}">
                @include('partials.preview-table-diskominfo', ['sheetData' => $sheetData, 'previewError' => $previewError])
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