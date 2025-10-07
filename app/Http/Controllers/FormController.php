<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Google\Client as GoogleClient;
use Google\Service\Sheets as GoogleSheets;

class FormController extends Controller
{
    public function showForm($slugInstansi)
    {
        $viewName = 'forms.' . $slugInstansi;
        if (!View::exists($viewName)) {
            abort(404, 'Form untuk instansi ini tidak ditemukan.');
        }

        [$sheetData, $errorMessage] = $this->getSheetData($slugInstansi);

        return view($viewName, [
            'sheetData' => $sheetData,
            'previewError' => $errorMessage,
            'slugInstansi' => $slugInstansi
        ]);
    }

    public function fetchPreviewData($slugInstansi)
    {
        [$sheetData, $errorMessage, $namaInstansi] = $this->getSheetData($slugInstansi, true);

        $partialViewMap = [
            'Dinas Lingkungan Hidup' => 'partials.preview-table-dlh',
            'Badan Urusan Logistik (BULOG)' => 'partials.preview-table-bulog',
            'Dinas Perhubungan' => 'partials.preview-table-dinas-perhubungan',
            'Dinas Pertanian, Kelautan dan Perikanan' => 'partials.preview-table-pkp',
            'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPMPTSP)' => 'partials.preview-table-dpmptsp',
            'DPRD Kota Parepare' => 'partials.preview-table-dprd',
            'Perusahaan Es Balok' => 'partials.preview-table-es-balok',
            'Institut Ilmu Sosial dan Bisnis Andi Sapada' => 'partials.preview-table-andi-sapada',
            'Institut Teknologi BJ Habibie' => 'partials.preview-table-ith',
            'J&T Express' => 'partials.preview-table-jt',
            'PAM Tirta Karajae' => 'partials.preview-table-pdam',
            'PLN' => 'partials.preview-table-pln',
            'Pos Indonesia' => 'partials.preview-table-pos',
            'RS Fatima' => 'partials.preview-table-rs-fatima',
            'RSUD Andi Makkasau' => 'partials.preview-table-rsud',
            'Perusahaan TV Kabel' => 'partials.preview-table-tv-kabel',
            'SAMSAT' => 'partials.preview-table-samsat',
            'BPJS Ketenagakerjaan' => 'partials.preview-table-bpjs-ketenagakerjaan',
            'Dinas Perdagangan' => 'partials.preview-table-dinas-perdagangan',
            'Perusahaan Es Kristal' => 'partials.preview-table-es-kristal',
            'Hadji Kalla Toyota' => 'partials.preview-table-hadji-kalla-toyota',
            'Mandiri Taspen' => 'partials.preview-table-mandiri-taspen',
            'La Tunrung Money Changer' => 'partials.preview-table-la-tunrung-mc',
            'Pegadaian' => 'partials.preview-table-pegadaian',
            'RS Khadijah' => 'partials.preview-table-rs-khadijah',
            'Universitas Muhammadiyah Parepare' => 'partials.preview-table-ump',
            'Universitas Negeri Makassar - Parepare' => 'partials.preview-table-unm',
            'Perumahan' => 'partials.preview-table-perumahan',
            'Swadharma Sarana Informatika' => 'partials.preview-table-ssi',
            'KPPN' => 'partials.preview-table-kppn',
            'Dinas PUPR' => 'partials.preview-table-pupr',
            'UPTD Pasar' => 'partials.preview-table-uptd-pasar',
            'Dinas Komunikasi dan Informatika' => 'partials.preview-table-diskominfo',
            'TELKOM' => 'partials.preview-table-telkom',
            'Dinas Perkimtan' => 'partials.preview-table-perkimtan',
            'Pengadilan Negeri' => 'partials.preview-table-pengadilan-negeri',
            'Dinas Kesehatan' => 'partials.preview-table-dinas-kesehatan',
        ];
        $partialView = $partialViewMap[$namaInstansi] ?? 'partials.preview-unavailable';

        return view($partialView, [
            'sheetData' => $sheetData,
            'previewError' => $errorMessage
        ]);
    }

    private function getSheetData($slugInstansi, $returnInstansiName = false)
    {
        $namaInstansi = '';
        try {
            $allInstansi = config('pdrb.all_instansi', []);
            foreach ($allInstansi as $instansi) {
                if (Str::slug($instansi) === $slugInstansi) {
                    $namaInstansi = $instansi;
                    break;
                }
            }

            if (!$namaInstansi) {
                throw new \Exception("Pratinjau tidak tersedia: Instansi tidak ditemukan.");
            }

            $spreadsheetId = config('pdrb.spreadsheet_ids.' . $namaInstansi);
            if (!$spreadsheetId || str_starts_with($spreadsheetId, 'GANTI_DENGAN')) {
                throw new \Exception("Pratinjau tidak tersedia: ID Spreadsheet belum diatur.");
            }

            $client = new GoogleClient();
            $client->setAuthConfig(storage_path('app/credentials.json'));
            $client->addScope(GoogleSheets::SPREADSHEETS);
            $sheets = new GoogleSheets($client);

            $sheetName = ($namaInstansi === 'DPRD Kota Parepare') ? 'DPRD' : date('Y');

            $rangeMap = [
                'Dinas Lingkungan Hidup' => 'A2:D13',
                'Badan Urusan Logistik (BULOG)' => 'A3:N9',
                'Dinas Perhubungan' => 'A3:E14',
                'Dinas Pertanian, Kelautan dan Perikanan' => 'A3:D14',
                'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPMPTSP)' => 'A2:D100',
                'DPRD Kota Parepare' => 'A3:C100',
                'Perusahaan Es Balok' => 'A2:B13',
                'Institut Ilmu Sosial dan Bisnis Andi Sapada' => 'A2:C13',
                'Institut Teknologi BJ Habibie' => 'A2:B13',
                'J&T Express' => 'A2:C13',
                'PAM Tirta Karajae' => 'A2:C13',
                'PLN' => 'A2:D13',
                'Pos Indonesia' => 'A2:F13',
                'RS Fatima' => 'A2:C13',
                'RSUD Andi Makkasau' => 'A2:C13',
                'Perusahaan TV Kabel' => 'A2:C13',
                'SAMSAT' => 'A2:P32',
                'BPJS Ketenagakerjaan' => 'A2:C13',
                'Dinas Perdagangan' => 'A2:H13',
                'Perusahaan Es Kristal' => 'A2:B13',
                'Hadji Kalla Toyota' => 'A2:C13',
                'Mandiri Taspen' => 'A2:B13',
                'La Tunrung Money Changer' => 'A2:C13',
                'Pegadaian' => 'A2:C13',
                'RS Khadijah' => 'A2:C13',
                'Universitas Muhammadiyah Parepare' => 'A2:B13',
                'Universitas Negeri Makassar - Parepare' => 'A2:B13',
                'Perumahan' => 'A2:C13',
                'Swadharma Sarana Informatika' => 'A2:C13',
                'KPPN' => 'A3:E14',
                'Dinas PUPR' => 'A2:D13',
                'UPTD Pasar' => 'A3:K14',
                'Dinas Komunikasi dan Informatika' => 'A2:E13',
                'TELKOM' => 'A2:E13',
                'Dinas Perkimtan' => 'A2:C13',
                'Pengadilan Negeri' => 'A2:B13',
                'Dinas Kesehatan' => 'A3:E14',
            ];

            if (!isset($rangeMap[$namaInstansi])) {
                throw new \Exception("Pratinjau tidak tersedia: Range untuk instansi ini belum diatur.");
            }

            $range = $sheetName . '!' . $rangeMap[$namaInstansi];
            $response = $sheets->spreadsheets_values->get($spreadsheetId, $range);
            $data = $response->getValues() ?? [];

            if ($namaInstansi === 'DPRD Kota Parepare') {
                $years = [];
                $yearRange = $sheetName . '!A3:A';
                $yearResponse = $sheets->spreadsheets_values->get($spreadsheetId, $yearRange);
                $yearValues = $yearResponse->getValues() ?? [];
                foreach ($yearValues as $row) {
                    if (!empty($row[0])) {
                        $years[] = $row[0];
                    }
                }
                $dataPackage = ['data' => $data, 'years' => $years];
                return $returnInstansiName ? [$dataPackage, null, $namaInstansi] : [$dataPackage, null];
            }

            return $returnInstansiName ? [$data, null, $namaInstansi] : [$data, null];
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            if ($namaInstansi === 'DPRD Kota Parepare' || $slugInstansi === 'dprd-kota-parepare') {
                $sheetData = ['data' => [], 'years' => []];
            } else {
                $sheetData = [];
            }
            return $returnInstansiName ? [$sheetData, $errorMessage, $namaInstansi] : [$sheetData, $errorMessage];
        }
    }
}
