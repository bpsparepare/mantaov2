@if($previewError)
<div class="notification error">{{ $previewError }}</div>
@else
<table>
    <thead>
        <tr>
            <th>Bulan</th>
            <th>Jumlah Pedagang Kaki Lima</th>
            <th>Jumlah Warung/Toko</th>
            <th>Jumlah Minimarket</th>
            <th>Jumlah Industri Kecil dan Menengah (IKM)</th>
            <th>Pendapatan Industri Kecil dan Menengah (IKM)</th>
            <th>Jumlah Industri Besar dan Sedang (IBS)</th>
            <th>Pendapatan Industri Besar dan Sedang (IBS)</th>
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
        </tr>
        @empty
        <tr>
            <td colspan="8" style="text-align: center;">Tidak ada data pratinjau yang dapat ditampilkan.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endif