@extends('layouts.app')

@section('title', 'Form UPTD Pasar')
@section('body-class', 'page-form')

@section('content')
<div class="main-container">
    <div class="form-container">
        <div class="form-header">
            <h1>Formulir Bulanan</h1>
            <p>UPTD Pasar</p>
        </div>

        @if(session('success'))
        <div class="notification success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="notification error">{{ session('error') }}</div>
        @endif

        <form action="{{ route('data.store') }}" method="POST" id="data-form">
            @csrf
            <input type="hidden" name="nama_instansi" value="UPTD Pasar">
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

            @php
            $pasars = ['Lakessi', 'Senggol', 'Labukkang', 'Sumpang', 'Wekke_e'];
            @endphp

            @foreach($pasars as $pasar)
            <fieldset>
                <legend>Pasar {{ str_replace('_', "'", $pasar) }}</legend>
                <div class="form-group">
                    <label for="pasar_{{ strtolower($pasar) }}_pedagang">Jumlah Pedagang (unit):</label>
                    <input type="number" step="1" id="pasar_{{ strtolower($pasar) }}_pedagang" name="pasar_{{ strtolower($pasar) }}_pedagang" required value="0">
                </div>
                <div class="form-group">
                    <label for="pasar_{{ strtolower($pasar) }}_pendapatan">Pendapatan (Rp):</label>
                    <input type="number" step="any" id="pasar_{{ strtolower($pasar) }}_pendapatan" name="pasar_{{ strtolower($pasar) }}_pendapatan" required value="0">
                </div>
            </fieldset>
            @endforeach

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
            <div id="preview-table-container" data-url="{{ route('preview.data', Str::slug('UPTD Pasar')) }}">
                @include('partials.preview-table-uptd-pasar', ['sheetData' => $sheetData, 'previewError' => $previewError])
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