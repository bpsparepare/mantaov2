@if($previewError)
<div class="notification error">{{ $previewError }}</div>
@else
<table>
    <thead>
        <tr>
            <th>Bulan</th>
            <th>Produksi Es Batu (Ton)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sheetData as $row)
        <tr>
            <td>{{ $row[0] ?? '-' }}</td>
            <td>{{ $row[1] ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="2" style="text-align: center;">Tidak ada data pratinjau.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endif