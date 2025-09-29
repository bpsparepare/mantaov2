@if($previewError)
<div class="notification error">{{ $previewError }}</div>
@else
<table>
    <thead>
        <tr>
            <th>Bulan</th>
            <th>Pendapatan Usaha (Rp)</th>
            <th>Jumlah Pelanggan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sheetData as $row)
        <tr>
            <td>{{ $row[0] ?? '-' }}</td>
            <td>{{ (isset($row[1]) && $row[1] !== '') ? 'Rp ' . number_format((int)str_replace('.', '', $row[1]), 0, ',', '.') : '-' }}</td>
            <td>{{ (isset($row[2]) && $row[2] !== '') ? number_format((int)str_replace('.', '', $row[2]), 0, ',', '.') : '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="3" style="text-align: center;">Tidak ada data pratinjau.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endif