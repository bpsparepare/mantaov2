@if($previewError)
<div class="notification error">{{ $previewError }}</div>
@else
<table>
    <thead>
        <tr>
            <th rowspan="2" style="vertical-align: middle;">Bulan</th>
            <th colspan="2" style="text-align: center;">Pasar Lakessi</th>
            <th colspan="2" style="text-align: center;">Pasar Senggol</th>
            <th colspan="2" style="text-align: center;">Pasar Labukkang</th>
            <th colspan="2" style="text-align: center;">Pasar Sumpang</th>
            <th colspan="2" style="text-align: center;">Pasar Wekke'e</th>
        </tr>
        <tr>
            <th>Pedagang (unit)</th>
            <th>Pendapatan (Rp)</th>
            <th>Pedagang (unit)</th>
            <th>Pendapatan (Rp)</th>
            <th>Pedagang (unit)</th>
            <th>Pendapatan (Rp)</th>
            <th>Pedagang (unit)</th>
            <th>Pendapatan (Rp)</th>
            <th>Pedagang (unit)</th>
            <th>Pendapatan (Rp)</th>
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
            <td>{{ $row[5] ?? '-' }}</td>
            <td>{{ $row[6] ?? '-' }}</td>
            <td>{{ $row[7] ?? '-' }}</td>
            <td>{{ $row[8] ?? '-' }}</td>
            <td>{{ $row[9] ?? '-' }}</td>
            <td>{{ $row[10] ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="11" style="text-align: center;">Tidak ada data pratinjau yang dapat ditampilkan.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endif