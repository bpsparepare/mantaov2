@if($previewError)
<div class="notification error">{{ $previewError }}</div>
@else
@php
// Memisahkan data untuk dua tabel yang berbeda berdasarkan struktur sheet
// Asumsi data diambil dari A3:P32
$terdaftarData = array_slice($sheetData, 1, 12);
$terdaftarTotal = $sheetData[13] ?? [];
$bbnkb1Data = array_slice($sheetData, 18, 12);
$bbnkb1Total = $sheetData[30] ?? [];

$headers = ["Bulan", "Sedan", "Jeep", "Minibus", "Microbus", "Bus", "Pickup", "Light Truck", "Truck", "Blindvan", "Motor R2", "Motor R3", "Kendaraan Di Atas Air", "Alat Berat", "Mobil R3", "Jumlah"];
@endphp

<h4>Jumlah Unit Kendaraan yang Terdaftar dan Terbayar</h4>
<table>
    <thead>
        <tr>
            @foreach($headers as $header)
            <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse($terdaftarData as $row)
        <tr>
            @for ($j = 0; $j < count($headers); $j++)
                <td>
                {{-- Kolom A (Bulan) diperlakukan sebagai teks --}}
                @if($j === 0)
                {{ $row[$j] ?? '-' }}
                @else
                {{ (isset($row[$j]) && $row[$j] !== '') ? number_format((float)str_replace('.', '', $row[$j]), 0, ',', '.') : '-' }}
                @endif
                </td>
                @endfor
        </tr>
        @empty
        <tr>
            <td colspan="{{ count($headers) }}" style="text-align: center;">Tidak ada data.</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr style="font-weight: bold; background-color: #f8f9fa;">
            @for ($j = 0; $j < count($headers); $j++)
                <td>
                {{-- Kolom pertama di baris jumlah adalah teks "Jumlah" --}}
                @if($j === 0)
                {{ $terdaftarTotal[$j] ?? 'Jumlah' }}
                @else
                {{ (isset($terdaftarTotal[$j]) && $terdaftarTotal[$j] !== '') ? number_format((float)str_replace('.', '', $terdaftarTotal[$j]), 0, ',', '.') : '-' }}
                @endif
                </td>
                @endfor
        </tr>
    </tfoot>
</table>

<h4 style="margin-top: 30px;">Jumlah Unit Kendaraan BBNKB I</h4>
<table>
    <thead>
        <tr>
            @foreach($headers as $header)
            <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse($bbnkb1Data as $row)
        <tr>
            @for ($j = 0; $j < count($headers); $j++)
                <td>
                @if($j === 0)
                {{ $row[$j] ?? '-' }}
                @else
                {{ (isset($row[$j]) && $row[$j] !== '') ? number_format((float)str_replace('.', '', $row[$j]), 0, ',', '.') : '-' }}
                @endif
                </td>
                @endfor
        </tr>
        @empty
        <tr>
            <td colspan="{{ count($headers) }}" style="text-align: center;">Tidak ada data.</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr style="font-weight: bold; background-color: #f8f9fa;">
            @for ($j = 0; $j < count($headers); $j++)
                <td>
                @if($j === 0)
                {{ $bbnkb1Total[$j] ?? 'Jumlah' }}
                @else
                {{ (isset($bbnkb1Total[$j]) && $bbnkb1Total[$j] !== '') ? number_format((float)str_replace('.', '', $bbnkb1Total[$j]), 0, ',', '.') : '-' }}
                @endif
                </td>
                @endfor
        </tr>
    </tfoot>
</table>
@endif