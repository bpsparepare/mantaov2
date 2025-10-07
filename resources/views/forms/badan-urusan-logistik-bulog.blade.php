@extends('layouts.app')

@section('title', 'Form Bulog')

@section('body-class', 'page-form')

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

        <form action="{{ route('data.store') }}" method="POST" id="data-form"> {{-- Tambahkan ID --}}
            @csrf
            <input type="hidden" name="nama_instansi" value="Badan Urusan Logistik (BULOG)">
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

            <div class="form-group"><label for="stok_beras_medium">Beras Medium (Kg):</label><input type="number" step="any" id="stok_beras_medium" name="bulog_stok_beras_medium" required></div>
            <div class="form-group"><label for="stok_beras_premium">Beras Premium (Kg):</label><input type="number" step="any" id="stok_beras_premium" name="bulog_stok_beras_premium" required></div>
            <div class="form-group"><label for="stok_gula_pasir">Gula Pasir (Kg):</label><input type="number" step="any" id="stok_gula_pasir" name="bulog_stok_gula_pasir" required></div>
            <div class="form-group"><label for="stok_minyak_goreng">Minyak Goreng (L):</label><input type="number" step="any" id="stok_minyak_goreng" name="bulog_stok_minyak_goreng" required></div>
            <div class="form-group"><label for="stok_daging_kerbau">Daging Kerbau (Kg):</label><input type="number" step="any" id="stok_daging_kerbau" name="bulog_stok_daging_kerbau" required></div>
            <div class="form-group"><label for="stok_tepung_terigu">Tepung Terigu (Kg):</label><input type="number" step="any" id="stok_tepung_terigu" name="bulog_stok_tepung_terigu" required></div>
            <div class="form-group"><label for="stok_jagung_pakan_ternak">Jagung pakan ternak (Kg):</label><input type="number" step="any" id="stok_jagung_pakan_ternak" name="bulog_stok_jagung_pakan_ternak" required></div>

            <button type="submit" class="submit-btn">
                <span class="btn-text">Kirim Data</span>
                <div class="loader"></div>
            </button>
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