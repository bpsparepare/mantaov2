<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Google\Service\Sheets as GoogleSheets;
use Google\Service\Sheets\ValueRange;
use Google\Service\Sheets\BatchUpdateValuesRequest;

class SubmissionController extends Controller
{
    /**
     * Titik masuk utama untuk semua pengiriman data form.
     */
    public function store(Request $request)
    {
        try {
            $client = new GoogleClient();
            $client->setAuthConfig(storage_path('app/credentials.json'));
            $client->addScope(GoogleSheets::SPREADSHEETS);
            $sheets = new GoogleSheets($client);

            $namaInstansi = $request->input('nama_instansi');
            $spreadsheetId = config('pdrb.spreadsheet_ids.' . $namaInstansi);

            if (!$spreadsheetId || str_starts_with($spreadsheetId, 'GANTI_DENGAN')) {
                return back()->with('error', 'Konfigurasi Spreadsheet ID untuk ' . $namaInstansi . ' belum diisi.');
            }

            // Tangani DPRD secara khusus karena formatnya berbeda (tidak pakai sheet tahunan)
            if ($namaInstansi === 'DPRD Kota Parepare') {
                return $this->handleDprdSubmission($sheets, $spreadsheetId, $request);
            }

            // Untuk instansi lain, gunakan sheet berdasarkan tahun saat ini
            $sheetName = date('Y');

            // Daftar instansi yang formatnya meng-UPDATE sel, bukan menambah baris baru
            $updateFormatInstansi = [
                'Badan Urusan Logistik (BULOG)',
                'Dinas Lingkungan Hidup',
                'Dinas Pertanian, Kelautan dan Perikanan',
                'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPMPTSP)',
                'Perusahaan Es Balok',
                'Institut Ilmu Sosial dan Bisnis Andi Sapada',
                'Institut Teknologi BJ Habibie',
                'J&T Express',
                'PAM Tirta Karajae',
                'PLN',
                'Pos Indonesia',
                'RS Fatima',
                'RSUD Andi Makkasau',
                'SAMSAT',
                'Perusahaan TV Kabel',
                'BPJS Ketenagakerjaan',
                'Dinas Perdagangan',
                'Perusahaan Es Kristal',
                'Hadji Kalla Toyota',
                'Mandiri Taspen',
                'La Tunrung Money Changer',
                'Pegadaian',
                'RS Khadijah',
                'Universitas Muhammadiyah Parepare',
                'Universitas Negeri Makassar - Parepare',
                'Perumahan',
                'Swadharma Sarana Informatika'
            ];

            if (in_array($namaInstansi, $updateFormatInstansi)) {
                return $this->handleUpdate($sheets, $spreadsheetId, $sheetName, $request);
            } else {
                return $this->handleAppend($sheets, $spreadsheetId, $sheetName, $request);
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            if (str_contains($errorMessage, 'edit a protected cell')) {
                return back()->with('error', 'Gagal: Periode pengisian untuk data ini telah berakhir atau sheet terkunci. Silakan hubungi admin BPS.');
            }
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $errorMessage);
        }
    }

    /**
     * Logika khusus untuk submit data DPRD (update atau append).
     */
    private function handleDprdSubmission($sheets, $spreadsheetId, Request $request)
    {
        $tahunInput = $request->input('tahun');
        $sheetName = 'DPRD'; // Pastikan nama sheet ini benar

        if ($tahunInput === 'new_year') {
            $tahun = $request->input('tahun_baru');
            if (empty($tahun)) {
                return back()->with('error', 'Tahun baru tidak boleh kosong.');
            }
            $newRow = [$tahun, $request->input('dprd_jumlah_laki', 0), $request->input('dprd_jumlah_perempuan', 0)];
            $body = new ValueRange(['values' => [$newRow]]);
            $params = ['valueInputOption' => 'USER_ENTERED'];
            $sheets->spreadsheets_values->append($spreadsheetId, $sheetName, $body, $params);
            return back()->with('success', 'Data untuk tahun ' . $tahun . ' berhasil ditambahkan!');
        }

        $tahun = $tahunInput;
        $range = $sheetName . '!A3:A';
        $response = $sheets->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues() ?? [];
        $rowNumber = null;
        foreach ($values as $index => $row) {
            if (isset($row[0]) && $row[0] == $tahun) {
                $rowNumber = $index + 3;
                break;
            }
        }

        if (is_null($rowNumber)) {
            return back()->with('error', 'Tahun ' . $tahun . ' tidak ditemukan untuk diperbarui. Jika ini tahun baru, pilih opsi "+ Tambah Tahun Baru".');
        }

        $updateRange = $sheetName . '!B' . $rowNumber . ':C' . $rowNumber;
        $updateBody = new ValueRange(['values' => [[$request->input('dprd_jumlah_laki', 0), $request->input('dprd_jumlah_perempuan', 0)]]]);
        $params = ['valueInputOption' => 'USER_ENTERED'];
        $sheets->spreadsheets_values->update($spreadsheetId, $updateRange, $updateBody, $params);
        return back()->with('success', 'Data untuk tahun ' . $tahun . ' berhasil diperbarui!');
    }

    /**
     * Menangani semua instansi yang formatnya meng-UPDATE sel.
     */
    private function handleUpdate($sheets, $spreadsheetId, $sheetName, Request $request)
    {
        $bulan = $request->input('bulan');
        $namaInstansi = $request->input('nama_instansi');
        $dataToUpdate = [];

        switch ($namaInstansi) {
            case 'Badan Urusan Logistik (BULOG)':
                $monthToColumn = ['Januari' => 'C', 'Februari' => 'D', 'Maret' => 'E', 'April' => 'F', 'Mei' => 'G', 'Juni' => 'H', 'Juli' => 'I', 'Agustus' => 'J', 'September' => 'K', 'Oktober' => 'L', 'November' => 'M', 'Desember' => 'N'];
                $column = $monthToColumn[$bulan] ?? null;
                if (!$column) return back()->with('error', 'Bulan tidak valid.');
                $fieldToRowMap = ['bulog_stok_beras_medium' => 3, 'bulog_stok_beras_premium' => 4, 'bulog_stok_gula_pasir' => 5, 'bulog_stok_minyak_goreng' => 6, 'bulog_stok_daging_kerbau' => 7, 'bulog_stok_tepung_terigu' => 8, 'bulog_stok_jagung_pakan_ternak' => 9];
                foreach ($fieldToRowMap as $field => $row) {
                    $value = $request->input($field);
                    $valueRange = new ValueRange();
                    $valueRange->setRange($sheetName . '!' . $column . $row);
                    $valueRange->setValues([[$value]]);
                    $dataToUpdate[] = $valueRange;
                }
                break;

            case 'Dinas Lingkungan Hidup':
                $monthToRow = ['Januari' => 2, 'Februari' => 3, 'Maret' => 4, 'April' => 5, 'Mei' => 6, 'Juni' => 7, 'Juli' => 8, 'Agustus' => 9, 'September' => 10, 'Oktober' => 11, 'November' => 12, 'Desember' => 13];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');
                $fieldToColumnMap = ['dlh_volume_timbulan' => 'B', 'dlh_volume_ditangani' => 'C', 'dlh_volume_dikelola' => 'D'];
                foreach ($fieldToColumnMap as $field => $column) {
                    $value = $request->input($field);
                    $valueRange = new ValueRange();
                    $valueRange->setRange($sheetName . '!' . $column . $row);
                    $valueRange->setValues([[$value]]);
                    $dataToUpdate[] = $valueRange;
                }
                break;

            case 'Dinas Pertanian, Kelautan dan Perikanan':
                $monthToRow = ['Januari' => 3, 'Februari' => 4, 'Maret' => 5, 'April' => 6, 'Mei' => 7, 'Juni' => 8, 'Juli' => 9, 'Agustus' => 10, 'September' => 11, 'Oktober' => 12, 'November' => 13, 'Desember' => 14];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');
                $fieldToColumnMap = ['pkp_populasi_broiler' => 'B', 'pkp_populasi_buras' => 'C', 'pkp_produksi_telur' => 'D'];
                foreach ($fieldToColumnMap as $field => $column) {
                    $value = $request->input($field);
                    $valueRange = new ValueRange();
                    $valueRange->setRange($sheetName . '!' . $column . $row);
                    $valueRange->setValues([[$value]]);
                    $dataToUpdate[] = $valueRange;
                }
                break;

            case 'Perusahaan Es Balok':
                $monthToRow = ['Januari' => 2, 'Februari' => 3, 'Maret' => 4, 'April' => 5, 'Mei' => 6, 'Juni' => 7, 'Juli' => 8, 'Agustus' => 9, 'September' => 10, 'Oktober' => 11, 'November' => 12, 'Desember' => 13];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');
                $value = $request->input('esbalok_produksi_ton');
                $valueRange = new ValueRange();
                $valueRange->setRange($sheetName . '!B' . $row);
                $valueRange->setValues([[$value]]);
                $dataToUpdate[] = $valueRange;
                break;

            case 'Institut Ilmu Sosial dan Bisnis Andi Sapada':
                $monthToRow = ['Januari' => 2, 'Februari' => 3, 'Maret' => 4, 'April' => 5, 'Mei' => 6, 'Juni' => 7, 'Juli' => 8, 'Agustus' => 9, 'September' => 10, 'Oktober' => 11, 'November' => 12, 'Desember' => 13];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');
                $fieldToColumnMap = ['andi_sapada_jumlah_mahasiswa' => 'B', 'andi_sapada_pendapatan' => 'C'];
                foreach ($fieldToColumnMap as $field => $column) {
                    $value = $request->input($field);
                    $valueRange = new ValueRange();
                    $valueRange->setRange($sheetName . '!' . $column . $row);
                    $valueRange->setValues([[$value]]);
                    $dataToUpdate[] = $valueRange;
                }
                break;

            case 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPMPTSP)':
                $monthMap = ['Januari' => 0, 'Februari' => 1, 'Maret' => 2, 'April' => 3, 'Mei' => 4, 'Juni' => 5, 'Juli' => 6, 'Agustus' => 7, 'September' => 8, 'Oktober' => 9, 'November' => 10, 'Desember' => 11];
                $monthIndex = $monthMap[$bulan] ?? null;
                if (is_null($monthIndex)) return back()->with('error', 'Bulan tidak valid.');
                $startRow = ($monthIndex * 5) + 2;
                $buildingOffsets = ["Rumah Tinggal" => 0, "Bangunan Usaha (RUKO)" => 1, "Perumahan" => 2, "Sosial (Tanah Pemerintah)" => 3, "Bangunan Pemerintah" => 4];
                $submittedData = $request->input('dpmptsp_data', []);
                foreach ($submittedData as $buildingType => $values) {
                    if (isset($buildingOffsets[$buildingType])) {
                        $offset = $buildingOffsets[$buildingType];
                        $finalRow = $startRow + $offset;
                        $valueRangeUnit = new ValueRange();
                        $valueRangeUnit->setRange($sheetName . '!C' . $finalRow);
                        $valueRangeUnit->setValues([[$values['jumlah_unit'] ?? null]]);
                        $dataToUpdate[] = $valueRangeUnit;
                        $valueRangeBiaya = new ValueRange();
                        $valueRangeBiaya->setRange($sheetName . '!D' . $finalRow);
                        $valueRangeBiaya->setValues([[$values['biaya_pbg'] ?? null]]);
                        $dataToUpdate[] = $valueRangeBiaya;
                    }
                }
                break;

            case 'Institut Teknologi BJ Habibie':
                $monthToRow = [
                    'Januari' => 2,
                    'Februari' => 3,
                    'Maret' => 4,
                    'April' => 5,
                    'Mei' => 6,
                    'Juni' => 7,
                    'Juli' => 8,
                    'Agustus' => 9,
                    'September' => 10,
                    'Oktober' => 11,
                    'November' => 12,
                    'Desember' => 13
                ];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');

                $value = $request->input('ith_jumlah_mahasiswa');
                $valueRange = new ValueRange();
                $valueRange->setRange($sheetName . '!B' . $row);
                $valueRange->setValues([[$value]]);
                $dataToUpdate[] = $valueRange;
                break;

            case 'J&T Express':
                $request->validate([
                    'jt_paket_dikirim' => 'required|numeric|min:0',
                    'jt_paket_diterima' => 'required|numeric|min:0',
                ], [
                    'jt_paket_dikirim.required' => 'Jumlah paket dikirim tidak boleh kosong.',
                    'jt_paket_diterima.required' => 'Jumlah paket diterima tidak boleh kosong.',
                ]);

                $monthToRow = [
                    'Januari' => 2,
                    'Februari' => 3,
                    'Maret' => 4,
                    'April' => 5,
                    'Mei' => 6,
                    'Juni' => 7,
                    'Juli' => 8,
                    'Agustus' => 9,
                    'September' => 10,
                    'Oktober' => 11,
                    'November' => 12,
                    'Desember' => 13
                ];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');

                $fieldToColumnMap = [
                    'jt_paket_dikirim' => 'B',
                    'jt_paket_diterima' => 'C',
                ];

                foreach ($fieldToColumnMap as $field => $column) {
                    $value = $request->input($field);
                    $valueRange = new ValueRange();
                    $valueRange->setRange($sheetName . '!' . $column . $row);
                    $valueRange->setValues([[$value]]);
                    $dataToUpdate[] = $valueRange;
                }
                break;

            case 'PAM Tirta Karajae':
                $request->validate([
                    'pdam_volume_air' => 'required|numeric|min:0',
                    'pdam_pendapatan_air' => 'required|numeric|min:0',
                ], [
                    'pdam_volume_air.required' => 'Volume Air tidak boleh kosong.',
                    'pdam_pendapatan_air.required' => 'Pendapatan Penjualan tidak boleh kosong.',
                ]);

                $monthToRow = [
                    'Januari' => 2,
                    'Februari' => 3,
                    'Maret' => 4,
                    'April' => 5,
                    'Mei' => 6,
                    'Juni' => 7,
                    'Juli' => 8,
                    'Agustus' => 9,
                    'September' => 10,
                    'Oktober' => 11,
                    'November' => 12,
                    'Desember' => 13
                ];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');

                $fieldToColumnMap = [
                    'pdam_volume_air' => 'B',
                    'pdam_pendapatan_air' => 'C',
                ];

                foreach ($fieldToColumnMap as $field => $column) {
                    $value = $request->input($field);
                    $valueRange = new ValueRange();
                    $valueRange->setRange($sheetName . '!' . $column . $row);
                    $valueRange->setValues([[$value]]);
                    $dataToUpdate[] = $valueRange;
                }
                break;

            case 'PLN':
                $request->validate([
                    'pln_listrik_disalurkan' => 'required|numeric|min:0',
                    'pln_listrik_terjual' => 'required|numeric|min:0',
                    'pln_jumlah_pelanggan' => 'required|numeric|min:0',
                ]);

                $monthToRow = [
                    'Januari' => 2,
                    'Februari' => 3,
                    'Maret' => 4,
                    'April' => 5,
                    'Mei' => 6,
                    'Juni' => 7,
                    'Juli' => 8,
                    'Agustus' => 9,
                    'September' => 10,
                    'Oktober' => 11,
                    'November' => 12,
                    'Desember' => 13
                ];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');

                $fieldToColumnMap = [
                    'pln_listrik_disalurkan' => 'B',
                    'pln_listrik_terjual' => 'C',
                    'pln_jumlah_pelanggan' => 'D',
                ];

                foreach ($fieldToColumnMap as $field => $column) {
                    $value = $request->input($field);
                    $valueRange = new ValueRange();
                    $valueRange->setRange($sheetName . '!' . $column . $row);
                    $valueRange->setValues([[$value]]);
                    $dataToUpdate[] = $valueRange;
                }
                break;

            case 'Pos Indonesia':
                $request->validate([
                    'pos_surat_dikirim' => 'required|numeric|min:0',
                    'pos_surat_diterima' => 'required|numeric|min:0',
                    'pos_wesel_pos' => 'required|numeric|min:0',
                ]);

                $monthToRow = [
                    'Januari' => 2,
                    'Februari' => 3,
                    'Maret' => 4,
                    'April' => 5,
                    'Mei' => 6,
                    'Juni' => 7,
                    'Juli' => 8,
                    'Agustus' => 9,
                    'September' => 10,
                    'Oktober' => 11,
                    'November' => 12,
                    'Desember' => 13
                ];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');

                $fieldToColumnMap = [
                    'pos_surat_dikirim' => 'B',
                    'pos_surat_diterima' => 'C',
                    'pos_wesel_pos' => 'D',
                ];

                foreach ($fieldToColumnMap as $field => $column) {
                    $value = $request->input($field);
                    $valueRange = new ValueRange();
                    $valueRange->setRange($sheetName . '!' . $column . $row);
                    $valueRange->setValues([[$value]]);
                    $dataToUpdate[] = $valueRange;
                }
                break;

            case 'RS Fatima':
                $request->validate([
                    'rsfatima_rawat_inap' => 'required|numeric|min:0',
                    'rsfatima_rawat_jalan' => 'required|numeric|min:0',
                ]);

                $monthToRow = [
                    'Januari' => 2,
                    'Februari' => 3,
                    'Maret' => 4,
                    'April' => 5,
                    'Mei' => 6,
                    'Juni' => 7,
                    'Juli' => 8,
                    'Agustus' => 9,
                    'September' => 10,
                    'Oktober' => 11,
                    'November' => 12,
                    'Desember' => 13
                ];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');

                $fieldToColumnMap = [
                    'rsfatima_rawat_inap' => 'B',
                    'rsfatima_rawat_jalan' => 'C',
                ];

                foreach ($fieldToColumnMap as $field => $column) {
                    $value = $request->input($field);
                    $valueRange = new ValueRange();
                    $valueRange->setRange($sheetName . '!' . $column . $row);
                    $valueRange->setValues([[$value]]);
                    $dataToUpdate[] = $valueRange;
                }
                break;

            case 'RSUD Andi Makkasau':
                $request->validate([
                    'rsud_rawat_inap' => 'required|numeric|min:0',
                    'rsud_rawat_jalan' => 'required|numeric|min:0',
                ]);

                $monthToRow = [
                    'Januari' => 2,
                    'Februari' => 3,
                    'Maret' => 4,
                    'April' => 5,
                    'Mei' => 6,
                    'Juni' => 7,
                    'Juli' => 8,
                    'Agustus' => 9,
                    'September' => 10,
                    'Oktober' => 11,
                    'November' => 12,
                    'Desember' => 13
                ];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');

                $fieldToColumnMap = [
                    'rsud_rawat_inap' => 'B',
                    'rsud_rawat_jalan' => 'C',
                ];

                foreach ($fieldToColumnMap as $field => $column) {
                    $value = $request->input($field);
                    $valueRange = new ValueRange();
                    $valueRange->setRange($sheetName . '!' . $column . $row);
                    $valueRange->setValues([[$value]]);
                    $dataToUpdate[] = $valueRange;
                }
                break;

            case 'Perusahaan TV Kabel':
                $request->validate([
                    'tvkabel_pendapatan' => 'required|numeric|min:0',
                    'tvkabel_jumlah_pelanggan' => 'required|numeric|min:0',
                ]);

                $monthToRow = [
                    'Januari' => 2,
                    'Februari' => 3,
                    'Maret' => 4,
                    'April' => 5,
                    'Mei' => 6,
                    'Juni' => 7,
                    'Juli' => 8,
                    'Agustus' => 9,
                    'September' => 10,
                    'Oktober' => 11,
                    'November' => 12,
                    'Desember' => 13
                ];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');

                $fieldToColumnMap = [
                    'tvkabel_pendapatan' => 'B',
                    'tvkabel_jumlah_pelanggan' => 'C',
                ];

                foreach ($fieldToColumnMap as $field => $column) {
                    $value = $request->input($field);
                    $valueRange = new ValueRange();
                    $valueRange->setRange($sheetName . '!' . $column . $row);
                    $valueRange->setValues([[$value]]);
                    $dataToUpdate[] = $valueRange;
                }
                break;

            case 'SAMSAT':
                $monthToRow = [
                    'Januari' => 3,
                    'Februari' => 4,
                    'Maret' => 5,
                    'April' => 6,
                    'Mei' => 7,
                    'Juni' => 8,
                    'Juli' => 9,
                    'Agustus' => 10,
                    'September' => 11,
                    'Oktober' => 12,
                    'November' => 13,
                    'Desember' => 14
                ];
                $rowTerdaftar = $monthToRow[$bulan] ?? null;
                $rowBbnkb1 = $rowTerdaftar ? $rowTerdaftar + 17 : null; // Tabel BBNKB I dimulai 17 baris di bawahnya (3+17=20)
                if (!$rowTerdaftar) return back()->with('error', 'Bulan tidak valid.');

                // --- PEMETAAN KOLOM YANG SUDAH BENAR ---
                $fieldsMap = [
                    'samsat_terdaftar_sedan' => 'B',
                    'samsat_terdaftar_jeep' => 'C',
                    'samsat_terdaftar_minibus' => 'D',
                    'samsat_terdaftar_microbus' => 'E',
                    'samsat_terdaftar_bus' => 'F',
                    'samsat_terdaftar_pickup' => 'G',
                    'samsat_terdaftar_light_truck' => 'H',
                    'samsat_terdaftar_truck' => 'I',
                    'samsat_terdaftar_blindvan' => 'J',
                    'samsat_terdaftar_motor_r2' => 'K',
                    'samsat_terdaftar_motor_r3' => 'L',
                    'samsat_terdaftar_di_atas_air' => 'M',
                    'samsat_terdaftar_alat_berat' => 'N',
                    'samsat_terdaftar_mobil_r3' => 'O',
                    'samsat_bbnkb1_sedan' => 'B',
                    'samsat_bbnkb1_jeep' => 'C',
                    'samsat_bbnkb1_minibus' => 'D',
                    'samsat_bbnkb1_microbus' => 'E',
                    'samsat_bbnkb1_bus' => 'F',
                    'samsat_bbnkb1_pickup' => 'G',
                    'samsat_bbnkb1_light_truck' => 'H',
                    'samsat_bbnkb1_truck' => 'I',
                    'samsat_bbnkb1_blindvan' => 'J',
                    'samsat_bbnkb1_motor_r2' => 'K',
                    'samsat_bbnkb1_motor_r3' => 'L',
                    'samsat_bbnkb1_di_atas_air' => 'M',
                    'samsat_bbnkb1_alat_berat' => 'N',
                    'samsat_bbnkb1_mobil_r3' => 'O',
                ];

                foreach ($fieldsMap as $field => $column) {
                    // Hanya proses jika field diisi (tidak null dan tidak string kosong)
                    if ($request->filled($field)) {
                        $targetRow = str_contains($field, '_terdaftar_') ? $rowTerdaftar : $rowBbnkb1;
                        $value = $request->input($field);
                        $valueRange = new ValueRange();
                        $valueRange->setRange($sheetName . '!' . $column . $targetRow);
                        $valueRange->setValues([[$value]]);
                        $dataToUpdate[] = $valueRange;
                    }
                }
                break;

            case 'BPJS Ketenagakerjaan':
                $monthToRow = ['Januari' => 2, 'Februari' => 3, 'Maret' => 4, 'April' => 5, 'Mei' => 6, 'Juni' => 7, 'Juli' => 8, 'Agustus' => 9, 'September' => 10, 'Oktober' => 11, 'November' => 12, 'Desember' => 13];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');

                $fieldToColumnMap = [
                    'bpjs_peserta_klaim' => 'B',
                    'bpjs_nilai_klaim' => 'C',
                ];

                foreach ($fieldToColumnMap as $field => $column) {
                    $value = $request->input($field);
                    $valueRange = new ValueRange();
                    $valueRange->setRange($sheetName . '!' . $column . $row);
                    $valueRange->setValues([[$value]]);
                    $dataToUpdate[] = $valueRange;
                }
                break;

            case 'Dinas Perdagangan':
                $monthToRow = ['Januari' => 2, 'Februari' => 3, 'Maret' => 4, 'April' => 5, 'Mei' => 6, 'Juni' => 7, 'Juli' => 8, 'Agustus' => 9, 'September' => 10, 'Oktober' => 11, 'November' => 12, 'Desember' => 13];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');

                $value = $request->input('perdagangan_jumlah_pedagang');
                $valueRange = new ValueRange();
                $valueRange->setRange($sheetName . '!B' . $row);
                $valueRange->setValues([[$value]]);
                $dataToUpdate[] = $valueRange;
                break;

            case 'Perusahaan Es Kristal':
                $monthToRow = ['Januari' => 2, 'Februari' => 3, 'Maret' => 4, 'April' => 5, 'Mei' => 6, 'Juni' => 7, 'Juli' => 8, 'Agustus' => 9, 'September' => 10, 'Oktober' => 11, 'November' => 12, 'Desember' => 13];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');

                $value = $request->input('eskristal_produksi_kg');
                $valueRange = new ValueRange();
                $valueRange->setRange($sheetName . '!B' . $row);
                $valueRange->setValues([[$value]]);
                $dataToUpdate[] = $valueRange;
                break;

            case 'Hadji Kalla Toyota':
                $monthToRow = ['Januari' => 2, 'Februari' => 3, 'Maret' => 4, 'April' => 5, 'Mei' => 6, 'Juni' => 7, 'Juli' => 8, 'Agustus' => 9, 'September' => 10, 'Oktober' => 11, 'November' => 12, 'Desember' => 13];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');

                $fieldToColumnMap = [
                    'toyota_jumlah_mobil' => 'B',
                    'toyota_pendapatan' => 'C',
                ];

                foreach ($fieldToColumnMap as $field => $column) {
                    $value = $request->input($field);
                    $valueRange = new ValueRange();
                    $valueRange->setRange($sheetName . '!' . $column . $row);
                    $valueRange->setValues([[$value]]);
                    $dataToUpdate[] = $valueRange;
                }
                break;

            case 'Mandiri Taspen':
                $monthToRow = ['Januari' => 2, 'Februari' => 3, 'Maret' => 4, 'April' => 5, 'Mei' => 6, 'Juni' => 7, 'Juli' => 8, 'Agustus' => 9, 'September' => 10, 'Oktober' => 11, 'November' => 12, 'Desember' => 13];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');

                $value = $request->input('taspen_jumlah_klaim');
                $valueRange = new ValueRange();
                $valueRange->setRange($sheetName . '!B' . $row);
                $valueRange->setValues([[$value]]);
                $dataToUpdate[] = $valueRange;
                break;

            case 'La Tunrung Money Changer':
                $monthToRow = ['Januari' => 2, 'Februari' => 3, 'Maret' => 4, 'April' => 5, 'Mei' => 6, 'Juni' => 7, 'Juli' => 8, 'Agustus' => 9, 'September' => 10, 'Oktober' => 11, 'November' => 12, 'Desember' => 13];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');

                $fieldToColumnMap = [
                    'latunrung_jumlah_transaksi' => 'B',
                    'latunrung_pendapatan' => 'C',
                ];

                foreach ($fieldToColumnMap as $field => $column) {
                    $value = $request->input($field);
                    $valueRange = new ValueRange();
                    $valueRange->setRange($sheetName . '!' . $column . $row);
                    $valueRange->setValues([[$value]]);
                    $dataToUpdate[] = $valueRange;
                }
                break;

            case 'Pegadaian':
                $monthToRow = ['Januari' => 2, 'Februari' => 3, 'Maret' => 4, 'April' => 5, 'Mei' => 6, 'Juni' => 7, 'Juli' => 8, 'Agustus' => 9, 'September' => 10, 'Oktober' => 11, 'November' => 12, 'Desember' => 13];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');

                $fieldToColumnMap = [
                    'pegadaian_jumlah_nasabah' => 'B',
                    'pegadaian_nilai_gadai' => 'C',
                ];

                foreach ($fieldToColumnMap as $field => $column) {
                    $value = $request->input($field);
                    $valueRange = new ValueRange();
                    $valueRange->setRange($sheetName . '!' . $column . $row);
                    $valueRange->setValues([[$value]]);
                    $dataToUpdate[] = $valueRange;
                }
                break;

            case 'RS Khadijah':
                $monthToRow = ['Januari' => 2, 'Februari' => 3, 'Maret' => 4, 'April' => 5, 'Mei' => 6, 'Juni' => 7, 'Juli' => 8, 'Agustus' => 9, 'September' => 10, 'Oktober' => 11, 'November' => 12, 'Desember' => 13];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');

                $fieldToColumnMap = [
                    'rskhadijah_rawat_jalan' => 'B',
                    'rskhadijah_rawat_inap' => 'C',
                ];

                foreach ($fieldToColumnMap as $field => $column) {
                    $value = $request->input($field);
                    $valueRange = new ValueRange();
                    $valueRange->setRange($sheetName . '!' . $column . $row);
                    $valueRange->setValues([[$value]]);
                    $dataToUpdate[] = $valueRange;
                }
                break;

            case 'Universitas Muhammadiyah Parepare':
                $monthToRow = ['Januari' => 2, 'Februari' => 3, 'Maret' => 4, 'April' => 5, 'Mei' => 6, 'Juni' => 7, 'Juli' => 8, 'Agustus' => 9, 'September' => 10, 'Oktober' => 11, 'November' => 12, 'Desember' => 13];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');

                $value = $request->input('jumlah_mahasiswa');
                $valueRange = new ValueRange();
                $valueRange->setRange($sheetName . '!B' . $row);
                $valueRange->setValues([[$value]]);
                $dataToUpdate[] = $valueRange;
                break;

            case 'Universitas Negeri Makassar - Parepare':
                $monthToRow = ['Januari' => 2, 'Februari' => 3, 'Maret' => 4, 'April' => 5, 'Mei' => 6, 'Juni' => 7, 'Juli' => 8, 'Agustus' => 9, 'September' => 10, 'Oktober' => 11, 'November' => 12, 'Desember' => 13];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');

                $value = $request->input('jumlah_mahasiswa');
                $valueRange = new ValueRange();
                $valueRange->setRange($sheetName . '!B' . $row);
                $valueRange->setValues([[$value]]);
                $dataToUpdate[] = $valueRange;
                break;

            case 'Perumahan':
                $monthToRow = ['Januari' => 2, 'Februari' => 3, 'Maret' => 4, 'April' => 5, 'Mei' => 6, 'Juni' => 7, 'Juli' => 8, 'Agustus' => 9, 'September' => 10, 'Oktober' => 11, 'November' => 12, 'Desember' => 13];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');

                $fieldToColumnMap = [
                    'perumahan_rumah_terjual' => 'B',
                    'perumahan_rumah_belum_terjual' => 'C',
                ];

                foreach ($fieldToColumnMap as $field => $column) {
                    $value = $request->input($field);
                    $valueRange = new ValueRange();
                    $valueRange->setRange($sheetName . '!' . $column . $row);
                    $valueRange->setValues([[$value]]);
                    $dataToUpdate[] = $valueRange;
                }
                break;

            case 'Swadharma Sarana Informatika':
                $monthToRow = ['Januari' => 2, 'Februari' => 3, 'Maret' => 4, 'April' => 5, 'Mei' => 6, 'Juni' => 7, 'Juli' => 8, 'Agustus' => 9, 'September' => 10, 'Oktober' => 11, 'November' => 12, 'Desember' => 13];
                $row = $monthToRow[$bulan] ?? null;
                if (!$row) return back()->with('error', 'Bulan tidak valid.');

                $fieldToColumnMap = [
                    'ssi_jumlah_order' => 'B',
                    'ssi_pendapatan' => 'C',
                ];

                foreach ($fieldToColumnMap as $field => $column) {
                    $value = $request->input($field);
                    $valueRange = new ValueRange();
                    $valueRange->setRange($sheetName . '!' . $column . $row);
                    $valueRange->setValues([[$value]]);
                    $dataToUpdate[] = $valueRange;
                }
                break;

            default:
                return back()->with('error', 'Logika UPDATE untuk ' . $namaInstansi . ' belum dibuat.');
        }

        if (!empty($dataToUpdate)) {
            $batchUpdateRequest = new BatchUpdateValuesRequest();
            $batchUpdateRequest->setValueInputOption('USER_ENTERED');
            $batchUpdateRequest->setData($dataToUpdate);
            $sheets->spreadsheets_values->batchUpdate($spreadsheetId, $batchUpdateRequest);
        }
        return back()->with('success', 'Data ' . $namaInstansi . ' untuk bulan ' . $bulan . ' berhasil diperbarui!');
    }

    private function handleAppend($sheets, $spreadsheetId, $sheetName, Request $request)
    {
        $namaInstansi = $request->input('nama_instansi');
        $row = $this->prepareSingleRowData($request);
        if (empty($row)) {
            return back()->with('error', 'Tidak ada data valid untuk dikirim.');
        }
        $values = [$row];
        $body = new ValueRange(['values' => $values]);
        $params = ['valueInputOption' => 'USER_ENTERED'];
        $sheets->spreadsheets_values->append($spreadsheetId, $sheetName, $body, $params);
        return back()->with('success', 'Data ' . $namaInstansi . ' berhasil dikirim!');
    }

    private function prepareSingleRowData(Request $request)
    {
        $namaInstansi = $request->input('nama_instansi');
        $periode = $request->input('bulan', $request->input('tahun'));
        $data = [now()->toDateTimeString(), $periode];
        $fieldsMap = [
            'Institut Teknologi BJ Habibie' => ['ith_jumlah_mahasiswa'],
            'RS Fatima' => ['rsfatima_rawat_inap', 'rsfatima_rawat_jalan'],
            'RSUD Andi Makkasau' => ['rsud_rawat_inap', 'rsud_rawat_jalan'],
            'J&T Express' => ['jt_paket_dikirim', 'jt_paket_diterima'],
            'Pos Indonesia' => ['pos_surat_dikirim', 'pos_surat_diterima', 'pos_wesel_pos'],
            'PAM Tirta Karajae' => ['pdam_volume_air', 'pdam_pendapatan_air'],
            'PLN' => ['pln_listrik_disalurkan', 'pln_listrik_terjual', 'pln_jumlah_pelanggan'],
            'SAMSAT' => ['samsat_terdaftar_sedan', 'samsat_terdaftar_jeep', 'samsat_terdaftar_minibus', 'samsat_terdaftar_bus', 'samsat_terdaftar_pickup', 'samsat_terdaftar_light_truck', 'samsat_terdaftar_truck', 'samsat_terdaftar_motor_r2', 'samsat_terdaftar_motor_r3', 'samsat_terdaftar_air', 'samsat_terdaftar_alat_berat', 'samsat_terdaftar_mobil_r3', 'samsat_bbnkb1_sedan', 'samsat_bbnkb1_jeep', 'samsat_bbnkb1_minibus', 'samsat_bbnkb1_bus', 'samsat_bbnkb1_pickup', 'samsat_bbnkb1_light_truck', 'samsat_bbnkb1_truck', 'samsat_bbnkb1_motor_r2', 'samsat_bbnkb1_motor_r3', 'samsat_bbnkb1_air', 'samsat_bbnkb1_alat_berat', 'samsat_bbnkb1_mobil_r3'],
        ];
        if (!isset($fieldsMap[$namaInstansi])) return [];
        foreach ($fieldsMap[$namaInstansi] as $field) {
            $data[] = $request->input($field);
        }
        return $data;
    }
}
