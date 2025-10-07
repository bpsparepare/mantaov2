@extends('layouts.app')

@section('title', 'Form SAMSAT')

@section('body-class', 'page-form')

@section('content')
<div class="main-container">
    <div class="form-container">
        <div class="form-header">
            <h1>Formulir Bulanan</h1>
            <p>SAMSAT</p>
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
            <input type="hidden" name="nama_instansi" value="SAMSAT">
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
                <legend>Jumlah Unit Kendaraan yang Terdaftar dan Terbayar</legend>
                <div class="fieldset-grid">
                    <div class="form-group"><label>Sedan:</label><input type="number" name="samsat_terdaftar_sedan" value="0" min="0" required></div>
                    <div class="form-group"><label>Jeep:</label><input type="number" name="samsat_terdaftar_jeep" value="0" min="0" required></div>
                    <div class="form-group"><label>Minibus:</label><input type="number" name="samsat_terdaftar_minibus" value="0" min="0" required></div>
                    <div class="form-group"><label>Microbus:</label><input type="number" name="samsat_terdaftar_microbus" value="0" min="0" required></div>
                    <div class="form-group"><label>Bus:</label><input type="number" name="samsat_terdaftar_bus" value="0" min="0" required></div>
                    <div class="form-group"><label>Pickup:</label><input type="number" name="samsat_terdaftar_pickup" value="0" min="0" required></div>
                    <div class="form-group"><label>Light Truck:</label><input type="number" name="samsat_terdaftar_light_truck" value="0" min="0" required></div>
                    <div class="form-group"><label>Truck:</label><input type="number" name="samsat_terdaftar_truck" value="0" min="0" required></div>
                    <div class="form-group"><label>Blindvan:</label><input type="number" name="samsat_terdaftar_blindvan" value="0" min="0" required></div>
                    <div class="form-group"><label>Motor R2:</label><input type="number" name="samsat_terdaftar_motor_r2" value="0" min="0" required></div>
                    <div class="form-group"><label>Motor R3:</label><input type="number" name="samsat_terdaftar_motor_r3" value="0" min="0" required></div>
                    <div class="form-group"><label>Di Atas Air:</label><input type="number" name="samsat_terdaftar_di_atas_air" value="0" min="0" required></div>
                    <div class="form-group"><label>Alat Berat:</label><input type="number" name="samsat_terdaftar_alat_berat" value="0" min="0" required></div>
                    <div class="form-group"><label>Mobil R3:</label><input type="number" name="samsat_terdaftar_mobil_r3" value="0" min="0" required></div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Jumlah Unit Kendaraan BBNKB I</legend>
                <div class="fieldset-grid">
                    <div class="form-group"><label>Sedan:</label><input type="number" name="samsat_bbnkb1_sedan" value="0" min="0" required></div>
                    <div class="form-group"><label>Jeep:</label><input type="number" name="samsat_bbnkb1_jeep" value="0" min="0" required></div>
                    <div class="form-group"><label>Minibus:</label><input type="number" name="samsat_bbnkb1_minibus" value="0" min="0" required></div>
                    <div class="form-group"><label>Microbus:</label><input type="number" name="samsat_bbnkb1_microbus" value="0" min="0" required></div>
                    <div class="form-group"><label>Bus:</label><input type="number" name="samsat_bbnkb1_bus" value="0" min="0" required></div>
                    <div class="form-group"><label>Pickup:</label><input type="number" name="samsat_bbnkb1_pickup" value="0" min="0" required></div>
                    <div class="form-group"><label>Light Truck:</label><input type="number" name="samsat_bbnkb1_light_truck" value="0" min="0" required></div>
                    <div class="form-group"><label>Truck:</label><input type="number" name="samsat_bbnkb1_truck" value="0" min="0" required></div>
                    <div class="form-group"><label>Blindvan:</label><input type="number" name="samsat_bbnkb1_blindvan" value="0" min="0" required></div>
                    <div class="form-group"><label>Motor R2:</label><input type="number" name="samsat_bbnkb1_motor_r2" value="0" min="0" required></div>
                    <div class="form-group"><label>Motor R3:</label><input type="number" name="samsat_bbnkb1_motor_r3" value="0" min="0" required></div>
                    <div class="form-group"><label>Di Atas Air:</label><input type="number" name="samsat_bbnkb1_di_atas_air" value="0" min="0" required></div>
                    <div class="form-group"><label>Alat Berat:</label><input type="number" name="samsat_bbnkb1_alat_berat" value="0" min="0" required></div>
                    <div class="form-group"><label>Mobil R3:</label><input type="number" name="samsat_bbnkb1_mobil_r3" value="0" min="0" required></div>
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
            <div id="preview-table-container" data-url="{{ route('preview.data', $slugInstansi) }}">
                @include('partials.preview-table-samsat', ['sheetData' => $sheetData, 'previewError' => $previewError])
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