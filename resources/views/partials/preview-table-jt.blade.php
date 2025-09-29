@if($previewError)
<div class="notification error">{{ $previewError }}</div>
@else
<table>
    <thead>
        <tr>
            <th>Bulan</th>
            <th>Jumlah Paket Dikirim</th>
            <th>Jumlah Paket Diterima</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sheetData as $row)
        <tr>
            <td>{{ $row[0] ?? '-' }}</td>
            {{-- PERBAIKAN: Cek apakah sel ada dan tidak kosong sebelum format --}}
            <td>{{ (isset($row[1]) && $row[1] !== '') ? number_format((int)str_replace('.', '', $row[1]), 0, ',', '.') : '-' }}</td>
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