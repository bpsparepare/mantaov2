@extends('layouts.app')

@section('title', 'Form DPRD Kota Parepare')

@section('body-class', 'page-form')

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

        <form action="{{ route('data.store') }}" method="POST" id="data-form">
            @csrf
            <input type="hidden" name="nama_instansi" value="DPRD Kota Parepare">
            <div class="form-group">
                <label for="tahun">Pilih Tahun Pelaporan:</label>
                <select id="tahun" name="tahun" required>
                    <option value="" disabled selected>-- Pilih Tahun --</option>
                    @if(isset($sheetData['years']))
                    @foreach($sheetData['years'] as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                    @endif
                    <option value="new_year" style="font-weight: bold;">+ Tambah Tahun Baru</option>
                </select>

                <div class="new-year-input" id="new-year-container" style="display:none; margin-top: 15px;">
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

            <button type="submit" class="submit-btn">
                <span class="btn-text">Kirim Data</span>
                <div class="loader"></div>
            </button>
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
            <div id="preview-table-container" data-url="{{ route('preview.data', $slugInstansi) }}">
                @include('partials.preview-table-dprd', ['sheetData' => $sheetData, 'previewError' => $previewError])
            </div>
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