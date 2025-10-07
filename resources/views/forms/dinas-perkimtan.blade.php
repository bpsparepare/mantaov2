@extends('layouts.app')

@section('body-class', 'page-form')

@section('content')
<div class="main-container">
    <div class="form-container">
        <div class="form-header">
            <h1>Formulir Bulanan</h1>
            <p>Dinas Perkimtan</p>
        </div>

        @if(session('success'))
        <div class="notification success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="notification error">{{ session('error') }}</div>
        @endif

        {{-- FORM UNTUK DATA NUMERIK --}}
        <form action="{{ route('data.store') }}" method="POST" id="data-form">
            @csrf
            <input type="hidden" name="nama_instansi" value="Dinas Perkimtan">
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
                <label for="jumlah_proyek">Jumlah Proyek:</label>
                <input type="number" step="1" id="jumlah_proyek" name="perkimtan_jumlah_proyek" required placeholder="Jumlah proyek">
            </div>
            <div class="form-group">
                <label for="nilai_proyek">Nilai Proyek (Rp):</label>
                <input type="number" step="any" id="nilai_proyek" name="perkimtan_nilai_proyek" required placeholder="Nilai proyek">
            </div>

            <button type="submit" class="submit-btn">
                <span class="btn-text">Kirim Data</span>
                <div class="loader"></div>
            </button>
        </form>

        <hr style="margin: 40px 0; border: 1px solid #e2e8f0;">

        {{-- FORM BARU KHUSUS UNTUK UPLOAD FILE --}}
        <form action="{{ route('file.upload') }}" method="POST" enctype="multipart/form-data" id="file-upload-form">
            @csrf
            <input type="hidden" name="nama_instansi" value="Dinas Perkimtan">
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
            <div id="preview-table-container" data-url="{{ route('preview.data', Str::slug('Dinas Perkimtan')) }}">
                @include('partials.preview-table-perkimtan', ['sheetData' => $sheetData, 'previewError' => $previewError])
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