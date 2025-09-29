@if($previewError)
<div class="notification error">{{ $previewError }}</div>
@else
<table>
    <thead>
        <tr>
            <th>Bulan</th>
            <th>Jenis Bangunan</th>
            <th>Jumlah Unit</th>
            <th>Biaya dari PBG (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sheetData as $row)
        <tr>
            <td>{{ $row[0] ?? '-' }}</td>
            <td>{{ $row[1] ?? '-' }}</td>
            <td>{{ $row[2] ?? '-' }}</td>
            <td>{{ $row[3] ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="4" style="text-align: center;">Tidak ada data pratinjau yang dapat ditampilkan.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endif