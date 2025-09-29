@if($previewError)
<div class="notification error">{{ $previewError }}</div>
@else
<table>
    <thead>
        <tr>
            <th>Bulan</th>
            <th>Jumlah mobil yang terjual (Unit)</th>
            <th>Pendapatan (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sheetData as $row)
        <tr>
            <td>{{ $row[0] ?? '-' }}</td>
            <td>{{ $row[1] ?? '-' }}</td>
            <td>{{ $row[2] ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="3" style="text-align: center;">Tidak ada data pratinjau yang dapat ditampilkan.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endif