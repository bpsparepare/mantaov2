@if($previewError)
<div class="notification error">{{ $previewError }}</div>
@else
<table>
    <thead>
        {{-- Baris Header Pertama --}}
        <tr>
            <th rowspan="2" style="vertical-align: middle;">Bulan</th>
            <th colspan="2" style="text-align: center;">Angkutan Darat</th>
            <th colspan="2" style="text-align: center;">Angkutan Laut</th>
        </tr>
        {{-- Baris Header Kedua --}}
        <tr>
            <th>Jumlah penumpang naik (orang)</th>
            <th>Jumlah barang dimuat (Ton)</th>
            <th>Jumlah penumpang naik (orang)</th>
            <th>Jumlah barang dimuat (Ton)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sheetData as $row)
        <tr>
            <td>{{ $row[0] ?? '-' }}</td>
            <td>{{ $row[1] ?? '-' }}</td>
            <td>{{ $row[2] ?? '-' }}</td>
            <td>{{ $row[3] ?? '-' }}</td>
            <td>{{ $row[4] ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5" style="text-align: center;">Tidak ada data pratinjau yang dapat ditampilkan.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endif