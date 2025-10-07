@if($previewError)
<div class="notification error">{{ $previewError }}</div>
@else
<table>
    <thead>
        <tr>
            <th rowspan="2" style="vertical-align: middle;">Bulan</th>
            <th colspan="2" style="text-align: center;">Puskesmas</th>
            <th colspan="2" style="text-align: center;">Rumah Sakit</th>
        </tr>
        <tr>
            <th>Rawat Inap</th>
            <th>Rawat Jalan</th>
            <th>Rawat Inap</th>
            <th>Rawat Jalan</th>
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