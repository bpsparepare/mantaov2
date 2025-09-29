@if($previewError)
<div class="notification error">{{ $previewError }}</div>
@else
<table>
    <thead>
        <tr>
            <th>Bulan</th>
            <th>Jumlah Surat Dikirim</th>
            <th>Jumlah Surat Diterima</th>
            <th>Jumlah Wesel Pos (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sheetData as $row)
        <tr>
            <td>{{ $row[0] ?? '-' }}</td>
            <td>{{ (isset($row[1]) && $row[1] !== '') ? number_format((int)str_replace('.', '', $row[1]), 0, ',', '.') : '-' }}</td>
            <td>{{ (isset($row[2]) && $row[2] !== '') ? number_format((int)str_replace('.', '', $row[2]), 0, ',', '.') : '-' }}</td>
            <td>{{ (isset($row[3]) && $row[3] !== '') ? 'Rp ' . number_format((int)str_replace('.', '', $row[3]), 0, ',', '.') : '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="4" style="text-align: center;">Tidak ada data pratinjau.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endif