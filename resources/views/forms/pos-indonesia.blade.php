@extends('layouts.app')

@section('title', 'Form Pos Indonesia')

@section('body-class', 'page-form')

@section('content')
<div class="main-container">
    <div class="form-container">
        <div class="form-header">
            <h1>Formulir Bulanan</h1>
            <p>Pos Indonesia</p>
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
            <input type="hidden" name="nama_instansi" value="Pos Indonesia">
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
                <legend>Data Barang</legend>
                <div class="form-group">
                    <label for="barang_dikirim">Jumlah Barang Dikirim (Kg):</label>
                    <input type="number" step="any" id="barang_dikirim" name="pos_barang_dikirim" required value="0">
                </div>
                <div class="form-group">
                    <label for="barang_diterima">Jumlah Barang Diterima (Kg):</label>
                    <input type="number" step="any" id="barang_diterima" name="pos_barang_diterima" required value="0">
                </div>
            </fieldset>

            <fieldset>
                <legend>Data Surat</legend>
                <div class="form-group">
                    <label for="surat_dikirim">Jumlah Surat Dikirim:</label>
                    <input type="number" step="1" id="surat_dikirim" name="pos_surat_dikirim" required value="0">
                </div>
                <div class="form-group">
                    <label for="surat_diterima">Jumlah Surat Diterima:</label>
                    <input type="number" step="1" id="surat_diterima" name="pos_surat_diterima" required value="0">
                </div>
            </fieldset>

            <fieldset>
                <legend>Data Keuangan</legend>
                <div class="form-group">
                    <label for="wesel_pos">Jumlah Wesel Pos (Rp):</label>
                    <input type="number" step="any" id="wesel_pos" name="pos_wesel_pos" required value="0">
                </div>
            </fieldset>

            <button type="submit" class="submit-btn">
                <span class="btn-text">Kirim Data</span>
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
            <div id="preview-table-container" data-url="{{ route('preview.data', Str::slug('Pos Indonesia')) }}">
                @include('partials.preview-table-pos', ['sheetData' => $sheetData, 'previewError' => $previewError])
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