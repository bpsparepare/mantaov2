@extends('layouts.app')

@section('title', 'Form SAMSAT')

@section('body-class', 'page-form')

@push('styles')
<style>
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
        flex: 0 0 500px;
        background-color: #ffffff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .preview-container {
        flex: 1 1 auto;
        top: 80px;
        background-color: #ffffff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        min-width: 0;
    }

    /* DIUBAH: Hapus batasan tinggi agar pratinjau bisa memanjang penuh */
    .table-wrapper {
        overflow-x: auto;
        /* Hanya aktifkan scroll horizontal jika tabel sangat lebar */
    }

    thead th {
        position: sticky;
        top: 0;
        background-color: #e9ecef;
        z-index: 10;
        white-space: nowrap;
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
        border: none;
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        box-sizing: border-box;
    }

    .notification {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        text-align: center;
    }

    .notification.success {
        background-color: #d4edda;
        color: #1524;
    }

    .notification.error {
        background-color: #f8d7da;
        color: #721c24;
    }

    .notification.error ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
        font-size: 0.9rem;
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
        white-space: nowrap;
    }

    thead th {
        font-weight: 600;
    }

    .fieldset-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
    }

    .submit-btn {
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 25px;
        background-color: #1a4f6d;
        color: white;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 20px;
    }

    .submit-btn:hover {
        background-color: #0d2c3f;
        transform: translateY(-2px);
    }

    fieldset {
        border: 1px solid #1a4f6d;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    legend {
        font-weight: 600;
        font-size: 1.1em;
        padding: 0 10px;
        color: #1a4f6d;
    }

    @media (max-width: 992px) {
        .main-container {
            flex-direction: column;
            padding: 90px 20px 20px 20px;
            gap: 20px;
        }

        .form-container {
            flex: 1 1 100%;
            width: 100%;
            padding: 30px;
        }

        .preview-container {
            position: static;
            width: 100%;
            padding: 30px;
        }

        h1 {
            font-size: 24px;
        }
    }
</style>
@endpush

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
        <div class="notification error">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('data.store') }}" method="POST">
            @csrf
            <input type="hidden" name="nama_instansi" value="SAMSAT">
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

            <fieldset>
                <legend>Jumlah Unit Kendaraan yang Terdaftar dan Terbayar</legend>
                <div class="fieldset-grid">
                    <div class="form-group"><label>Sedan:</label><input type="number" name="samsat_terdaftar_sedan" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Jeep:</label><input type="number" name="samsat_terdaftar_jeep" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Minibus:</label><input type="number" name="samsat_terdaftar_minibus" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Microbus:</label><input type="number" name="samsat_terdaftar_microbus" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Bus:</label><input type="number" name="samsat_terdaftar_bus" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Pickup:</label><input type="number" name="samsat_terdaftar_pickup" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Light Truck:</label><input type="number" name="samsat_terdaftar_light_truck" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Truck:</label><input type="number" name="samsat_terdaftar_truck" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Blindvan:</label><input type="number" name="samsat_terdaftar_blindvan" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Motor R2:</label><input type="number" name="samsat_terdaftar_motor_r2" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Motor R3:</label><input type="number" name="samsat_terdaftar_motor_r3" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Di Atas Air:</label><input type="number" name="samsat_terdaftar_di_atas_air" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Alat Berat:</label><input type="number" name="samsat_terdaftar_alat_berat" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Mobil R3:</label><input type="number" name="samsat_terdaftar_mobil_r3" placeholder="0" min="0" required></div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Jumlah Unit Kendaraan BBNKB I</legend>
                <div class="fieldset-grid">
                    <div class="form-group"><label>Sedan:</label><input type="number" name="samsat_bbnkb1_sedan" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Jeep:</label><input type="number" name="samsat_bbnkb1_jeep" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Minibus:</label><input type="number" name="samsat_bbnkb1_minibus" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Microbus:</label><input type="number" name="samsat_bbnkb1_microbus" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Bus:</label><input type="number" name="samsat_bbnkb1_bus" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Pickup:</label><input type="number" name="samsat_bbnkb1_pickup" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Light Truck:</label><input type="number" name="samsat_bbnkb1_light_truck" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Truck:</label><input type="number" name="samsat_bbnkb1_truck" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Blindvan:</label><input type="number" name="samsat_bbnkb1_blindvan" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Motor R2:</label><input type="number" name="samsat_bbnkb1_motor_r2" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Motor R3:</label><input type="number" name="samsat_bbnkb1_motor_r3" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Di Atas Air:</label><input type="number" name="samsat_bbnkb1_di_atas_air" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Alat Berat:</label><input type="number" name="samsat_bbnkb1_alat_berat" placeholder="0" min="0" required></div>
                    <div class="form-group"><label>Mobil R3:</label><input type="number" name="samsat_bbnkb1_mobil_r3" placeholder="0" min="0" required></div>
                </div>
            </fieldset>

            <button type="submit" class="submit-btn">Kirim Data</button>
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
</script>
@endpush